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
    private $codingLanguageRepository;
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
        [$codingLanguage, $projects] = $this->getCollection(
            $slug,
            $request,
            'getProjects',
            Config::PROJECTS_PER_PAGE
        );
        return $this->render('coding_language/projects.html.twig', [
            'codingLanguage' => $codingLanguage,
            'projects' => $projects
        ]);
    }

    /**
     * @Route("/lessons/language/{slug}", name="coding_language_lessons")
     */
    public function lessons($slug, Request $request): Response
    {
        [$codingLanguage, $lessons] = $this->getCollection(
            $slug,
            $request,
            'getLessons',
            Config::LESSONS_PER_PAGE
        );
        return $this->render('coding_language/lessons.html.twig', [
            'codingLanguage' => $codingLanguage,
            'lessons' => $lessons
        ]);
    }

    private function getCollection(
        string $slug,
        Request $request,
        string $getterFn,
        int $limit
    ): array {
        $codingLanguage = $this->codingLanguageRepository->findOneBy([
            'slug' => $slug
        ]);
        $entities = $this->paginator->paginate(
            $codingLanguage->$getterFn(),
            $request->query->getInt('page', 1),
            $limit
        );
        return [$codingLanguage, $entities];
    }
}
