<?php

namespace App\EventListener;

use App\Entity\Author;
use DateTime;
use App\Entity\User;
use App\Repository\AuthorRepository;
use App\Repository\UserRepository;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use EasyCorp\Bundle\EasyAdminBundle\Event\AbstractLifecycleEvent;
use EasyCorp\Bundle\EasyAdminBundle\Event\BeforeEntityDeletedEvent;
use EasyCorp\Bundle\EasyAdminBundle\Event\BeforeEntityUpdatedEvent;
use EasyCorp\Bundle\EasyAdminBundle\Event\BeforeEntityPersistedEvent;

class EasyAdminSubscriber implements EventSubscriberInterface
{

    private $security;
    private $authorRepository;

    public function __construct(
        Security $security,
        AuthorRepository $authorRepository,
        UserRepository $userRepository
    ) {
        $this->security = $security;
        $this->authorRepository = $authorRepository;
        $this->userRepository = $userRepository;
    }

    public static function getSubscribedEvents()
    {
        return [
            BeforeEntityPersistedEvent::class => [
                ['setCreatedAtOnEntityNew'],
                ['setAuthorOnPosttypeNew'],
                ['setUserOnAuthorNew']
            ],
            BeforeEntityUpdatedEvent::class => [
                'setUserOnAuthorEdit'
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
     * Set User when creating a new Author ($user->role, $user->isAuthor)
     * @param BeforeEntityPersistedEvent $event Fired on Action::NEW
     * @return void 
     */
    public function setUserOnAuthorNew(BeforeEntityPersistedEvent $event)
    {
        $author = $event->getEntityInstance();
        if ($author instanceof Author) {
            $user = $this->userRepository->find($author->getUser());
            $user->addRole('ROLE_AUTHOR');
            $user->setIsAuthor(true);
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
     * - Remove User ($author->user) when Deleting an Author ($user->role, $user->isAuthor)
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
