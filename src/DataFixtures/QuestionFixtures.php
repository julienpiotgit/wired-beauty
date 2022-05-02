<?php

namespace App\DataFixtures;

use App\Entity\Question;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class QuestionFixtures extends Fixture implements DependentFixtureInterface
{

    const QUESTION1 = "question1";
    const QUESTION2 = "question2";
    const QUESTION3 = "question3";
    const QUESTION4 = "question4";
    const QUESTION5 = "question5";

    public function load(ObjectManager $manager): void
    {
        $question1 = new Question();
        $question1->setName("What cosmetic product do you use most often?")
            ->setCampaign($this->getReference(CampaignFixtures::CAMPAIGN1));
        $manager->persist($question1);
        $this->addReference(self::QUESTION1, $question1);

        $question2 = new Question();
        $question2->setName("The product is effective")
            ->setCampaign($this->getReference(CampaignFixtures::CAMPAIGN1));
        $manager->persist($question2);
        $this->addReference(self::QUESTION2, $question2);

        $question3 = new Question();
        $question3->setName("The product irritates my skin")
            ->setCampaign($this->getReference(CampaignFixtures::CAMPAIGN1));
        $manager->persist($question3);
        $this->addReference(self::QUESTION3, $question3);

        $question4 = new Question();
        $question4->setName("My skin Is comfortable")
            ->setCampaign($this->getReference(CampaignFixtures::CAMPAIGN1));
        $manager->persist($question4);
        $this->addReference(self::QUESTION4, $question4);

        $question5 = new Question();
        $question5->setName("Instant hydration")
            ->setCampaign($this->getReference(CampaignFixtures::CAMPAIGN1));
        $manager->persist($question5);
        $this->addReference(self::QUESTION5, $question5);


        $manager->flush();
    }

    public function getDependencies()
    {
        return array(
            CampaignFixtures::class,
        );
    }
}
