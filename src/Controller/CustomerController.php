<?php

namespace App\Controller;

use App\Entity\AnswerUser;
use App\Entity\Campaign;
use App\Entity\QuestionAnswer;
use App\Entity\Status;
use App\Entity\Product;
use App\Repository\AnswerUserRepository;
use App\Repository\ApplicationRepository;
use App\Repository\CampaignRepository;
use App\Repository\ProductRepository;
use App\Repository\QuestionAnswerRepository;
use App\Repository\QuestionRepository;
use App\Repository\StatusRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Dompdf\Dompdf;
use Dompdf\Options;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use Screen\Capture;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Annotation\Route;

class CustomerController extends AbstractController
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @Route("/list_campaign_customer", name="list_campaign_customer")
     */
    public function list_campaign(): Response
    {
        $allCampaigns = $this->entityManager->getRepository(Campaign::class)->findAll();
        $statusSoon = $this->entityManager->getRepository(Status::class)->findOneBy(["name" => "soon"]);
        $statusFinish = $this->entityManager->getRepository(Status::class)->findOneBy(["name" => "finish"]);

        $allCampaignSoon = $this->entityManager->getRepository(Campaign::class)->findBy([
            'status' => $statusSoon
        ]);

        $allCampaignFinish = $this->entityManager->getRepository(Campaign::class)->findBy([
            'status' => $statusFinish
        ]);

        return $this->render('customer/list_campaign.html.twig', [
            'allCampaigns' => $allCampaigns,
            'allCampaignSoon' => $allCampaignSoon,
            'allCampaignFinish' => $allCampaignFinish
        ]);
    }

    /**
     * @Route("/my_campaign_customer", name="my_campaign_customer")
     */
    public function my_campaign(CampaignRepository $campaignRepository, ApplicationRepository $applicationRepository, ProductRepository $productRepository,QuestionRepository $questionRepository, QuestionAnswerRepository $questionAnswerRepository, StatusRepository $statusRepository): Response
    {
        $currentUser = $this->getUser();

        $mycampaigns = $productRepository->findCampaigns($currentUser);

        $mycampaigncount = $productRepository->findCountCampaigns($currentUser);

        $detailsCampaign = $productRepository->findDetailsCampaign();

        $detailsCampaignStats = [];
        $detailsCampaignOngoing = $productRepository->findCampaignOngoing($currentUser);
        $detailsCampaignFinish = $productRepository->findCampaignFinish($currentUser);
        $detailsCampaignSoon = $productRepository->findCampaignSoon($currentUser);
        $detailsCampaignStats = [$detailsCampaignSoon[0][1],$detailsCampaignOngoing[0][1],$detailsCampaignFinish[0][1]];

        $statusNom = [];
        $status = $statusRepository->findAll();
        foreach ($status as $stat) {
            if ($stat->getType() == "campaign"){
                $statusNom[] = $stat->getName();
            }
        }

        $nbPersonsTester = $productRepository->findNumberTesterByCampaignFinish($currentUser);
        $nbPersons = $productRepository->findNumberTesterByCampaignFinish2($currentUser);
        $nbPersons = [$nbPersons[0][1], $nbPersonsTester[0][1]];

        $questions = $questionRepository->findCampaignQuestion();

        return $this->render('customer/my_campaign.html.twig', [
            "statusnom" => json_encode($statusNom),
            "mycampaigns" => $mycampaigns,
            "mycampaignscount" =>  json_encode($mycampaigncount[0][1]),
            "details" =>  $detailsCampaign,
            "details_stats" =>  json_encode($detailsCampaignStats),
            "nbpersons" => json_encode($nbPersons),
            "questions" => $questions,
        ]);
    }

    /**
     * @Route("/all_campaign_customer", name="all_campaign_customer")
     */
    public function all_campaign(ApplicationRepository $applicationRepository, ProductRepository $productRepository,QuestionRepository $questionRepository, QuestionAnswerRepository $questionAnswerRepository, StatusRepository $statusRepository): Response
    {
        $currentUser = $this->getUser();

//        $mycampaigns = $applicationRepository->findCampaigns($currentUser);
        $mycampaigns = $productRepository->findCampaigns($currentUser);

        $detailsCampaign = $productRepository->findDetailsCampaign();

        $statusNom = [];
        $status = $statusRepository->findAll();
        foreach ($status as $stat) {
            if ($stat->getType() == "campaign"){
                $statusNom[] = $stat->getName();
            }
        }

        $questions = $questionRepository->findCampaignQuestion();

        return $this->render('customer/all_campaign.html.twig', [
            "statusnom" => json_encode($statusNom),
            "mycampaigns" => $mycampaigns,
            "details" =>  $detailsCampaign,
            "questions" => $questions,
        ]);
    }


    /**
     * @Route("/campaign_details/{id}", name="campaign_details")
     */
    public function campaign_details(Request $request, AnswerUserRepository $answerUserRepository): Response
    {
        $campaign_id = $request->attributes->get("_route_params");
        $campaign_id = (int)array_shift($campaign_id);
        $questions = $answerUserRepository->getQuestionsByCampaign($campaign_id);
        $array = [];
        foreach ($questions as $key => $question) {
            if (!array_key_exists($question['questionName'], $array)) {
                $array[$question['questionName']]['liste_reponse'] = [$question['questionAnswerName']];
                $array[$question['questionName']]['occurence'] = [$question['questionAnswerName'] => 1];
                continue;
            }
            if (array_key_exists($question['questionName'], $array)) {
                if (in_array($question['questionAnswerName'], $array[$question['questionName']]['liste_reponse'])) {
                    $array[$question['questionName']]['occurence'][$question['questionAnswerName']] += 1;
                    continue;
                }
                $array[$question['questionName']]['liste_reponse'][] = $question['questionAnswerName'];
                $array[$question['questionName']]['occurence'][$question['questionAnswerName']] = 1;
            }
        }
        return $this->render('customer/campaign_details.html.twig', [
            "questions"=>json_encode($questions),
            "questionsArray"=>$questions,
            "question"=>json_encode($array),
            "answername"=>json_encode(array_keys($array)),
        ]);
    }

    /**
     * @Route("/add_qcm", name="add_qcm")
     */
    public function add_qcm(Request $request, UserRepository $userRepository, EntityManagerInterface $entityManager, QuestionAnswerRepository $questionAnswerRepository): Response
    {
        $currentUser = $this->getUser();
        $user = $userRepository->find($currentUser);

        // Is it an Ajax Request ?
        if (!$request->isXmlHttpRequest())
            return new JsonResponse(array('status' => 'Error'), 400);

        // Request has request data ?
        if (!isset($request->request))
            return new JsonResponse(array('status' => 'Error'), 400);

        $answers = $request->request->get('answers');
        $number_answers = $request->request->get('number_answers');

        for($i =0; $i < $number_answers; $i++){
            $answerUser = new AnswerUser();

            $question = $questionAnswerRepository->findOneBy(["id" => $answers[$i]]);

            $answerUser->setUser($user);
            $answerUser->setQuestionAnswer($question);
            $entityManager->persist($answerUser);
            $entityManager->flush();
        }

        return $this->redirectToRoute("admin");
    }

    /**
     * @Route("/contact_campaign", name="contact_campaign")
     */
    public function contact_campaign(Request $request, MailerInterface $mailer, UserRepository $userRepository): Response
    {
        $currentUser = $this->getUser();
        $user = $userRepository->find($currentUser);

        $form = $this->createFormBuilder()
            ->add('name_campaign', TextType::class)
            ->add('description_campaign', TextareaType::class)
            ->add('datestart', DateType::class, [
                'label' => 'Date Start'
            ])
            ->add('dateend', DateType::class, [
                'label' => 'Date End'
            ])
            ->add('file', FileType::class)
            ->add('sessions', TextareaType::class)
            ->add('name_product', TextType::class)
            ->add('type', TextType::class)
            ->add('gender', TextType::class)
            ->add('agerange', TextType::class, [
                'label' => 'Age range'
            ])
            ->add('send', SubmitType::class, [
                'label' => 'Send'
            ])
            ->getForm();

        $form->handleRequest($request);


        if($form->isSubmitted() && $form->isValid()) {

            $contactFormData = $form->getData();
            $destination = './emailFile';
            $contactFormData['file']->move($destination, $contactFormData['file']->getClientOriginalName());

            $message = (new Email())
                ->from($user->getEmail())
                ->to('mekieses1234@gmail.com')
                ->subject('Creation campaign')
                ->html("
                    
                    <h1 style='text-align: center; color:blue'>Request of creation of campaign by {$user->getFirstname()} {$user->getName()} </h1>
                    <div>
                    <h2>Infos campaign</h2>
                    <p>-Name : {$contactFormData['name_campaign']}</p>
                    <p>-Description : {$contactFormData['description_campaign']}</p>
                    <p>-Date start / Date end : {$contactFormData['datestart']->format('d/m/Y')} / {$contactFormData['dateend']->format('d/m/Y')}</p>
                    <p>-Sessions : {$contactFormData['sessions']}</p>
                   <br>
                    <h2>Infos product</h2>
                    <p>-Name : {$contactFormData['name_product']}</p>
                    <p>-type : {$contactFormData['type']}</p>
                    <p>-gender : {$contactFormData['gender']}</p>
                    <p>-age range : {$contactFormData['agerange']}</p>
                    <br>
                    <p><span style='color: red'>if need more information, contact</span> {$user->getEmail()}</p>
                    </div>
                ")
                ->attachFromPath('./emailFile/'.$contactFormData['file']->getClientOriginalName());
            $mailer->send($message);

            $filesystem = new Filesystem();
            $filesystem->remove('./emailFile/'.$contactFormData['file']->getClientOriginalName());

            $this->addFlash('success', 'Your message has been sent');

            return $this->redirectToRoute('admin');
        }



        return $this->render('customer/contact.html.twig', [
            'form_contact' => $form->createView()
        ]);
    }
}
