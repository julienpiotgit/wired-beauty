<?php

namespace App\Controller;

use App\Entity\Campaign;
use App\Entity\Question;
use App\Entity\QuestionAnswer;
use App\Form\CampaignType;
use App\Repository\CampaignRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/qcm")
 */
class QcmController extends AbstractController
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @Route("/", name="app_qcm_index", methods={"GET"})
     */
    public function index(CampaignRepository $campaignRepository): Response
    {
        return $this->render('qcm/index.html.twig', [
            'campaigns' => $campaignRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="app_qcm_new", methods={"GET", "POST"})
     */
    public function new(Request $request, CampaignRepository $campaignRepository): Response
    {
        $campaign = new Campaign();
        $form = $this->createForm(CampaignType::class, $campaign);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $campaignRepository->add($campaign);
            return $this->redirectToRoute('app_qcm_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('qcm/new.html.twig', [
            'campaign' => $campaign,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="app_qcm_show", methods={"GET"})
     */
    public function show(Campaign $campaign): Response
    {
        var_dump($campaign->getId());
        $allCurrentCampaign = $this->entityManager->getRepository(Question::class)->findAll();
        var_dump($allCurrentCampaign);
        return $this->render('qcm/show.html.twig', [
            'campaign' => $campaign,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="app_qcm_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Campaign $campaign, CampaignRepository $campaignRepository): Response
    {
        $form = $this->createForm(CampaignType::class, $campaign);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $campaignRepository->add($campaign);
            return $this->redirectToRoute('app_qcm_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('qcm/edit.html.twig', [
            'campaign' => $campaign,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="app_qcm_delete", methods={"POST"})
     */
    public function delete(Request $request, Campaign $campaign, CampaignRepository $campaignRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$campaign->getId(), $request->request->get('_token'))) {
            $campaignRepository->remove($campaign);
        }

        return $this->redirectToRoute('app_qcm_index', [], Response::HTTP_SEE_OTHER);
    }
}
