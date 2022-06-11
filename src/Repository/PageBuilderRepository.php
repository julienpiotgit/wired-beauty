<?php

namespace App\Repository;

use App\Entity\PageBuilder;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Exception\ORMException;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<PageBuilder>
 *
 * @method PageBuilder|null find($id, $lockMode = null, $lockVersion = null)
 * @method PageBuilder|null findOneBy(array $criteria, array $orderBy = null)
 * @method PageBuilder[]    findAll()
 * @method PageBuilder[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PageBuilderRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PageBuilder::class);
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function add(PageBuilder $entity, bool $flush = false): void
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
    public function remove(PageBuilder $entity, bool $flush = false): void
    {
        $this->_em->remove($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

//    /**
//     * @return PageBuilder[] Returns an array of PageBuilder objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('p.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?PageBuilder
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
    public function getWhatWeDoPage()
    {
        return $this->createQueryBuilder('p')
            ->addSelect('s', 'points')
            ->leftJoin('p.sections', 's')
            ->leftJoin('s.points', 'points')
            ->andWhere('p.name = :name')
            ->setParameter('name', 'What we do')
            ->getQuery()
            ->getResult()
        ;
    }
}
