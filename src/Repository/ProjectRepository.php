<?php

namespace App\Repository;

use App\Entity\Author;
use App\Entity\Project;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

/**
 * @method Project|null find($id, $lockMode = null, $lockVersion = null)
 * @method Project|null findOneBy(array $criteria, array $orderBy = null)
 * @method Project[]    findAll()
 * @method Project[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProjectRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Project::class);
    }

    /**
     * @return Project[] Returns an array of Project objects
     */
    public function findLast($limit)
    {
        return $this->createQueryBuilder('p')
            ->orderBy('p.createdAt', 'DESC')
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult();
    }

    /**
     * @return Project[] Returns an array of Project objects
     */
    public function findFeatured($limit)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.featured = 1')
            ->orderBy('p.createdAt', 'DESC')
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult();
    }

    /**
     * Get "Related Projects" = projects with at least 1 category in common
     * @param Project $project 
     * @return void 
     */
    public function findRelated(Project $project)
    {
        $projectCategories = [];
        $relatedProjects = [];

        // get project categories
        $categories = $project->getCategories();
        foreach ($categories as $category) {
            $projectCategories[] = $category;
        }
        // get related projects
        foreach ($this->findAll() as $p) {
            $categories = $p->getCategories();
            foreach ($categories as $c) {
                if (in_array($c, $projectCategories)) {
                    $relatedProjects[] = $p;
                }
            }
        }
        return $relatedProjects;
    }
}
