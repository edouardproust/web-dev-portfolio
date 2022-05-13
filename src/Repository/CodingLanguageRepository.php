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

    /**
     * Get list of all categories of a group of lessons or projects
     * @var Collection|Lesson[]|Project[] Lessons or Projects collection/array
     * @return PostCategory[] Returns an array of PostCategory objects
     */
    public function findNotEmpty($entities): array
    {
        $entitiesLanguages = [];
        foreach ($entities as $entity) {
            $language = $entity->getCodingLanguage();
            $entitiesLanguages[$language->getSlug()] = $language;
        }
        return $entitiesLanguages;
    }
}
