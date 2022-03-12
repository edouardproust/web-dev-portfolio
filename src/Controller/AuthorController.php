<?php

namespace App\Controller;

use App\Service\AuthorService;
use App\Form\AuthorRegisterType;
use App\Repository\AuthorRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AuthorController extends AbstractController
{
    private $authorService;
    private $authorRepository;
    private $entityManager;

    public function __construct(
        AuthorService $authorService,
        AuthorRepository $authorRepository,
        EntityManagerInterface $entityManager
    ) {
        $this->authorService = $authorService;
        $this->authorRepository = $authorRepository;
        $this->entityManager = $entityManager;
    }

    /**
     * @Route("/projects/author/{id<\d+>}", name="author_projects")
     */
    public function projects($id): Response
    {
        $author = $this->authorRepository->find($id);
        return $this->render('author/projects.html.twig', [
            'author' => $author,
            'projects' => $author->getProjects()
        ]);
    }

    /**
     * @Route("/lessons/author/{id<\d+>}", name="author_lessons")
     */
    public function lessons($id): Response
    {
        $author = $this->authorRepository->find($id);
        return $this->render('author/lessons.html.twig', [
            'author' => $author,
            'lessons' => $author->getLessons()
        ]);
    }

    /**
     * @Route("/blog/author/{id<\d+>}", name="author_posts")
     */
    public function posts($id): Response
    {
        $author = $this->authorRepository->find($id);
        return $this->render('author/posts.html.twig', [
            'author' => $author,
            'posts' => $author->getPosts()
        ]);
    }

    /**
     * @Route("/register/author", name="author_register")
     */
    public function register(Request $request): Response
    {
        $form = $this->createForm(AuthorRegisterType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $isPersisted = $this->authorService->persistAuthor($form->getData());
            if ($isPersisted) {
                $this->entityManager->flush();
                $this->addFlash('success', 'Your registration has been sent to the admin. 
                You will receive a confirmation email once your request is reviewed.');
                return $this->redirectToRoute('home');
            }
        }
        return $this->render('author/register.html.twig', [
            'registerForm' => $form->createView()
        ]);
    }
}
