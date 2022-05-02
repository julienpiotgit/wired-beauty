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
    public function load(ObjectManager $manager): void
    {
        $campaign = new Campaign();

        /**
         * @param DateTime $date
         */
        $date_start = new DateTime('11-03-2022');
        $date_end = new DateTime('18-03-2022');

        $campaign->setName("campagne 1")
            ->setDescription("description campagne 1")
            ->setFile("nom fichier question")
            ->setDateStart($date_start)
            ->setDateEnd($date_end)
            ->setStatus($this->getReference(StatusFixtures::STATUS_SOON));
        $manager->persist($campaign);

        $this->addReference(self::CAMPAIGN1, $campaign);

        $manager->flush();
    }

    public function getDependencies()
    {
        return array(
            StatusFixtures::class,
        );
    }
}
