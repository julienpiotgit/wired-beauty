<?php

namespace App\DataFixtures;

use App\Entity\Session;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class SessionFixtures extends Fixture implements DependentFixtureInterface
{
    const SESSION_PLACEBO = "produit placebo";

    public function load(ObjectManager $manager): void
    {
         $session_placebo = new Session();
         $session_placebo->setName("produit placebo")
             ->setCampaign($this->getReference(CampaignFixtures::CAMPAIGN1));
         $manager->persist($session_placebo);

         $this->addReference(self::SESSION_PLACEBO, $session_placebo);

        $manager->flush();
    }

    public function getDependencies()
    {
        return array(
            CampaignFixtures::class,
        );
    }
}
