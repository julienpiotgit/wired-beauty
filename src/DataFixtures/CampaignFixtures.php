<?php

namespace App\DataFixtures;

use App\Entity\Campaign;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use DateTime;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class CampaignFixtures extends Fixture implements DependentFixtureInterface
{

    const CAMPAIGN1 = "campaign1";
    const CAMPAIGN2 = "campaign2";
    const CAMPAIGN3 = "campaign3";
    public function load(ObjectManager $manager): void
    {
        $campaign_soon = new Campaign();
        $campaign_ongoing = new Campaign();
        $campaign_finish = new Campaign();

        /**
         * @param DateTime $date
         */
        $date_start = new DateTime('11-03-2022');
        $date_end = new DateTime('18-03-2022');

        //campaign soon
        $campaign_soon->setName("campagne 1")
            ->setDescription("description campagne 1")
            ->setFile("nom fichier question")
            ->setDateStart($date_start)
            ->setDateEnd($date_end)
            ->setStatus($this->getReference(StatusFixtures::STATUS_SOON));
        $manager->persist($campaign_soon);
        $this->addReference(self::CAMPAIGN1, $campaign_soon);


        //campaign ongoing
        $campaign_ongoing->setName("campagne 2")
            ->setDescription("description campagne 2")
            ->setFile("nom fichier question")
            ->setDateStart($date_start)
            ->setDateEnd($date_end)
            ->setStatus($this->getReference(StatusFixtures::STATUS_ONGOING));
        $manager->persist($campaign_ongoing);
        $this->addReference(self::CAMPAIGN2, $campaign_ongoing);


        //campaign finish
        $campaign_finish->setName("campagne 3")
            ->setDescription("description campagne 3")
            ->setFile("nom fichier question")
            ->setDateStart($date_start)
            ->setDateEnd($date_end)
            ->setStatus($this->getReference(StatusFixtures::STATUS_FINISH));
        $manager->persist($campaign_finish);
        $this->addReference(self::CAMPAIGN3, $campaign_finish);

        $manager->flush();
    }

    public function getDependencies()
    {
        return array(
            StatusFixtures::class,
        );
    }
}
