<?php

namespace App\Service;

use App\Entity\Post;
use App\Entity\Lesson;
use App\Entity\Comment;
use App\Entity\Project;
use App\Form\CommentType;
use App\Event\CommentSubmitEvent;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use App\EventListener\CommentSubmitSubscriber;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class PostTypeService extends AbstractController
{
    const COMMENT_SUBMIT_SUCCESS_EVENT = 'commentSubmitSuccess';

    private $urlGenerator;
    private $entityManager;
    private $flashBag;
    private $eventDispatcher;
    private $session;

    public function __construct(
        UrlGeneratorInterface $urlGenerator,
        EntityManagerInterface $entityManager,
        FlashBagInterface $flashBag,
        EventDispatcherInterface $eventDispatcher,
        SessionInterface $session
    ) {
        $this->urlGenerator = $urlGenerator;
        $this->entityManager = $entityManager;
        $this->flashBag = $flashBag;
        $this->eventDispatcher = $eventDispatcher;
        $this->session = $session;
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
     * @param array $order Default: ['createdAt' => 'DESC']
     * @return Post|Lesson|Project|null The previous or next entity
     * / null if first post (no previous one) or last post (no next one)
     * (depending on the $result var value: 'prev' or 'nex')
     */
    private function getPreviousAndNextPosts(
        object $entity,
        ServiceEntityRepository $repository,
        string $prevNext,
        array $order = ['createdAt' => 'DESC']
    ): ?object {
        $allPosts = $repository->findBy([], $order);
        foreach ($allPosts as $index => $post) {
            if ($entity === $post) {
                $entityIndex = $index;
            }
        }
        if ($prevNext === 'prev') {
            return $allPosts[$entityIndex - 1] ?? null;
        } elseif ($prevNext === 'next') {
            return $allPosts[$entityIndex + 1] ?? null;
        }
        return null;
    }

    public function getCommentForm(Request $request, object $entity)
    {
        $form = $this->createForm(CommentType::class, new Comment);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $comment = $form->getData();

            // Event hook
            $commentSubmitEvent = new CommentSubmitEvent($comment, $entity);
            $this->eventDispatcher->addSubscriber(new CommentSubmitSubscriber());
            $this->eventDispatcher->dispatch($commentSubmitEvent, CommentSubmitEvent::NAME);
            $this->entityManager->persist($comment);
            $this->entityManager->flush();
            $this->flashBag->add(self::COMMENT_SUBMIT_SUCCESS_EVENT, 'Your comment has been submitted to moderation.');
        }
        return $form;
    }
}
