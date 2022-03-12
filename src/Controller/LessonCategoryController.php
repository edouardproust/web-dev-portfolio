<?php

namespace App\Controller;

use App\Repository\LessonCategoryRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class LessonCategoryController extends AbstractController
{
    private $lessonCategoryRepository;

    public function __construct(LessonCategoryRepository $lessonCategoryRepository)
    {
        $this->lessonCategoryRepository = $lessonCategoryRepository;
    }

    /**
     * @Route("/lessons/category/{slug}", name="lesson_category")
     */
    public function index($slug): Response
    {
        $category = $this->lessonCategoryRepository->findOneBy([
            'slug' => $slug
        ]);

        return $this->render('lesson_category/index.html.twig', [
            'category' => $category,
            'lessons' => $category->getLessons()
        ]);
    }
}
