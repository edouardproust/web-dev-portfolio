<?php

namespace App\Repository;

use App\Entity\Post;
use App\Entity\Lesson;
use App\Entity\Comment;
use App\Entity\Project;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

/**
 * @method Comment|null find($id, $lockMode = null, $lockVersion = null)
 * @method Comment|null findOneBy(array $criteria, array $orderBy = null)
 * @method Comment[]    findAll()
 * @method Comment[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CommentRepository extends ServiceEntityRepository
{
    const MAX_COMMENTS_PER_PAGE = 10;

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Comment::class);
    }

    /**
     * @param string $entityProperty 'post', 'lesson' or 'project'
     * @param Post|Lesson|Project $entity
     * @return Comment[] Returns an array of Comment objects
     */
    public function findIsVisibleBy(string $entityProperty, object $entity)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.' . $entityProperty . ' = :val')
            ->andWhere('c.isVisible = true')
            ->setParameter('val', $entity)
            ->orderBy('c.createdAt', 'DESC')
            ->setMaxResults(self::MAX_COMMENTS_PER_PAGE)
            ->getQuery()
            ->getResult()
        ;
    }

    /**
     * @param int $max Maximum number of comments
     * @return Comment[] Returns an array of Comment objects
     */
    public function findIsNotVisibleAll(int $max)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.isVisible = false')
            ->orderBy('c.createdAt', 'DESC')
            ->setMaxResults($max)
            ->getQuery()
            ->getResult()
        ;
    }
}
