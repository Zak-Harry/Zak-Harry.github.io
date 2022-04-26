<?php

namespace App\Repository;

use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\DBAL\Exception;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method User|null find($id, $lockMode = null, $lockVersion = null)
 * @method User|null findOneBy(array $criteria, array $orderBy = null)
 * @method User[]    findAll()
 * @method User[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class);
    }

    /**
     * @param int $departement_id
     * @param int $userLogged_id
     * @return float|int|mixed|string
     */
    public function findByTeamDQL(int $departement_id, int $userLogged_id)
    {
        return $this->createQueryBuilder('u')
            ->where('u.departement = :dpt')
            ->andWhere('u.id != :id')
            ->setParameter('dpt', $departement_id)
            ->setParameter('id', $userLogged_id)
            ->getQuery()
            ->getResult();
    }

    /**
     * @return float|int|mixed|string
     */
    public function findByJob()
    {
        return $this->createQueryBuilder('u')
            ->where('u.role = 3')
            ->getQuery()
            ->getResult();
    }

    public function findByTeamSQL(int $departement_id, int $userLogged_id ): array
    {
        $conn = $this->getEntityManager()->getConnection();

        $sql = '
        SELECT *
        FROM `user`
        WHERE `departement_id` = '.$departement_id. ' AND `id` !=' .$userLogged_id;
            
        // exécution de la requete
        $results = $conn->executeQuery($sql);

        // returns an array of arrays (i.e. a raw data set)
        return $results->fetchAllAssociative();
    }

    /**
     * @throws Exception
     */
    public function findByManagerDepartementSQL(int $departement_id, int $role_id): array
    {
        $conn = $this->getEntityManager()->getConnection();

        $sql = '
        SELECT *
        FROM `user`
        WHERE `departement_id` = '.$departement_id. ' AND `role_id` =' .$role_id;
            
        // exécution de la requete
        $results = $conn->executeQuery($sql);

        // returns an array of arrays (i.e. a raw data set)
        return $results->fetchAllAssociative();
    }

    
    public function findByManagerDepartementDQL(int $departement_id, int $role_id)
    {
        $entityManager = $this->getEntityManager();
        $query =$entityManager->createQuery(
        ' SELECT u
        FROM App\Entity\User u
        WHERE u.departement = '.$departement_id. ' AND u.role =' .$role_id);
            
        // exécution de la requete
        // returns an array of arrays (i.e. a raw data set)
        return $query->getResult();
    }
    

    // /**
    //  * @return User[] Returns an array of User objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('u.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?User
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
