<?php

namespace App\Repository;

use App\Entity\Product;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Product|null find($id, $lockMode = null, $lockVersion = null)
 * @method Product|null findOneBy(array $criteria, array $orderBy = null)
 * @method Product[]    findAll()
 * @method Product[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProductRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Product::class);
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function add(Product $entity, bool $flush = true): void
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
    public function remove(Product $entity, bool $flush = true): void
    {
        $this->_em->remove($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    // /**
    //  * @return Product[] Returns an array of Product objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('p.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Product
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
    public function findDetailsCampaign()
    {
        return $this->createQueryBuilder('p')
            ->addSelect('campaign')
            ->innerJoin('p.campaign', 'campaign')
            ->getQuery()
            ->getResult();
    }

    public function findCampaignOngoing()
    {
        return $this->createQueryBuilder('p')
            ->select('count(campaign)')
            ->innerJoin('p.campaign', 'campaign')
            ->innerJoin('campaign.status', 'status')
            ->andWhere('status.name = :status')
            ->setParameter('status', "ongoing")
            ->getQuery()
            ->getResult();
    }

    public function findCampaignFinish()
    {
        return $this->createQueryBuilder('p')
            ->select('count(campaign)')
            ->innerJoin('p.campaign', 'campaign')
            ->innerJoin('campaign.status', 'status')
            ->andWhere('status.name = :status')
            ->setParameter('status', "finish")
            ->getQuery()
            ->getResult();
    }

    public function findCampaignSoon()
    {
        return $this->createQueryBuilder('p')
            ->select('count(campaign)')
            ->innerJoin('p.campaign', 'campaign')
            ->innerJoin('campaign.status', 'status')
            ->andWhere('status.name = :status')
            ->setParameter('status', "soon")
            ->getQuery()
            ->getResult();
    }

    public function findCountCampaigns($user)
    {
        return $this->createQueryBuilder('a')
//            ->addSelect('product')
//            ->innerJoin('a.product', 'product')
            ->andWhere('a.user = :user')
            ->setParameter('user', $user)
            ->select('count(a)')
            ->getQuery()
            ->getResult();
    }

    public function findNumberTesterByCampaignFinish($user)
    {
        return $this->createQueryBuilder('p')
            ->addSelect('c.id, c.name, count(c.number_tester)')
            ->innerJoin('p.campaign', 'c')
            ->innerJoin('c.status', 's')
            ->andWhere('s.name = :name')
            ->setParameter('name', 'finish')
            ->andWhere('p.user = :user')
            ->setParameter('user', $user)
            ->andWhere('c.number_tester = :nb')
            ->setParameter('nb', 1)
            ->getQuery()
            ->getResult();
    }

    public function findNumberTesterByCampaignFinish2($user)
    {
        return $this->createQueryBuilder('p')
            ->addSelect('c.id, c.name, count(c.number_tester)')
            ->innerJoin('p.campaign', 'c')
            ->innerJoin('c.status', 's')
            ->andWhere('s.name = :name')
            ->setParameter('name', 'finish')
            ->andWhere('p.user = :user')
            ->setParameter('user', $user)
            ->getQuery()
            ->getResult();
    }
}
