<?php

namespace App\Service;

use App\Entity\User;
use App\Entity\Author;
use App\Service\EmailService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Security;

class AuthorService
{
    private $userService;
    private $entityManager;
    private $security;
    private $emailService;

    public function __construct(
        UserService $userService,
        EntityManagerInterface $entityManager,
        Security $security,
        EmailService $emailService
    ) {
        $this->userService = $userService;
        $this->entityManager = $entityManager;
        $this->security = $security;
        $this->emailService = $emailService;
    }

    /**
     * @param Author $author Data of the form submitted by user
     * @param null|User $user An Author should alway be attached to a User account
     * @return Author The generated Author
     */
    public function buildAuthor(Author $author, ?User $user = null): Author
    {
        $user
            ->setIsAuthor(true)
            ->addRole('ROLE_AUTHOR');
        return $author
            ->setUser($user);
    }

    /**
     * Process all actions in order to persist an Author.
     * Note that this action does not flush it.
     * @param Author $data
     * @return bool
     */
    public function persistAuthor(Author $author, $request): bool
    {
        $author->email = $request->request->get('author_register')['email'];
        $author->password = $request->request->get('author_register')['password'];
        $success = true;
        // user
        $user = $this->security->getUser();
        if (!$user) { // if is a new user
            $user = $this->userService->buildUser($author);
            $success = $this->userService->persistUser($user);
        }
        // author
        $author = $this->buildAuthor($author, $user);
        $this->entityManager->persist($author);
        $this->emailService->sendEmailOnAuthorRegistration($author);
        return $success;
    }
}
