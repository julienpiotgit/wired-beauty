<?php

namespace App\DataFixtures;

use App\Entity\Application;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class ApplicationFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $application = new Application();
        $application->setUser($this->getReference(UserFixtures::TESTER1))
            ->setSession($this->getReference(SessionFixtures::SESSION_PLACEBO))
            ->setStatus($this->getReference(StatusFixtures::STATUS_PENDING));
        $manager->persist($application);


        $manager->flush();
    }

    public function getDependencies()
    {
        return array(
            UserFixtures::class,
            SessionFixtures::class,
            StatusFixtures::class,
        );
    }
}
