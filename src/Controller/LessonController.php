<?php

namespace App\Controller;

use App\Repository\LessonCategoryRepository;
use App\Repository\LessonRepository;
use App\Service\PostTypeService;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class LessonController extends AbstractController
{
    private $lessonRepository;
    private $lessonCategoryRepository;
    private $postTypeService;

    public function __construct(
        LessonRepository $lessonRepository,
        LessonCategoryRepository $lessonCategoryRepository,
        PostTypeService $postTypeService
    ) {
        $this->lessonRepository = $lessonRepository;
        $this->lessonCategoryRepository = $lessonCategoryRepository;
        $this->postTypeService = $postTypeService;
    }

    /**
     * @Route("/lessons", name="lessons")
     */
    public function index(): Response
    {
        $lessons = $this->lessonRepository->findAll();

        return $this->render('lesson/index.html.twig', [
            'lessons' => $lessons,
            'categories' => $this->lessonCategoryRepository->findNotEmpty()
        ]);
    }
    /**
     * @Route("/lessons/{slug}_{id}", name="lesson_show")
     */
    public function show(int $id): Response
    {
        $lesson = $this->lessonRepository->find($id);
        $prevNextLinks = $this->postTypeService
            ->getprevNextLinks($lesson, $this->lessonRepository, 'lesson_show');

        return $this->render('lesson/show.html.twig', [
            'lesson' => $lesson,
            'prevNextLinks' => $prevNextLinks
        ]);
    }
}
