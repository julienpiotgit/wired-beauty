<?php

namespace App\EventSubscriber;

use App\Repository\CampaignRepository;
use App\Repository\StatusRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\ControllerEvent;

class CampaignSubscriber implements EventSubscriberInterface
{
    private $statusRepository;
    private $campaignRepository;
    private $entityManager;

    public function __construct(StatusRepository $statusRepository, CampaignRepository $campaignRepository, EntityManagerInterface $entityManager) {
        $this->statusRepository = $statusRepository;
        $this->campaignRepository = $campaignRepository;
        $this->entityManager = $entityManager;
    }

    public function onControllerEvent(ControllerEvent $event)
    {
        $campaigns = $this->campaignRepository->findAll();
        $statusOngoing = $this->statusRepository->findOneBy(['name' => 'ongoing']);
        $statusFinish = $this->statusRepository->findOneBy(['name' => 'finish']);
        $statusSoon = $this->statusRepository->findOneBy(['name' => 'soon']);

        foreach ($campaigns as $campaign){
            $dateStart = $campaign->getDateStart();
            $dateEnd = $campaign->getDateEnd();
            $dateNow = new \DateTime();
            if($dateStart > $dateNow && $dateEnd > $dateNow){
                $campaign->setStatus($statusSoon);
            }
            elseif($dateStart < $dateNow && $dateEnd > $dateNow){
                $campaign->setStatus($statusOngoing);
            }
            elseif($dateStart< $dateNow && $dateEnd < $dateNow){
                $campaign->setStatus($statusFinish);
            }
            $this->entityManager->persist($campaign);
            $this->entityManager->flush();
        }
    }

    public static function getSubscribedEvents()
    {
        return [
            'kernel.controller' => 'onControllerEvent',
        ];
    }
}
