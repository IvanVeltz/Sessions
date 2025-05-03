<?php

namespace App\Repository;

use App\Entity\Trainee;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Trainee>
 */
class TraineeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Trainee::class);
    }

    public function findTraineesNotInSession($value): array
    {
        $subQuery = $this->createQueryBuilder('t')
            ->select('t.id')
            ->join('t.sessions', 's')
            ->andWhere('s.id = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getDQL()
            ;

        $qb = $this->createQueryBuilder('tr');
        
        
        return $qb->select('tr')
            ->where($qb->expr()->notIn('tr.id', $subQuery))
            ->setParameter('val', $value)
            ->orderBy('tr.lastName', 'ASC')
            ->getQuery()
            ->getResult()
            ;
    }

    //    /**
    //     * @return Trainee[] Returns an array of Trainee objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('t')
    //            ->andWhere('t.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('t.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Trainee
    //    {
    //        return $this->createQueryBuilder('t')
    //            ->andWhere('t.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
