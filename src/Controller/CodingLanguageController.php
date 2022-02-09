<?php

namespace App\Controller;

use App\Config;
use App\Repository\CodingLanguageRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CodingLanguageController extends AbstractController
{
    /** @var CodingLanguageRepository */
    private $codingLanguageRepository;

    /** @var PaginatorInterface */
    private $paginator;

    public function __construct(
        CodingLanguageRepository $codingLanguageRepository,
        PaginatorInterface $paginator
    ) {
        $this->codingLanguageRepository = $codingLanguageRepository;
        $this->paginator = $paginator;
    }

    /**
     * @Route("/projects/language/{slug}", name="coding_language_projects")
     */
    public function projects($slug, Request $request): Response
    {
        $codingLanguage = $this->codingLanguageRepository->findOneBy([
            'slug' => $slug
        ]);
        $projects = $this->paginator->paginate(
            $codingLanguage->getProjects(),
            $request->query->getInt('page', 1),
            Config::POST_PER_PAGE
        );
        return $this->render('coding_language/projects.html.twig', [
            'codingLanguage' => $codingLanguage,
            'projects' => $projects
        ]);
    }
}
