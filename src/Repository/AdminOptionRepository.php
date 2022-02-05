<?php

namespace App\Repository;

use App\Entity\AdminOption;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method AdminOption|null find($id, $lockMode = null, $lockVersion = null)
 * @method AdminOption|null findOneBy(array $criteria, array $orderBy = null)
 * @method AdminOption[]    findAll()
 * @method AdminOption[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AdminOptionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, AdminOption::class);
    }

    // /**
    //  * @return AdminOption[] Returns an array of AdminOption objects
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
    public function findOneBySomeField($value): ?AdminOption
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
