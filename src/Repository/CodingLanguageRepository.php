<?php

namespace App\Repository;

use App\Entity\CodingLanguage;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method CodingLanguage|null find($id, $lockMode = null, $lockVersion = null)
 * @method CodingLanguage|null findOneBy(array $criteria, array $orderBy = null)
 * @method CodingLanguage[]    findAll()
 * @method CodingLanguage[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CodingLanguageRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CodingLanguage::class);
    }

    // /**
    //  * @return CodingLanguage[] Returns an array of CodingLanguage objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('c.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?CodingLanguage
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
