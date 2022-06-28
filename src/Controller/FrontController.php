<?php

namespace App\Controller;

use App\Repository\PageBuilderRepository;
use Dompdf\Dompdf;
use Dompdf\Options;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Contracts\Translation\TranslatorInterface;

class FrontController extends AbstractController
{
    /**
     * @Route("/", name="homepage")
     */
    public function index(Request $request): Response
    {
        return $this->render('front/index.html.twig');
    }


    /**
     * @Route("/changeLocale/{_locale}", name="locale")
     */
    public function locale(Request $request, $_locale): Response
    {

        $locale = $request->getLocale();

        $request->setLocale($locale);

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

    /**
     * @Route("/pdf", name="pdf")
     */
    public function pdf(): Response
    {
        // Configure Dompdf according to your needs
        $pdfOptions = new Options();
        $pdfOptions->set('defaultFont', 'Arial');

        // Instantiate Dompdf with our options
        $dompdf = new Dompdf($pdfOptions);

        // Retrieve the HTML generated in our twig file
        $html = $this->renderView('default/mypdf.html.twig', [
            'title' => "Welcome to our PDF Test"
        ]);

        // Load HTML to Dompdf
        $dompdf->loadHtml("coucou");

        // (Optional) Setup the paper size and orientation 'portrait' or 'portrait'
        $dompdf->setPaper('A4', 'portrait');

        // Render the HTML as PDF
        $dompdf->render();

        // Output the generated PDF to Browser (inline view)
        $dompdf->stream("mypdf.pdf", [
            "Attachment" => false
        ]);

        return new Response("The PDF file has been succesfully generated !");
    }
}
