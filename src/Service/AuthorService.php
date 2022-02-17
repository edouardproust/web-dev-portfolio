<?php

namespace App\Service;

use App\Entity\User;
use App\Entity\Author;
use App\Repository\AuthorRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;

class AuthorService
{

    private $authorRepository;
    private $paginator;
    private $userService;
    private $entityManager;

    public function __construct(
        AuthorRepository $authorRepository,
        PaginatorInterface $paginator,
        UserService $userService,
        EntityManagerInterface $entityManager
    ) {
        $this->authorRepository = $authorRepository;
        $this->paginator = $paginator;
        $this->userService = $userService;
        $this->entityManager = $entityManager;
    }

    public function getCollection(
        int $id,
        Request $request,
        string $getterFn,
        int $limit
    ): array {
        $author = $this->authorRepository->findOneBy([
            'id' => $id
        ]);
        $entities = $this->paginator->paginate(
            $author->$getterFn(),
            $request->query->getInt('page', 1),
            $limit
        );
        return [$author, $entities];
    }

    /**
     * @param array $data Data of the form submitted by user
     * @param null|User $user An Author should alway be attached to a User account
     * @return Author The generated Author
     */
    public function setAuthor(array $data, ?User $user = null): Author
    {
        $user
            ->setIsAuthor(true)
            ->addRole('ROLE_AUTHOR');
        return (new Author)
            ->setUser($user)
            ->setFullName($data['fullName'])
            ->setBio($data['bio'])
            ->setAvatar($data['avatar'])
            ->setContactEmail($data['contactEmail'])
            ->setWebsite($data['website'])
            ->setGithub($data['github'])
            ->setLinkedin($data['linkedin'])
            ->setStackoverflow($data['stackoverflow']);
    }

    /**
     * Process all actions in order to persist an Author.
     * Note that this action does not flush it.
     * @param array $data 
     * @return bool 
     */
    public function persistAuthor(array $data): bool
    {
        $success = true;

        // user
        $user = $this->userService->setUser($data);
        $success = $this->userService->persistUser($user);
        // author
        $author = $this->setAuthor($data, $user);
        $this->entityManager->persist($author);

        return $success;
    }
}
