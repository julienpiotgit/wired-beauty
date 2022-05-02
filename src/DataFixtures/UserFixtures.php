<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\User;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Faker\Factory;

class UserFixtures extends Fixture
{

    private $encoder;

    const CUSTOMER1 = "customer1";
    const ADMIN1 = "admin1";
    const TESTER1 = "tester1";


    public function __construct(UserPasswordHasherInterface $encoder)
    {
        $this->encoder = $encoder;
    }

    public function load(ObjectManager $manager): void
    {
        $fake = Factory::create();

        //Creation tester 1
        $tester1 = new User();

        $passHash = $this->encoder->hashPassword($tester1, 'tester');

        $tester1->setEmail($fake->email)
            ->setRoles(array("ROLE_TESTER"))
            ->setPassword($passHash)
            ->setFirstname($fake->firstName("female"))
            ->setName($fake->lastName)
            ->setAge(35)
            ->setHeight(164)
            ->setWeight(60)
            ->setPostalCode("69004")
            ->setCity($fake->city)
            ->setPostalAddress("sdiupzhf")
            ->setLongitude("5987")
            ->setLatitude("457889");

        $manager->persist($tester1);

        $this->addReference(self::TESTER1, $tester1);

        //Creation admin 1
        $admin1 = new User();

        $passHash = $this->encoder->hashPassword($admin1, 'admin');

        $admin1->setEmail($fake->email)
            ->setRoles(array("ROLE_ADMIN"))
            ->setPassword($passHash)
            ->setFirstname($fake->firstName("male"))
            ->setName($fake->lastName)
            ->setPostalCode("25410")
            ->setPostalAddress("sdiupzhf")
            ->setCity($fake->city)
            ->setLongitude("5987")
            ->setAge(35)
            ->setHeight(164)
            ->setWeight(60)
            ->setLatitude("457889");

        $manager->persist($admin1);

        $this->addReference(self::ADMIN1, $admin1);


        //Creation customer 1
        $customer1 = new User();

        $passHash = $this->encoder->hashPassword($customer1, 'customer');

        $customer1->setEmail($fake->email)
            ->setRoles(array("ROLE_CUSTOMER"))
            ->setPassword($passHash)
            ->setFirstname($fake->firstName("female"))
            ->setName($fake->lastName)
            ->setPostalCode("12458")
            ->setCity($fake->city)
            ->setNameCompany("Nestlé")
            ->setPostalAddress("sdiupzhf")
            ->setLongitude("5987")
            ->setAge(35)
            ->setHeight(164)
            ->setWeight(60)
            ->setLatitude("457889");

        $manager->persist($customer1);

        $this->addReference(self::CUSTOMER1, $customer1);

        $manager->flush();
    }


}
