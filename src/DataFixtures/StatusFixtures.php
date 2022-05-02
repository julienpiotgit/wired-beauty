<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\Status;

class StatusFixtures extends Fixture
{

    //campaign status
    const STATUS_SOON = 'status_soon';
    const STATUS_ONGOING = 'status_ongoing';
    const STATUS_FINISH = 'status_finish';

    //application status
    const STATUS_PENDING = 'status_pending';
    const STATUS_ACCEPTED = 'status_accepted';
    const STATUS_REFUSED = 'status_refused';

    public function load(ObjectManager $manager): void
    {
        //status soon of the campaign
        $status_soon = new Status();
        $status_soon->setName("soon");
        $manager->persist($status_soon);

        //add reference status soon
        $this->addReference(self::STATUS_SOON, $status_soon);

        //status ongoing of the campaign
        $status_ongoing = new Status();
        $status_ongoing->setName("ongoing");
        $manager->persist($status_ongoing);

        //add reference status ongoing
        $this->addReference(self::STATUS_ONGOING, $status_ongoing);


        //status finish of the campaign
        $status_finish = new Status();
        $status_finish->setName("finish");
        $manager->persist($status_finish);

        //add reference status finish
        $this->addReference(self::STATUS_FINISH, $status_finish);


        //status pending of the application of the user for the session of the campaign
        $status_pending = new Status();
        $status_pending->setName("pending");
        $manager->persist($status_pending);

        //add reference status pending
        $this->addReference(self::STATUS_PENDING, $status_pending);


        //status accepted of the application of the user for the session of the campaign
        $status_accepted = new Status();
        $status_accepted->setName("accepted");
        $manager->persist($status_accepted);

        //add reference status accepted
        $this->addReference(self::STATUS_ACCEPTED, $status_accepted);


        //status refused of the application of the user for the session of the campaign
        $status_refused = new Status();
        $status_refused->setName("refused");
        $manager->persist($status_refused);

        //add reference status refused
        $this->addReference(self::STATUS_REFUSED, $status_refused);


        $manager->flush();
    }

}
