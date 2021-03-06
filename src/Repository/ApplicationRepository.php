<?php

namespace App\Repository;

use App\Entity\Application;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Application|null find($id, $lockMode = null, $lockVersion = null)
 * @method Application|null findOneBy(array $criteria, array $orderBy = null)
 * @method Application[]    findAll()
 * @method Application[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ApplicationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Application::class);
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function add(Application $entity, bool $flush = true): void
    {
        $this->_em->persist($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function remove(Application $entity, bool $flush = true): void
    {
        $this->_em->remove($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    // /**
    //  * @return Application[] Returns an array of Application objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('a.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Application
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */

    public function findCampaigns($user)
    {
        return $this->createQueryBuilder('a')
            ->addSelect('session')
            ->innerJoin('a.session', 'session')
            ->innerJoin('session.campaign', 'c')
            ->andWhere('a.user = :user')
            ->setParameter('user', $user)
            ->getQuery()
            ->getResult();
    }

    public function findCampaignsByAcceptedApplication($user)
    {
        return $this->createQueryBuilder('a')
            ->addSelect('session')
            ->innerJoin('a.session', 'session')
            ->innerJoin('a.status', 'status')
            ->innerJoin('session.campaign', 'c')
            ->andWhere('a.user = :user')
            ->setParameter('user', $user)
            ->andWhere('status.name = :name')
            ->setParameter('name', 'accepted')
            ->getQuery()
            ->getResult();
    }

    public function findCampaignsByStatusAndAcceptedApplication($user, $status)
    {
        return $this->createQueryBuilder('a')
            ->addSelect('session')
            ->innerJoin('a.session', 'session')
            ->innerJoin('a.status', 'status')
            ->innerJoin('session.campaign', 'c')
            ->andWhere('c.status = :cstatusname')
            ->setParameter('cstatusname', $status)
            ->andWhere('a.user = :user')
            ->setParameter('user', $user)
            ->andWhere('status.name = :astatusname')
            ->setParameter('astatusname', 'accepted')
            ->getQuery()
            ->getResult();
    }

    public function findApplicationByUserAndCampaignAndStatusAccepted($user, $campaign)
    {
        return $this->createQueryBuilder('a')
            ->addSelect('session')
            ->innerJoin('a.session', 'session')
            ->innerJoin('a.status', 'status')
            ->andWhere('session.campaign = :campaign')
            ->setParameter('campaign', $campaign)
            ->andWhere('a.user = :user')
            ->setParameter('user', $user)
            ->andWhere('status.name = :astatusname')
            ->setParameter('astatusname', 'accepted')
            ->getQuery()
            ->getOneOrNullResult();
    }

    public function findNumberByCampaign($campaignId)
    {
//        return $this->createQueryBuilder('a')
//            ->addSelect('a')
//            ->innerJoin('a.session', 's')
//            ->innerJoin('s.campaign', 'c')
//            ->innerJoin('a.status', 'st')
////            ->andWhere('a.status_id = :status_id')
////            ->setParameter('status_id', '11')
//            ->andWhere('c.id = :id')
//            ->setParameter('id', $campaignId)
//            ->getQuery()
//            ->getResult();
    }

    public function findNbPerson()
    {
        return $this->createQueryBuilder('a')
            ->select('session')
            ->innerJoin('a.session', 'session')
            ->innerJoin('session.campaign', 'campaign')
//            ->andWhere('status.name = :status')
//            ->setParameter('status', "soon")
            ->getQuery()
            ->getResult();
    }

}
