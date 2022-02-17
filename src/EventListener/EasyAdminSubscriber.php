<?php

namespace App\EventListener;

use App\Entity\Author;
use DateTime;
use App\Entity\User;
use App\Repository\AuthorRepository;
use App\Repository\UserRepository;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use EasyCorp\Bundle\EasyAdminBundle\Event\BeforeEntityDeletedEvent;
use EasyCorp\Bundle\EasyAdminBundle\Event\BeforeEntityUpdatedEvent;
use EasyCorp\Bundle\EasyAdminBundle\Event\BeforeEntityPersistedEvent;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class EasyAdminSubscriber implements EventSubscriberInterface
{
    private $security;
    private $authorRepository;
    private $userRepository;
    private $hasher;

    public function __construct(
        Security $security,
        AuthorRepository $authorRepository,
        UserRepository $userRepository,
        UserPasswordHasherInterface $hasher
    ) {
        $this->security = $security;
        $this->authorRepository = $authorRepository;
        $this->userRepository = $userRepository;
        $this->hasher = $hasher;
    }

    public static function getSubscribedEvents()
    {
        return [
            BeforeEntityPersistedEvent::class => [
                ['setCreatedAtOnEntityNew'],
                ['setAuthorOnPosttypeNew'],
                ['onAuthorNew'],
                ['setVisibleOnCommentNew'],
            ],
            BeforeEntityUpdatedEvent::class => [
                ['setUserOnAuthorEdit'],
                ['encodeUserPasswordOnUserEdit']
            ],
            BeforeEntityDeletedEvent::class => [
                'removeUserOnAuthorDelete'
            ]
        ];
    }

    /**
     * Set CreatedAt when creating a new entity ($entity->createdAt)
     * @param BeforeEntityPersistedEvent $event EasyAmdin Action::NEW
     * @return void
     */
    public function setCreatedAtOnEntityNew(BeforeEntityPersistedEvent $event)
    {
        $entity = $event->getEntityInstance();
        if (property_exists($entity, 'createdAt')) {
            $entity->setCreatedAt(new DateTime());
        }
    }

    /**
     * Set Author when creating a new Project, Lesson or Post ($posttype->author)
     * @param BeforeEntityPersistedEvent $event Fired on Action::NEW
     * @return void
     */
    public function setAuthorOnPosttypeNew(BeforeEntityPersistedEvent $event)
    {
        $posttype = $event->getEntityInstance();

        if (property_exists($posttype, 'author')) {
            /** @var User $user */
            $user = $this->security->getUser();
            $author = $this->authorRepository->findOneByUser($user);
            $posttype->setAuthor($author);
        }
    }

    /**
     * Misc actions to process when creating a new Author
     * @param BeforeEntityPersistedEvent $event Fired on Action::NEW
     * @return void
     */
    public function onAuthorNew(BeforeEntityPersistedEvent $event)
    {
        $author = $event->getEntityInstance();
        if ($author instanceof Author) {
            // user
            $user = $this->userRepository->find($author->getUser());
            $user->addRole('ROLE_AUTHOR');
            $user->setIsAuthor(true);
            // approve
            $author->setIsApproved(true);
        }
    }

    /**
     * Set visible on TRUE when creating a new comment ($entity->isVisible)
     * @param BeforeEntityPersistedEvent $event EasyAmdin Action::NEW
     * @return void
     */
    public function setVisibleOnCommentNew(BeforeEntityPersistedEvent $event)
    {
        $entity = $event->getEntityInstance();
        if (property_exists($entity, 'isVisible')) {
            $entity->setIsVisible(true);
        }
    }

    /**
     * Update User ($author->user) when editing an Author ($user->role, $user->isAuthor)
     * @param BeforeEntityUpdatedEvent $event Fired on Action::EDIT
     * @return void
     */
    public function setUserOnAuthorEdit(BeforeEntityUpdatedEvent $event)
    {
        $author = $event->getEntityInstance();
        if ($author instanceof Author) {
            $author->setUser($author->getUser()); // hidden 'user' field on Crud::EDIT
            $user = $this->userRepository->find($author->getUser());
            $user->addRole('ROLE_AUTHOR');
            $user->setIsAuthor(1);
        }
    }

    /**
     * Update User when editing a User ($user->password)
     * @param BeforeEntityUpdatedEvent $event Fired on Action::EDIT
     * @return void
     */
    public function encodeUserPasswordOnUserEdit(BeforeEntityUpdatedEvent $event)
    {
        $user = $event->getEntityInstance();
        if ($user instanceof User) {
            $user->setPassword(
                $this->hasher->hashPassword($user, $user->getPassword())
            );
        }
    }

    /**
     * Remove User ($author->user) when Deleting an Author ($user->role, $user->isAuthor)
     * @param BeforeEntityDeletedEvent $event Fired on Action::DELETE
     * @return void
     */
    public function removeUserOnAuthorDelete(BeforeEntityDeletedEvent $event)
    {
        $author = $event->getEntityInstance();
        if ($author instanceof Author) {
            $user = $this->userRepository->find($author->getUser());
            $user->removeRole('ROLE_AUTHOR');
            $user->setIsAuthor(null);
        }
    }
}
