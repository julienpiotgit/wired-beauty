<?php


namespace App\EventListener;

use App\Entity\Question;
use App\Entity\QuestionAnswer;
use App\Entity\Status;
use App\Entity\User;
use App\Repository\StatusRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Event\LifecycleEventArgs;
use App\Entity\Campaign;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;


class CampaignStatusSetter
{
    /** @var UserPasswordEncoderInterface */
    private $passwordEncoder;

    private $statusRepository;
    private $em;
    private $client;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder,StatusRepository $statusRepository, EntityManagerInterface $em, HttpClientInterface $client) {
        $this->statusRepository = $statusRepository;
        $this->em = $em;
        $this->passwordEncoder = $passwordEncoder;
        $this->client = $client;
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

        if($entity instanceof User) {
            $password = $entity->getPassword();
            $entity->setPassword($this->passwordEncoder->encodePassword($entity, $password));
            $response = $this->client->request('GET', 'https://api-adresse.data.gouv.fr/search/?q=' . $entity->getPostalAddress());
            $content = $response->ToArray();

            $entity->setLongitude($content["features"][0]["geometry"]["coordinates"][0]);
            $entity->setLatitude($content["features"][0]["geometry"]["coordinates"][1]);
        }




    }
}
