<?php

namespace App\Controller;

use App\Entity\Application;
use App\Entity\Campaign;
use App\Entity\Session;
use App\Entity\Status;
use App\Repository\StatusRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
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
    public function test(): Response
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
