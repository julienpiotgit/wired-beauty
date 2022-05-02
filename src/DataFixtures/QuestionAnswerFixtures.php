<?php

namespace App\DataFixtures;

use App\Entity\QuestionAnswer;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class QuestionAnswerFixtures extends Fixture implements  DependentFixtureInterface
{
    const answer1question1 = "answer1question1";
    const answer3question2 = "answer3question2";
    const answer2question3 = "answer2question3";
    const answer5question4 = "answer5question4";
    const answer1question5 = "answer1question5";

    public function load(ObjectManager $manager): void
    {
        $answer1_question1 = new QuestionAnswer();
        $answer1_question1->setName("Exfoliating")
            ->setQuestion($this->getReference(QuestionFixtures::QUESTION1));
        $manager->persist($answer1_question1);
        $this->addReference(self::answer1question1, $answer1_question1);

        $answer2_question1 = new QuestionAnswer();
        $answer2_question1->setName("Cream")
            ->setQuestion($this->getReference(QuestionFixtures::QUESTION1));
        $manager->persist($answer2_question1);

        $answer3_question1 = new QuestionAnswer();
        $answer3_question1->setName("Water")
            ->setQuestion($this->getReference(QuestionFixtures::QUESTION1));
        $manager->persist($answer3_question1);

        $answer4_question1 = new QuestionAnswer();
        $answer4_question1->setName("Remover")
            ->setQuestion($this->getReference(QuestionFixtures::QUESTION1));
        $manager->persist($answer4_question1);

        $answer5_question1 = new QuestionAnswer();
        $answer5_question1->setName("Mask")
            ->setQuestion($this->getReference(QuestionFixtures::QUESTION1));
        $manager->persist($answer5_question1);


        $answer1_question2 = new QuestionAnswer();
        $answer1_question2->setName("Strongly agree")
            ->setQuestion($this->getReference(QuestionFixtures::QUESTION2));
        $manager->persist($answer1_question2);

        $answer2_question2 = new QuestionAnswer();
        $answer2_question2->setName("Somewhat agree")
            ->setQuestion($this->getReference(QuestionFixtures::QUESTION2));
        $manager->persist($answer2_question2);

        $answer3_question2 = new QuestionAnswer();
        $answer3_question2->setName("Neither agree nor disagree")
            ->setQuestion($this->getReference(QuestionFixtures::QUESTION2));
        $manager->persist($answer3_question2);
        $this->addReference(self::answer3question2, $answer3_question2);

        $answer4_question2 = new QuestionAnswer();
        $answer4_question2->setName("Somewhat disagree")
            ->setQuestion($this->getReference(QuestionFixtures::QUESTION2));
        $manager->persist($answer4_question2);

        $answer5_question2 = new QuestionAnswer();
        $answer5_question2->setName("Strongly disagree")
            ->setQuestion($this->getReference(QuestionFixtures::QUESTION2));
        $manager->persist($answer5_question2);

        $answer1_question3 = new QuestionAnswer();
        $answer1_question3->setName("Strongly agree")
            ->setQuestion($this->getReference(QuestionFixtures::QUESTION3));
        $manager->persist($answer1_question3);

        $answer2_question3 = new QuestionAnswer();
        $answer2_question3->setName("Somewhat agree")
            ->setQuestion($this->getReference(QuestionFixtures::QUESTION3));
        $manager->persist($answer2_question3);
        $this->addReference(self::answer2question3, $answer2_question3);

        $answer3_question3 = new QuestionAnswer();
        $answer3_question3->setName("Neither agree nor disagree")
            ->setQuestion($this->getReference(QuestionFixtures::QUESTION3));
        $manager->persist($answer3_question3);

        $answer4_question3 = new QuestionAnswer();
        $answer4_question3->setName("Somewhat disagree")
            ->setQuestion($this->getReference(QuestionFixtures::QUESTION3));
        $manager->persist($answer4_question3);

        $answer5_question3 = new QuestionAnswer();
        $answer5_question3->setName("Strongly disagree")
            ->setQuestion($this->getReference(QuestionFixtures::QUESTION3));
        $manager->persist($answer5_question3);

        $answer1_question4 = new QuestionAnswer();
        $answer1_question4->setName("Strongly agree")
            ->setQuestion($this->getReference(QuestionFixtures::QUESTION4));
        $manager->persist($answer1_question4);

        $answer2_question4 = new QuestionAnswer();
        $answer2_question4->setName("Somewhat agree")
            ->setQuestion($this->getReference(QuestionFixtures::QUESTION4));
        $manager->persist($answer2_question4);

        $answer3_question4 = new QuestionAnswer();
        $answer3_question4->setName("Neither agree nor disagree")
            ->setQuestion($this->getReference(QuestionFixtures::QUESTION4));
        $manager->persist($answer3_question4);

        $answer4_question4 = new QuestionAnswer();
        $answer4_question4->setName("Somewhat disagree")
            ->setQuestion($this->getReference(QuestionFixtures::QUESTION4));
        $manager->persist($answer4_question4);

        $answer5_question4 = new QuestionAnswer();
        $answer5_question4->setName("Strongly disagree")
            ->setQuestion($this->getReference(QuestionFixtures::QUESTION4));
        $manager->persist($answer5_question4);
        $this->addReference(self::answer5question4, $answer5_question4);

        $answer1_question5 = new QuestionAnswer();
        $answer1_question5->setName("Strongly agree")
            ->setQuestion($this->getReference(QuestionFixtures::QUESTION5));
        $manager->persist($answer1_question5);
        $this->addReference(self::answer1question5, $answer1_question5);

        $answer2_question5 = new QuestionAnswer();
        $answer2_question5->setName("Somewhat agree")
            ->setQuestion($this->getReference(QuestionFixtures::QUESTION5));
        $manager->persist($answer2_question5);

        $answer3_question5 = new QuestionAnswer();
        $answer3_question5->setName("Neither agree nor disagree")
            ->setQuestion($this->getReference(QuestionFixtures::QUESTION5));
        $manager->persist($answer3_question5);

        $answer4_question5 = new QuestionAnswer();
        $answer4_question5->setName("Somewhat disagree")
            ->setQuestion($this->getReference(QuestionFixtures::QUESTION5));
        $manager->persist($answer4_question5);

        $answer5_question5 = new QuestionAnswer();
        $answer5_question5->setName("Strongly disagree")
            ->setQuestion($this->getReference(QuestionFixtures::QUESTION5));
        $manager->persist($answer5_question5);

        $manager->flush();
    }

    public function getDependencies()
    {
        return array(
            QuestionFixtures::class,
        );
    }
}
