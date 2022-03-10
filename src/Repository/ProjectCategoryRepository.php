<?php

namespace App\Repository;

use App\Entity\ProjectCategory;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method ProjectCategory|null find($id, $lockMode = null, $lockVersion = null)
 * @method ProjectCategory|null findOneBy(array $criteria, array $orderBy = null)
 * @method ProjectCategory[]    findAll()
 * @method ProjectCategory[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProjectCategoryRepository extends ServiceEntityRepository
{

    private $projectRepository;

    public function __construct(ManagerRegistry $registry, ProjectRepository $projectRepository)
    {
        parent::__construct($registry, ProjectCategory::class);
        $this->projectRepository = $projectRepository;
    }

    /**
     * Get list of all categories that contain projects
     * @return ProjectCategory[] Returns an array of ProjectCategory objects
     */
    public function findNotEmpty(): array
    {
        $projectCategories = $this->findAll();

        $notEmptyCategories = [];
        foreach ($projectCategories as $category) {
            foreach ($category->getProjects() as $project) {
                dump($category->getLabel() . ': ' . $project->getTitle());
            }
            if (!empty($category->getProjects())) {
                $notEmptyCategories[] = $category;
            }
        }
        return $notEmptyCategories;
    }

    /*
    public function findOneBySomeField($value): ?ProjectCategory
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
