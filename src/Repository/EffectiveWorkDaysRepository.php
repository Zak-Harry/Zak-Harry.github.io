<?php

namespace App\Repository;

use App\Entity\EffectiveWorkDays;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method EffectiveWorkDays|null find($id, $lockMode = null, $lockVersion = null)
 * @method EffectiveWorkDays|null findOneBy(array $criteria, array $orderBy = null)
 * @method EffectiveWorkDays[]    findAll()
 * @method EffectiveWorkDays[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EffectiveWorkDaysRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, EffectiveWorkDays::class);
    }

    // /**
    //  * @return EffectiveWorkDays[] Returns an array of EffectiveWorkDays objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('e.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?EffectiveWorkDays
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */

    /**
     * Méthode permettant de trouver un user avec une date spécifique 
     *
     * @param integer $value
     * @param string $date
     * @return void
     */
    public function findEffectiveWorkUser (int $value, string $date){

        $conn = $this->getEntityManager()->getConnection();

        // on utilise le système d'alias pour représenter notre Entity
        // Dnas le select on dit que l'on veut TOUTE l'entité en utilisant l'alias
        $sql = "SELECT * 
            FROM effective_work_days 
            WHERE user_id=".$value." AND effective_work_days.startlog  
            LIKE '".$date."%';";

        $results = $conn->executeQuery($sql);

        // returns an array (i.e. a raw data set)
        return $results->fetchAssociative();
    }
}
