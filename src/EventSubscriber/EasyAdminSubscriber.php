<?php

namespace App\EventSubscriber;

use DateTime;
use App\Entity\User;
use App\Entity\Author;
use App\Repository\UserRepository;
use App\Repository\AuthorRepository;
use App\Service\EmailService;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use EasyCorp\Bundle\EasyAdminBundle\Event\AfterEntityBuiltEvent;
use EasyCorp\Bundle\EasyAdminBundle\Event\BeforeEntityDeletedEvent;
use EasyCorp\Bundle\EasyAdminBundle\Event\BeforeEntityUpdatedEvent;
use EasyCorp\Bundle\EasyAdminBundle\Event\BeforeEntityPersistedEvent;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class EasyAdminSubscriber implements EventSubscriberInterface
{
    private $security;
    private $authorRepository;
    private $userRepository;
    private $hasher;
    private $emailService;
    private $flash;

    private $dataContainer = [];

    public function __construct(
        Security $security,
        AuthorRepository $authorRepository,
        UserRepository $userRepository,
        UserPasswordHasherInterface $hasher,
        EmailService $emailService,
        FlashBagInterface $flash
    ) {
        $this->security = $security;
        $this->authorRepository = $authorRepository;
        $this->userRepository = $userRepository;
        $this->hasher = $hasher;
        $this->emailService = $emailService;
        $this->flash = $flash;
    }

    public static function getSubscribedEvents()
    {
        return [
            AfterEntityBuiltEvent::class => 'onAfterEntityBuild',
            BeforeEntityPersistedEvent::class => 'onBeforeEntityPersisted',
            BeforeEntityUpdatedEvent::class => 'onBeforeEntityUpdated',
            BeforeEntityDeletedEvent::class => 'onBeforeEntityDeleted'
        ];
    }

    /**
     * @param AfterEntityBuiltEvent $event On page load (all Actions)
     * @return void
     */
    public function onAfterEntityBuild(AfterEntityBuiltEvent $event)
    {
        $entity = $event->getEntity();

        // Author entity and is defined (has an id)
        if ($entity->getFqcn() === Author::class && $entity->getInstance()) {
            // save data for later (onBeforeEntityUpdated)
            $this->dataContainer['isApproved'] = $entity->getInstance()->getIsApproved();
        }
    }

    /**
     * @param BeforeEntityPersistedEvent $event EasyAmdin Action::NEW
     * @return void
     */
    public function onBeforeEntityPersisted(BeforeEntityPersistedEvent $event)
    {
        $entity = $event->getEntityInstance();

        // All entities with 'createdAt' property
        if (property_exists($entity, 'createdAt')) {
            $entity->setCreatedAt(new DateTime());
        }

        // Project, Lesson or Post entities (have 'author' property)
        if (property_exists($entity, 'author')) {
            /** @var User $user */
            $user = $this->security->getUser();
            $author = $this->authorRepository->findOneByUser($user);
            $entity->setAuthor($author);
        }

        // Author entity
        if ($author instanceof Author) {
            // user
            $user = $this->userRepository->find($author->getUser());
            $user->addRole('ROLE_AUTHOR');
            $user->setIsAuthor(true);
            // approve
            $author->setIsApproved(true);
        }

        // Comment entity (has 'isVisible' property)
        if (property_exists($entity, 'isVisible')) {
            $entity->setIsVisible(true);
        }

        // flash
        $this->flash->add('success', 'New item created.');
    }

    /**
     * @param BeforeEntityUpdatedEvent $event Fired on Action::EDIT
     * @return void
     */
    public function onBeforeEntityUpdated(BeforeEntityUpdatedEvent $event)
    {
        $entity = $event->getEntityInstance();

        // All entities with 'updatedAt' property
        if (property_exists($entity, 'updatedAt')) {
            $entity->setUpdatedAt(new DateTime());
        }

        // Author entity
        if ($entity instanceof Author) {
            $entity->setUser($entity->getUser()); // hidden 'user' field on Crud::EDIT
            $user = $this->userRepository->find($entity->getUser());
            $user->addRole('ROLE_AUTHOR');
            $user->setIsAuthor(1);

            // isApproved
            $isApprovedBefore = $this->dataContainer['isApproved'] ? true : false;
            $isApproveAfter = $entity->getIsApproved() ? true : false;
            if ($isApprovedBefore !== $isApproveAfter) {
                if ($isApprovedBefore === false) {
                    $this->emailService->sendNotifOnAuthorApproval($entity);
                }
            }
        }

        // User entity
        if ($entity instanceof User) {
            $entity->setPassword(
                $this->hasher->hashPassword($entity, $entity->getPassword())
            );
        }

        // flash
        $this->flash->add('success', 'Item updated.');
    }

    /**
     * @param BeforeEntityDeletedEvent $event Fired on Action::DELETE
     * @return void
     */
    public function onBeforeEntityDeleted(BeforeEntityDeletedEvent $event)
    {
        $author = $event->getEntityInstance();
        if ($author instanceof Author) {
            $user = $this->userRepository->find($author->getUser());
            $user->removeRole('ROLE_AUTHOR');
            $user->setIsAuthor(null);
        }

        // flash
        $this->flash->add('success', 'Item deleted.');
    }
}
