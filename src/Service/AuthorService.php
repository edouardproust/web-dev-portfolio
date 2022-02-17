<?php

namespace App\Service;

use App\Config;
use App\Entity\User;
use App\Entity\Author;
use Symfony\Component\Mime\Address;
use App\Repository\AuthorRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;

class AuthorService
{

    private $authorRepository;
    private $paginator;
    private $userService;
    private $entityManager;
    private $security;

    public function __construct(
        AuthorRepository $authorRepository,
        PaginatorInterface $paginator,
        UserService $userService,
        EntityManagerInterface $entityManager,
        MailerInterface $mailer,
        FlashBagInterface $flash,
        Security $security
    ) {
        $this->authorRepository = $authorRepository;
        $this->paginator = $paginator;
        $this->userService = $userService;
        $this->entityManager = $entityManager;
        $this->mailer = $mailer;
        $this->flash = $flash;
        $this->security = $security;
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
    public function buildAuthor(array $data, ?User $user = null): Author
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
        $user = $this->security->getUser();
        if (!$user) { // if is a new user
            $user = $this->userService->buildUser($data);
            $success = $this->userService->persistUser($user);
        }
        // author
        $author = $this->buildAuthor($data, $user);
        $this->entityManager->persist($author);
        $this->sendEmailNotif($author);
        return $success;
    }

    /**
     * Send a notification email to Admin on Author registration
     * @var Author $authorRequest Data from the Author Registration form, reformated into a Author object
     * @return void  
     */
    public function sendEmailNotif(Author $authorRequest)
    {
        $email = (new TemplatedEmail)
            ->to(new Address(Config::CONTACT_EMAIL, Config::CONTACT_NAME))
            ->from(new Address(Config::CONTACT_EMAIL, Config::CONTACT_NAME))
            ->subject('New Author registration on ' . Config::SITE_NAME)
            ->htmlTemplate('email/author_registration.html.twig')
            ->context([
                'authorRequest' => $authorRequest
            ]);
        $this->mailer->send($email);
    }
}
