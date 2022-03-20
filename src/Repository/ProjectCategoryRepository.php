<?php

namespace App\Repository;

use App\Entity\ProjectCategory;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Common\Collections\Collection;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

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
     * Get list of all categories of a group of projects
     * @var Collection|Project[] Projects in collection
     * @return ProjectCategory[] Returns an array of ProjectCategory objects
     */
    public function findNotEmpty($projects): array
    {
        $projectsCategories = [];
        foreach ($projects as $project) {
            foreach ($project->getCategories() as $category) {
                $projectsCategories[$category->getSlug()] = $category;
            }
        }
        return $projectsCategories;
    }
}
