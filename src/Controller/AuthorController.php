<?php

namespace App\Controller;

use App\Repository\AuthorRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AuthorController extends AbstractController
{
    /** @var AuthorRepository */
    private $authorRepository;

    public function __construct(
        AuthorRepository $authorRepository
    ) {
        $this->authorRepository = $authorRepository;
    }

    /**
     * @Route("/projects/author/{id<\d+>}", name="author_projects")
     */
    public function projects($id): Response
    {
        return $this->render('author/projects.html.twig', [
            'author' => $this->authorRepository->findOneBy([
                'id' => $id
            ])
        ]);
    }
}
