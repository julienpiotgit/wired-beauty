<?php

namespace App\Controller;

use App\Entity\Campaign;
use App\Entity\Question;
use App\Entity\QuestionAnswer;
use App\Entity\User;
use DateTime;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    /**
     * @Route("/homee", name="app_homee")
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



        echo $lastRow;
        $row = 1;
//        for ($column = 'A'; $column != $lastRow; $column++) {
        for ($row =1; $row <= $lastRow; $row++) {
            $question = new Question();
            $cell = $worksheet->getCell('A'.$row)->getFormattedValue();
//            echo $cell;
//            echo "<br>";
            $question->setCampaign($campaign);
            $question->setName($cell);
//            $em->persist($question);
        }
//        $em->flush();

        $row = 1;
        $lastColumn = $worksheet->getHighestColumn();
        $lastColumn++;
//        for ($column = 'B'; $column != $lastColumn; $column++) {
        for ($row =1; $row <= $lastRow; $row++) {
            $reponse = new QuestionAnswer();
            for ($column = 'B'; $column != $lastColumn; $column++) {
                $cell = $worksheet->getCell($column . $row)->getFormattedValue();
                echo $cell;
                echo "<br>";
            }
            $reponse->setName($cell);
//            $reponse->setQuestion($question);
            $em->persist($question);
            $em->flush();
        }



        return $this->render('homee/index.html.twig', [
            'controller_name' => 'HomeeController',
        ]);
    }
}