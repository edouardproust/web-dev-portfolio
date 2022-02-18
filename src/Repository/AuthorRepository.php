<?php

namespace App\Repository;

use App\Entity\User;
use App\Entity\Author;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

/**
 * @method Author|null find($id, $lockMode = null, $lockVersion = null)
 * @method Author|null findOneBy(array $criteria, array $orderBy = null)
 * @method Author[]    findAll()
 * @method Author[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AuthorRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Author::class);
    }

    /**
     * @param User $user
     * @return Author  Returns an Author object
     */
    public function findOneByUser(User $user)
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.user = :val')
            ->setParameter('val', $user)
            ->getQuery()
            ->getOneOrNullResult();
    }

    /**
     * Get list of Authors registrations waiting for approval
     * @return Author[]
     */
    public function findIsNotApproved()
    {
        return $this->createQueryBuilder('a')
            ->where('a.isApproved = 0')
            ->orWhere('a.isApproved IS NULL')
            ->getQuery()
            ->getResult();
    }
}
