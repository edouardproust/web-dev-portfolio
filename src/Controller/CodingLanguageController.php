<?php

namespace App\Controller;

use App\Repository\CodingLanguageRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CodingLanguageController extends AbstractController
{
    private $codingLanguageRepository;

    public function __construct(CodingLanguageRepository $codingLanguageRepository)
    {
        $this->codingLanguageRepository = $codingLanguageRepository;
    }

    /**
     * @Route("/projects/language/{slug}", name="coding_language_projects")
     */
    public function projects($slug, Request $request): Response
    {
        $codingLanguage = $this->codingLanguageRepository->findOneBy([
            'slug' => $slug
        ]);
        return $this->render('coding_language/projects.html.twig', [
            'codingLanguage' => $codingLanguage,
            'projects' => $codingLanguage->getProjects()
        ]);
    }

    /**
     * @Route("/lessons/language/{slug}", name="coding_language_lessons")
     */
    public function lessons($slug): Response
    {
        $codingLanguage = $this->codingLanguageRepository->findOneBy([
            'slug' => $slug
        ]);
        return $this->render('coding_language/lessons.html.twig', [
            'codingLanguage' => $codingLanguage,
            'lessons' => $codingLanguage->getLessons()
        ]);
    }
}
