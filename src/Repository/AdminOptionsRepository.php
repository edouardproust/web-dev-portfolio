<?php

namespace App\Repository;

use App\Entity\AdminOptions;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method AdminOptions|null find($id, $lockMode = null, $lockVersion = null)
 * @method AdminOptions|null findOneBy(array $criteria, array $orderBy = null)
 * @method AdminOptions[]    findAll()
 * @method AdminOptions[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AdminOptionsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, AdminOptions::class);
    }

    // /**
    //  * @return AdminOptions[] Returns an array of AdminOptions objects
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
    public function findOneBySomeField($value): ?AdminOptions
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
