<?php

namespace App\Controller;

use App\Entity\Campaign;
use App\Entity\Question;
use App\Entity\QuestionAnswer;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    /**
     * @Route("/home", name="app_home")
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     */
    public function index(EntityManagerInterface $em): Response
    {
        //Permet de créer les questions par rapport à la campaign créee
        $campaign = new Campaign();
        $date_start = new DateTime('11-03-2022');
        $date_end = new DateTime('18-03-2022');

        $campaign->setName("campagne 1")
            ->setDescription("description campagne 1")
            ->setFile("Dataa.xlsx")
            ->setDateStart($date_start)
            ->setDateEnd($date_end);
        $em->persist($campaign);
        $em->flush();

        $tmpfname = './uploads/'.$campaign->getFile();

//        $tmpfname = './uploads/Dataa.xlsx';
        $excelReader = \PhpOffice\PhpSpreadsheet\IOFactory::load($tmpfname);
        $excelReader->setActiveSheetIndexByName('Questions');
        $worksheet = $excelReader->getActiveSheet();
        $lastRow = $worksheet->getHighestRow();

        for ($row =1; $row <= $lastRow; $row++) {
            $question = new Question();
            $cell = $worksheet->getCell('A'.$row)->getFormattedValue();
//            echo $cell;
//            echo "<br>";
            $question->setCampaign($campaign);
            $question->setName($cell);
            $em->persist($question);

            $lastColumn = $worksheet->getHighestColumn();
            $lastColumn++;
            for ($column = 'B'; $column != $lastColumn; $column++) {
                $reponse = new QuestionAnswer();
                $cell = $worksheet->getCell($column . $row)->getFormattedValue();
//                echo $cell;
//                echo "<br>";
                if($cell != "") {
                    $reponse->setName($cell);
                    $reponse->setQuestion($question);
                    $em->persist($reponse);
                }
            }
        }
        $em->flush();


        return $this->render('home/index.html.twig', [
            'controller_name' => 'HomeController',
        ]);
    }
}