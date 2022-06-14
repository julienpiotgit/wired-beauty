<?php

namespace App\Controller;

use App\Repository\PageBuilderRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class FrontController extends AbstractController
{
    /**
     * @Route("/", name="homepage")
     */
    public function index(): Response
    {
        return $this->render('front/index.html.twig');
    }

    /**
     * @Route("/what-we-do", name="what")
     */
    public function what(PageBuilderRepository $pageBuilderRepository): Response
    {
        $result = $pageBuilderRepository->getWhatWeDoPage();
        $data = array_shift($result);
        return $this->render('front/what.html.twig', [
            'pageData' => $data
        ]);
    }
}
