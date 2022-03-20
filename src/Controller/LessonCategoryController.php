<?php

namespace App\Controller;

use App\Repository\CodingLanguageRepository;
use App\Repository\LessonCategoryRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class LessonCategoryController extends AbstractController
{
    private $lessonCategoryRepository;
    private $codingLanguageRepository;

    public function __construct(
        LessonCategoryRepository $lessonCategoryRepository,
        CodingLanguageRepository $codingLanguageRepository
    ) {
        $this->lessonCategoryRepository = $lessonCategoryRepository;
        $this->codingLanguageRepository = $codingLanguageRepository;
    }

    /**
     * @Route("/lessons/category/{slug}", name="lesson_category")
     */
    public function index($slug): Response
    {
        $category = $this->lessonCategoryRepository->findOneBy([
            'slug' => $slug
        ]);
        $lessons = $category->getLessons();

        return $this->render('lesson_category/index.html.twig', [
            'category' => $category,
            'lessons' => $category->getLessons(),
            'codingLanguages' => $this->codingLanguageRepository->findNotEmpty($lessons)
        ]);
    }
}
