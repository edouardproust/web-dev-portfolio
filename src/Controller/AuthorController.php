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
        $author = $this->authorRepository->findOneBy([
            'id' => $id
        ]);
        $projects = $this->paginator->paginate(
            $author->getProjects(),
            $request->query->getInt('page', 1),
            Config::POST_PER_PAGE
        );
        return $this->render('author/projects.html.twig', [
            'author' => $author,
            'projects' => $projects
        ]);
    }
}
