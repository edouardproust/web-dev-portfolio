<?php

namespace App\Controller;

use App\Config;
use App\Service\AuthorService;
use App\Form\AuthorRegisterType;
use App\Service\UserService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AuthorController extends AbstractController
{
    private $authorService;
    private $userService;
    private $entityManager;

    public function __construct(
        AuthorService $authorService,
        UserService $userService,
        EntityManagerInterface $entityManager
    ) {
        $this->authorService = $authorService;
        $this->userService = $userService;
        $this->entityManager = $entityManager;
    }

    /**
     * @Route("/projects/author/{id<\d+>}", name="author_projects")
     */
    public function projects($id, Request $request): Response
    {
        [$author, $projects] = $this->authorService->getCollection(
            $id,
            $request,
            'getProjects',
            Config::PROJECTS_PER_PAGE
        );
        return $this->render('author/projects.html.twig', [
            'author' => $author,
            'projects' => $projects
        ]);
    }

    /**
     * @Route("/lessons/author/{id<\d+>}", name="author_lessons")
     */
    public function lessons($id, Request $request): Response
    {
        [$author, $lessons] = $this->authorService->getCollection(
            $id,
            $request,
            'getLessons',
            Config::LESSONS_PER_PAGE
        );
        return $this->render('author/lessons.html.twig', [
            'author' => $author,
            'lessons' => $lessons
        ]);
    }

    /**
     * @Route("/blog/author/{id<\d+>}", name="author_posts")
     */
    public function posts($id, Request $request): Response
    {
        [$author, $posts] = $this->authorService->getCollection(
            $id,
            $request,
            'getPosts',
            Config::POSTS_PER_PAGE
        );
        return $this->render('author/posts.html.twig', [
            'author' => $author,
            'posts' => $posts
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
