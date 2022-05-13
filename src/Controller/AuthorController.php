<?php

namespace App\Controller;

use App\Entity\Author;
use App\Service\AuthorService;
use App\Form\AuthorRegisterType;
use App\Repository\AuthorRepository;
use App\Repository\CodingLanguageRepository;
use App\Repository\LessonCategoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\PostCategoryRepository;
use App\Repository\ProjectCategoryRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AuthorController extends AbstractController
{
    private $authorService;
    private $authorRepository;
    private $entityManager;
    private $postCategoryRepository;
    private $projectCategoryRepository;
    private $lessonCategoryRepository;
    private $codingLanguageRepository;

    public function __construct(
        AuthorService $authorService,
        AuthorRepository $authorRepository,
        EntityManagerInterface $entityManager,
        PostCategoryRepository $postCategoryRepository,
        ProjectCategoryRepository $projectCategoryRepository,
        LessonCategoryRepository $lessonCategoryRepository,
        CodingLanguageRepository $codingLanguageRepository
    ) {
        $this->authorService = $authorService;
        $this->authorRepository = $authorRepository;
        $this->entityManager = $entityManager;
        $this->postCategoryRepository = $postCategoryRepository;
        $this->projectCategoryRepository = $projectCategoryRepository;
        $this->lessonCategoryRepository = $lessonCategoryRepository;
        $this->codingLanguageRepository = $codingLanguageRepository;
    }

    /**
     * @Route("/projects/author/{id<\d+>}", name="author_projects")
     */
    public function projects($id): Response
    {
        $author = $this->authorRepository->find($id);
        return $this->render('author/projects.html.twig', [
            'author' => $author,
            'projects' => $author->getProjects(),
            'categories' => $this->projectCategoryRepository->findNotEmpty($author->getProjects())
        ]);
    }

    /**
     * @Route("/lessons/author/{id<\d+>}", name="author_lessons")
     */
    public function lessons($id): Response
    {
        $author = $this->authorRepository->find($id);
        $lessons = $author->getLessons();

        return $this->render('author/lessons.html.twig', [
            'author' => $author,
            'lessons' => $lessons,
            'categories' => $this->lessonCategoryRepository->findNotEmpty($lessons),
            'codingLanguages' => $this->codingLanguageRepository->findNotEmpty($lessons)
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
            'posts' => $author->getPosts(),
            'categories' => $this->postCategoryRepository->findNotEmpty($author->getPosts())
        ]);
    }

    /**
     * @Route("/register/author", name="author_register")
     */
    public function register(Request $request): Response
    {
        $form = $this->createForm(AuthorRegisterType::class, new Author);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $isPersisted = $this->authorService->persistAuthor($form->getData(), $request);
            if ($isPersisted) {
                $this->entityManager->flush();
                $this->addFlash(
                    'success',
                    'Your registration has been sent to the admin.' .
                        ' You will receive a confirmation email once your request is reviewed.' .
                        ' For now, you can login as a simple user below using the credentials you just provided, or <a href="/">go to homepage</a>.'
                );
                return $this->redirectToRoute('app_login');
            }
        }
        return $this->render('author/register.html.twig', [
            'registerForm' => $form->createView()
        ]);
    }
}
