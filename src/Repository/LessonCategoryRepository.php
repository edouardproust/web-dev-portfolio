<?php

namespace App\Repository;

use App\Entity\LessonCategory;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method LessonCategory|null find($id, $lockMode = null, $lockVersion = null)
 * @method LessonCategory|null findOneBy(array $criteria, array $orderBy = null)
 * @method LessonCategory[]    findAll()
 * @method LessonCategory[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class LessonCategoryRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, LessonCategory::class);
    }

    /**
     * Get list of all categories of a group of lessons
     * @var Collection|Lesson[] Lessons in collection
     * @return LessonCategory[] Returns an array of LessonCategory objects
     */
    public function findNotEmpty($lessons): array
    {
        $lessonsCategories = [];
        foreach ($lessons as $lesson) {
            foreach ($lesson->getCategories() as $category) {
                if (!empty($category->getLessons())) {
                    $lessonsCategories[$category->getSlug()] = $category;
                }
            }
        }
        return $lessonsCategories;
    }
}
