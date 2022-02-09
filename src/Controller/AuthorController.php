<?php

namespace App\Controller;

use App\Config;
use App\Repository\AuthorRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AuthorController extends AbstractController
{
    /** @var AuthorRepository */
    private $authorRepository;

    /** @var PaginatorInterface */
    private $paginator;

    public function __construct(
        AuthorRepository $authorRepository,
        PaginatorInterface $paginator
    ) {
        $this->authorRepository = $authorRepository;
        $this->paginator = $paginator;
    }

    /**
     * @Route("/projects/author/{id<\d+>}", name="author_projects")
     */
    public function projects($id, Request $request): Response
    {
        [$author, $projects] = $this->getCollection(
            $id,
            $request,
            'getProjects',
            COnfig::PROJECTS_PER_PAGE
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
        [$author, $lessons] = $this->getCollection(
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

    private function getCollection(
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
}
