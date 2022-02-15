<?php

namespace App\EventListener;

use DateTime;
use App\Entity\User;
use App\Repository\AuthorRepository;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use EasyCorp\Bundle\EasyAdminBundle\Event\BeforeEntityPersistedEvent;

class EasyAdminSubscriber implements EventSubscriberInterface
{

    private $security;
    private $authorRepository;

    public function __construct(
        Security $security,
        AuthorRepository $authorRepository
    ) {
        $this->security = $security;
        $this->authorRepository = $authorRepository;
    }

    public static function getSubscribedEvents()
    {
        return [
            BeforeEntityPersistedEvent::class => [
                ['setCreatedAt'],
                ['setAuthor']
            ],
        ];
    }

    public function setCreatedAt(BeforeEntityPersistedEvent $event)
    {
        $entity = $event->getEntityInstance();
        if (property_exists($entity, 'createdAt')) {
            $entity->setCreatedAt(new DateTime());
        }
    }

    public function setAuthor(BeforeEntityPersistedEvent $event)
    {
        $entity = $event->getEntityInstance();

        if (property_exists($entity, 'author')) {
            /** @var User $user */
            $user = $this->security->getUser();
            $author = $this->authorRepository->findOneByUser($user);
            $entity->setAuthor($author);
        }
    }
}
