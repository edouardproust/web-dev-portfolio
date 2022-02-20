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

    /**
     * @param string $constant 'constant' field of the admin_option table in database'
     * @return AdminOption Returns an array of AdminOption objects
     */
    public function findOneByConstant(string $constant): ?AdminOption
    {
        return $this->createQueryBuilder('ao')
            ->andWhere('ao.constant = :val')
            ->setParameter('val', $constant)
            ->getQuery()
            ->getOneOrNullResult();
    }
}
