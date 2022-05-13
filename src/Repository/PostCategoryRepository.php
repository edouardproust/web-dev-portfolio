<?php

namespace App\Repository;

use App\Entity\PostCategory;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method PostCategory|null find($id, $lockMode = null, $lockVersion = null)
 * @method PostCategory|null findOneBy(array $criteria, array $orderBy = null)
 * @method PostCategory[]    findAll()
 * @method PostCategory[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PostCategoryRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PostCategory::class);
    }

    /**
     * Get list of all categories of a group of posts
     * @var Collection|Post[] Posts in collection
     * @return PostCategory[] Returns an array of PostCategory objects
     */
    public function findNotEmpty($posts): array
    {
        $postsCategories = [];
        foreach ($posts as $post) {
            foreach ($post->getCategories() as $category) {
                if (!empty($category->getPosts())) {
                    $postsCategories[$category->getSlug()] = $category;
                }
            }
        }
        return $postsCategories;
    }
}
