<?php

namespace App\Controller;

use App\Entity\AnswerUser;
use App\Entity\Application;
use App\Entity\Campaign;
use App\Entity\Session;
use App\Entity\Status;
use App\Repository\ApplicationRepository;
use App\Repository\ProductRepository;
use App\Repository\QuestionAnswerRepository;
use App\Repository\QuestionRepository;
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
     * @Route("/list_campaign", name="list_campaign")
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

        return $this->render('tester/list_campaign.html.twig', [
            'allCampaigns' => $allCampaigns,
            'allCampaignSoon' => $allCampaignSoon,
            'allCampaignFinish' => $allCampaignFinish
        ]);
    }

    /**
     * @Route("/my_campaign", name="my_campaign")
     */
    public function my_campaign(ApplicationRepository $applicationRepository, ProductRepository $productRepository,QuestionRepository $questionRepository, QuestionAnswerRepository $questionAnswerRepository): Response
    {

        $currentUser = $this->getUser();

        $mycampaigns = $applicationRepository->findCampaigns($currentUser);

        $detailsCampaign = $productRepository->findDetailsCampaign();

        $question = $questionRepository->findAll();

        $answer = $questionAnswerRepository->findAll();

        return $this->render('tester/my_campaign.html.twig', [
            "mycampaigns" => $mycampaigns,
            "details" => $detailsCampaign,
            "questions" => $question,
            "answers" => $answer
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
     * @Route("/add_application", name="add_application")
     */
    public function add_application(UserRepository $userRepository, Request $request, EntityManagerInterface $entityManager): Response
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

        $statusPending = $this->entityManager->getRepository(Status::class)->findOneBy(["name" => "pending"]);

        $session = $this->entityManager->getRepository(Session::class)->findBy(["campaign" => $campaignId]);

        $sessioncount = $this->entityManager->getRepository(Session::class)->count(["campaign" => $campaignId]);

        $sessionRandom = random_int(1, $sessioncount);

        $application = new Application();
        $application->setUser($user);
        $application->setStatus($statusPending);
        $application->setSession($session[$sessionRandom-1]);

        $entityManager->persist($application);
        $entityManager->flush();

        return $this->redirectToRoute("admin");
    }
}
