<?php

namespace App\Repository;

use App\Entity\PlannedWorkDays;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method PlannedWorkDays|null find($id, $lockMode = null, $lockVersion = null)
 * @method PlannedWorkDays|null findOneBy(array $criteria, array $orderBy = null)
 * @method PlannedWorkDays[]    findAll()
 * @method PlannedWorkDays[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PlannedWorkDaysRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PlannedWorkDays::class);
    }

    // /**
    //  * @return PlannedWorkDays[] Returns an array of PlannedWorkDays objects
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
    public function findOneBySomeField($value): ?PlannedWorkDays
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */

    /**
     * Méthode qui permet de retrouver un user ainsi qu'un jour spécifique
     *
     * @param integer $value
     * @param string $date
     * @return void
     */
    public function findOneUserPlanning (int $value, string $date){

        $conn = $this->getEntityManager()->getConnection();

        // on utilise le système d'alias pour représenter notre Entity
        // Dnas le select on dit que l'on veut TOUTE l'entité en utilisant l'alias
        $sql = "SELECT * 
            FROM user_planned_work_days 
            JOIN planned_work_days 
            ON planned_work_days.id = user_planned_work_days.planned_work_days_id 
            WHERE user_id ='.$value.' AND planned_work_days.startshift 
            LIKE '".$date."%';";

        $results = $conn->executeQuery($sql);

        // returns an array (i.e. a raw data set)
        return $results->fetchAssociative();
    }
}
