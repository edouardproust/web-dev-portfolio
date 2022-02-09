<?php

namespace App\Controller;

use App\Config;
use Knp\Component\Pager\PaginatorInterface;
use App\Repository\LessonCategoryRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class LessonCategoryController extends AbstractController
{
    public function __construct(
        LessonCategoryRepository $lessonCategoryRepository,
        PaginatorInterface $paginator
    ) {
        $this->lessonCategoryRepository = $lessonCategoryRepository;
        $this->paginator = $paginator;
    }

    /**
     * @Route("/lessons/category/{slug}", name="lesson_category")
     */
    public function index($slug, Request $request): Response
    {
        $category = $this->lessonCategoryRepository->findOneBy([
            'slug' => $slug
        ]);
        $lessons = $this->paginator->paginate(
            $category->getLessons(),
            $request->query->getInt('page', 1),
            Config::LESSONS_PER_PAGE
        );

        return $this->render('lesson_category/index.html.twig', [
            'category' => $category,
            'lessons' => $lessons
        ]);
    }
}
