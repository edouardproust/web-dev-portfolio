<?php

namespace App\Controller;

use App\Repository\CodingLanguageRepository;
use App\Repository\ProjectCategoryRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CodingLanguageController extends AbstractController
{
    private $codingLanguageRepository;
    private $projectCategoryRepository;

    public function __construct(
        CodingLanguageRepository $codingLanguageRepository,
        ProjectCategoryRepository $projectCategoryRepository
    ) {
        $this->codingLanguageRepository = $codingLanguageRepository;
        $this->projectCategoryRepository = $projectCategoryRepository;
    }

    /**
     * @Route("/projects/language/{slug}", name="coding_language_projects")
     */
    public function projects($slug): Response
    {
        $codingLanguage = $this->codingLanguageRepository->findOneBy(['slug' => $slug]);
        $projects = $codingLanguage->getProjects();
        $filterCategories = $this->projectCategoryRepository->findNotEmpty($projects);

        return $this->render('coding_language/projects.html.twig', [
            'codingLanguage' => $codingLanguage,
            'projects' => $projects,
            'categories' => $filterCategories
        ]);
    }

    /**
     * @Route("/lessons/language/{slug}", name="coding_language_lessons")
     */
    public function lessons($slug): Response
    {
        $codingLanguage = $this->codingLanguageRepository->findOneBy(['slug' => $slug]);
        $lessons = $codingLanguage->getLessons();
        $filterCategories = $this->projectCategoryRepository->findNotEmpty($lessons);

        return $this->render('coding_language/lessons.html.twig', [
            'codingLanguage' => $codingLanguage,
            'lessons' => $lessons,
            'categories' => $filterCategories
        ]);
    }
}
