<?php

namespace App\DataFixtures;

use App\Entity\Product;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class ProductFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $product = new Product();
        $product->setName("poudre choco")
            ->setAgeRange("vieux")
            ->setGender("femme")
            ->setType("masque")
            ->setCampaign($this->getReference(CampaignFixtures::CAMPAIGN1))
            ->setUser($this->getReference(UserFixtures::CUSTOMER1));
        $manager->persist($product);

        $manager->flush();
    }

    public function getDependencies()
    {
        return array(
            CampaignFixtures::class,
            UserFixtures::class,
        );
    }
}
