<?php

namespace App\Repository;

use App\Entity\AnswerUser;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method AnswerUser|null find($id, $lockMode = null, $lockVersion = null)
 * @method AnswerUser|null findOneBy(array $criteria, array $orderBy = null)
 * @method AnswerUser[]    findAll()
 * @method AnswerUser[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AnswerUserRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, AnswerUser::class);
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function add(AnswerUser $entity, bool $flush = true): void
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
    public function remove(AnswerUser $entity, bool $flush = true): void
    {
        $this->_em->remove($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    // /**
    //  * @return AnswerUser[] Returns an array of AnswerUser objects
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
    public function findOneBySomeField($value): ?AnswerUser
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
    public function getAnswer($campaign_id)
    {
        return $this->createQueryBuilder('a')
            ->select('qa.name, qa.id')
            ->innerJoin('a.question_answer', 'qa')
            ->innerJoin('qa.question', 'q')
            ->innerJoin('q.campaign', 'c')
            ->andWhere('c.id = :id')
            ->setParameter('id', $campaign_id)
            ->getQuery()
            ->getResult();
    }

    public function getAnswerByUser($user)
    {
        return $this->createQueryBuilder('a')
            ->innerJoin('a.question_answer', 'qa')
            ->innerJoin('qa.question', 'q')
            ->innerJoin('q.campaign', 'c')
            ->andWhere('a.user = :user')
            ->setParameter('user', $user)
            ->getQuery()
            ->getResult();
    }

    public function getQuestionsByCampaign(int $campaign_id)
    {
        return $this->createQueryBuilder('a')
            ->select('qa.name as questionAnswerName, q.name as questionName')
            ->innerJoin('a.question_answer', 'qa')
            ->innerJoin('qa.question', 'q')
            ->innerJoin('q.campaign', 'c')
            ->andWhere('c.id = :id')
            ->setParameter('id', $campaign_id)
            ->getQuery()
            ->getResult();
    }
}
