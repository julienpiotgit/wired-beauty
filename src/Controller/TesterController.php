<?php

namespace App\Controller;

use App\Entity\AnswerUser;
use App\Entity\Application;
use App\Entity\Campaign;
use App\Entity\Product;
use App\Entity\Session;
use App\Entity\Status;
use App\Repository\AnswerUserRepository;
use App\Repository\ApplicationRepository;
use App\Repository\CampaignRepository;
use App\Repository\ProductRepository;
use App\Repository\QuestionAnswerRepository;
use App\Repository\QuestionRepository;
use App\Repository\SessionRepository;
use App\Repository\StatusRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TesterController extends AbstractController
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @Route("/list_campaign_tester", name="list_campaign_tester")
     */
    public function list_campaign(ProductRepository $productRepository, ApplicationRepository $applicationRepository): Response
    {
        $statusSoon = $this->entityManager->getRepository(Status::class)->findOneBy(["name" => "soon"]);
        $statusOngoing = $this->entityManager->getRepository(Status::class)->findOneBy(["name" => "ongoing"]);
        $statusFinish = $this->entityManager->getRepository(Status::class)->findOneBy(["name" => "finish"]);

        $allCampaigns = $this->entityManager->getRepository(Campaign::class)->findAll();
        $allCampaignSoon = $this->entityManager->getRepository(Campaign::class)->findBy([
            'status' => $statusSoon
        ]);
        $allCampaignOngoing = $this->entityManager->getRepository(Campaign::class)->findBy([
            'status' => $statusOngoing
        ]);
        $allCampaignFinish = $this->entityManager->getRepository(Campaign::class)->findBy([
            'status' => $statusFinish
        ]);
        $currentUser = $this->getUser();
        $application = $applicationRepository->findCampaigns($currentUser);
        $detailsCampaign = $productRepository->findDetailsCampaign();

        return $this->render('tester/list_campaign.html.twig', [
            'allCampaigns' => $allCampaigns,
            'allCampaignSoon' => $allCampaignSoon,
            'allCampaignOngoing' => $allCampaignOngoing,
            'allCampaignFinish' => $allCampaignFinish,
            'details' => $detailsCampaign,
            'applications' => $application
        ]);
    }

    /**
     * @Route("/my_campaign", name="my_campaign")
     */
    public function my_campaign(AnswerUserRepository $answerUserRepository, ApplicationRepository $applicationRepository, ProductRepository $productRepository,QuestionRepository $questionRepository, QuestionAnswerRepository $questionAnswerRepository): Response
    {

        $currentUser = $this->getUser();
        $statusSoon = $this->entityManager->getRepository(Status::class)->findOneBy(["name" => "soon"]);
        $statusOngoing = $this->entityManager->getRepository(Status::class)->findOneBy(["name" => "ongoing"]);
        $statusFinish = $this->entityManager->getRepository(Status::class)->findOneBy(["name" => "finish"]);

        $allMyCampaigns = $applicationRepository->findCampaignsByAcceptedApplication($currentUser);


        $mycampaignsSoon = $applicationRepository->findCampaignsByStatusAndAcceptedApplication($currentUser, $statusSoon);
        $mycampaignsOngoing = $applicationRepository->findCampaignsByStatusAndAcceptedApplication($currentUser, $statusOngoing);
        $mycampaignsFinish = $applicationRepository->findCampaignsByStatusAndAcceptedApplication($currentUser, $statusFinish);



        $detailsCampaign = $productRepository->findDetailsCampaign();

        $question = $questionRepository->findAll();

        $answer = $questionAnswerRepository->findAll();

        $myanswer = $answerUserRepository->getAnswerByUser($currentUser);

        return $this->render('tester/my_campaign.html.twig', [
            "mycampaigns" => $allMyCampaigns,
            "mycampaignsSoon" => $mycampaignsSoon,
            "mycampaignsOngoing" => $mycampaignsOngoing,
            "mycampaignsFinish" => $mycampaignsFinish,
            "details" => $detailsCampaign,
            "questions" => $question,
            "answers" => $answer,
            "myanswers" => $myanswer
        ]);
    }

    /**
     * @Route("/add_qcm", name="add_qcm")
     */
    public function add_qcm(Request $request,CampaignRepository $campaignRepository, ApplicationRepository $applicationRepository, UserRepository $userRepository, EntityManagerInterface $entityManager, QuestionAnswerRepository $questionAnswerRepository): Response
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
        $campaignId = $request->request->get('campaign');
        $campaign = $campaignRepository->findOneBy(['id' => $campaignId]);

        $application = $applicationRepository->findApplicationByUserAndCampaignAndStatusAccepted($user,$campaign);

        for($i =0; $i < $number_answers; $i++){
            $answerUser = new AnswerUser();

            $question = $questionAnswerRepository->findOneBy(["id" => $answers[$i]]);

            $answerUser->setUser($user);
            $answerUser->setQuestionAnswer($question);
            $entityManager->persist($answerUser);
            $entityManager->flush();
        }
        $application->setQcmIsAnswered(true);
        $entityManager->flush();
        return $this->redirectToRoute("admin");
    }
    /**
     * @Route("/add_application", name="add_application")
     */
    public function add_application(SessionRepository $sessionRepository, StatusRepository $statusRepository, UserRepository $userRepository, Request $request, EntityManagerInterface $entityManager): Response
    {
        $currentUser = $this->getUser();
        $user = $userRepository->find($currentUser);

        // Is it an Ajax Request ?
        if (!$request->isXmlHttpRequest())
            return new JsonResponse(array('status' => 'Error'), 400);

        // Request has request data ?
        if (!isset($request->request))
            return new JsonResponse(array('status' => 'Error'), 400);

        $campaignId = $request->request->get("campaign");

        $statusPending = $statusRepository->findOneBy(["name" => "pending"]);

        $session = $sessionRepository->findBy(["campaign" => $campaignId]);

        $sessioncount = $sessionRepository->count(["campaign" => $campaignId]);

        $sessionRandom = random_int(1, $sessioncount);

        $application = new Application();
        $application->setUser($user);
        $application->setStatus($statusPending);
        $application->setSession($session[$sessionRandom-1]);
        $application->setQcmIsAnswered(false);

        $entityManager->persist($application);
        $entityManager->flush();

        return $this->redirectToRoute("admin");
    }
}
