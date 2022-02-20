<?php

namespace App\Controller;

use App\Config;
use App\Service\AdminOptionService;
use Knp\Component\Pager\PaginatorInterface;
use App\Repository\LessonCategoryRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class LessonCategoryController extends AbstractController
{
    private $lessonCategoryRepository;
    private $paginator;
    private $adminOptionService;

    public function __construct(
        LessonCategoryRepository $lessonCategoryRepository,
        PaginatorInterface $paginator,
        AdminOptionService $adminOptionService
    ) {
        $this->lessonCategoryRepository = $lessonCategoryRepository;
        $this->paginator = $paginator;
        $this->adminOptionService = $adminOptionService;
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
            $this->adminOptionService->get('LESSONS_PER_PAGE')
        );

        return $this->render('lesson_category/index.html.twig', [
            'category' => $category,
            'lessons' => $lessons
        ]);
    }
}
