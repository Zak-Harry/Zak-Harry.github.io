<?php

namespace App\Repository;

use App\Entity\DepartementJob;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method DepartementJob|null find($id, $lockMode = null, $lockVersion = null)
 * @method DepartementJob|null findOneBy(array $criteria, array $orderBy = null)
 * @method DepartementJob[]    findAll()
 * @method DepartementJob[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DepartementJobRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, DepartementJob::class);
    }

    // /**
    //  * @return DepartementJob[] Returns an array of DepartementJob objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('d')
            ->andWhere('d.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('d.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?DepartementJob
    {
        return $this->createQueryBuilder('d')
            ->andWhere('d.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
