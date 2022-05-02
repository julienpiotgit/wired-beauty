<?php

namespace App\DataFixtures;

use App\Entity\AnswerUser;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class AnswerUserFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $answer1user1 = new AnswerUser();
        $answer1user1->setUser($this->getReference(UserFixtures::TESTER1))
            ->setQuestionAnswer($this->getReference(QuestionAnswerFixtures::answer1question1));
        $manager->persist($answer1user1);

        $answer2user1 = new AnswerUser();
        $answer2user1->setUser($this->getReference(UserFixtures::TESTER1))
            ->setQuestionAnswer($this->getReference(QuestionAnswerFixtures::answer3question2));
        $manager->persist($answer2user1);

        $answer3user1 = new AnswerUser();
        $answer3user1->setUser($this->getReference(UserFixtures::TESTER1))
            ->setQuestionAnswer($this->getReference(QuestionAnswerFixtures::answer2question3));
        $manager->persist($answer3user1);

        $answer4user1 = new AnswerUser();
        $answer4user1->setUser($this->getReference(UserFixtures::TESTER1))
            ->setQuestionAnswer($this->getReference(QuestionAnswerFixtures::answer5question4));
        $manager->persist($answer4user1);

        $answer5user1 = new AnswerUser();
        $answer5user1->setUser($this->getReference(UserFixtures::TESTER1))
            ->setQuestionAnswer($this->getReference(QuestionAnswerFixtures::answer1question5));
        $manager->persist($answer5user1);

        $manager->flush();
    }

    public function getDependencies()
    {
        return array(
            UserFixtures::class,
            QuestionAnswerFixtures::class,
        );
    }
}
