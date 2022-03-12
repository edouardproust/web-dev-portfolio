<?php

namespace App\Service;

use App\Entity\Post;
use App\Entity\Lesson;
use App\Entity\Project;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class PostTypeService
{
    private $urlGenerator;

    public function __construct(UrlGeneratorInterface $urlGenerator)
    {
        $this->urlGenerator = $urlGenerator;
    }

    /**
     * Get link data (url and title) for the 'navigation' section of a twig template
     * 
     * @param Post|Lesson|Project $entity The current entity (must be a post-type)
     * @param ServiceEntityRepository $repository Repository for this entity
     * @param string $routeName Route name for the link to generate. Eg. 'post_show'
     * @return array Array containing the Link data: 
     *   [
     *      'prev' => ['url' => string, 'title' => string],
     *      'next' => ['url' => string, 'title' => string]
     *   ]
     */
    public function getprevNextLinks(
        object $entity,
        ServiceEntityRepository $repository,
        string $routeName
    ): array {
        $navigationLinks = [];
        foreach (['prev', 'next'] as $prevNext) {
            $post = $this->getPreviousAndNextPosts($entity, $repository, $prevNext);
            $navigationLink = null;
            if ($post) {
                $navigationLink = [
                    'url' => $this->urlGenerator
                        ->generate($routeName, ['id' => $post->getId(), 'slug' => $post->getSlug()]),
                    'title' => $post->getTitle()
                ];
            }
            $navigationLinks[$prevNext] = $navigationLink;
        }
        return $navigationLinks;
    }

    /**
     * Returns previous or next Post, Lesson or Project (based on ids)
     * @param Post|Lesson|Project $entity The reference entity
     * @param string $prevNext 'prev' or 'next' depending on the link you want to get (previous or next one)
     * @return Post|Lesson|Project|null The previous or next entity
     * / null if first post (no previous one) or last post (no next one)
     * (depending on the $result var value: 'prev' or 'nex')
     */
    private function getPreviousAndNextPosts(
        object $entity,
        ServiceEntityRepository $repository,
        $prevNext = 'prev'
    ): ?object {
        $allPosts = $repository->findBy([], ['id' => 'ASC']);
        $nextPosts = $prevPosts = [];

        if ($prevNext === 'prev') {
            for ($i = count($allPosts) - 1; $i >= 0; $i--) {
                if ($allPosts[$i]->getId() < $entity->getId()) {
                    $prevPosts[] = $allPosts[$i];
                }
            }
            return $prevPosts[0] ?? null;
        } elseif ($prevNext === 'next') {
            for ($i = 0; $i < count($allPosts) - 1; $i++) {
                if ($allPosts[$i]->getId() > $entity->getId()) $nextPosts[] = $allPosts[$i];
            }
            return $nextPosts[0] ?? null;
        }
        return null;
    }
}
