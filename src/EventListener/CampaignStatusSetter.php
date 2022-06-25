<?php


namespace App\EventListener;

use App\Entity\Question;
use App\Entity\QuestionAnswer;
use App\Entity\Status;
use App\Repository\StatusRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Event\LifecycleEventArgs;
use App\Entity\Campaign;
use Doctrine\Persistence\ManagerRegistry;


class CampaignStatusSetter
{
    private $statusRepository;
    private $em;

    public function __construct(StatusRepository $statusRepository, EntityManagerInterface $em) {
        $this->statusRepository = $statusRepository;
        $this->em = $em;
    }

    public function prePersist(LifecycleEventArgs $args) {

        $entity = $args->getEntity();

        if ($entity instanceof Campaign) {
            $status = $this->statusRepository->findOneBy(['name' => 'soon']);
            $entity->addStatus($status);
            $entity->setNumberTester(0);


            $tmpfname = './uploads/'.$entity->getFile();

            $excelReader = \PhpOffice\PhpSpreadsheet\IOFactory::load($tmpfname);
            $excelReader->setActiveSheetIndexByName('Questions');
            $worksheet = $excelReader->getActiveSheet();
            $lastRow = $worksheet->getHighestRow();

            for ($row =1; $row <= $lastRow; $row++) {
                $question = new Question();
                $cell = $worksheet->getCell('A'.$row)->getFormattedValue();

                $question->setCampaign($entity);
                $question->setName($cell);
                $this->em->persist($question);

                $lastColumn = $worksheet->getHighestColumn();
                $lastColumn++;
                for ($column = 'B'; $column != $lastColumn; $column++) {
                    $reponse = new QuestionAnswer();
                    $cell = $worksheet->getCell($column . $row)->getFormattedValue();

                    if($cell != "") {
                        $reponse->setName($cell);
                        $reponse->setQuestion($question);
                        $this->em->persist($reponse);
                    }
                }
            }
        }






    }
}
