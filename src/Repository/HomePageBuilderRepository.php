<?php

namespace App\Repository;

use App\Entity\HomePageBuilder;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Exception\ORMException;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<HomePageBuilder>
 *
 * @method HomePageBuilder|null find($id, $lockMode = null, $lockVersion = null)
 * @method HomePageBuilder|null findOneBy(array $criteria, array $orderBy = null)
 * @method HomePageBuilder[]    findAll()
 * @method HomePageBuilder[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class HomePageBuilderRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, HomePageBuilder::class);
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function add(HomePageBuilder $entity, bool $flush = false): void
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
    public function remove(HomePageBuilder $entity, bool $flush = false): void
    {
        $this->_em->remove($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

//    /**
//     * @return HomePageBuilder[] Returns an array of HomePageBuilder objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('h')
//            ->andWhere('h.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('h.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?HomePageBuilder
//    {
//        return $this->createQueryBuilder('h')
//            ->andWhere('h.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
