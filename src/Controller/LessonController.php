<?php

namespace App\Controller;

use App\Repository\CodingLanguageRepository;
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
    private $codingLanguageRepository;
    private $postTypeService;

    public function __construct(
        LessonRepository $lessonRepository,
        LessonCategoryRepository $lessonCategoryRepository,
        CodingLanguageRepository $codingLanguageRepository,
        PostTypeService $postTypeService
    ) {
        $this->lessonRepository = $lessonRepository;
        $this->lessonCategoryRepository = $lessonCategoryRepository;
        $this->codingLanguageRepository = $codingLanguageRepository;
        $this->postTypeService = $postTypeService;
    }

    /**
     * @Route("/lessons", name="lessons")
     */
    public function index(): Response
    {
        $lessons = $this->lessonRepository->findBy([], ['createdAt' => 'DESC']);

        return $this->render('lesson/index.html.twig', [
            'lessons' => $lessons,
            'codingLanguagesFilter' => $this->codingLanguageRepository->findNotEmpty($lessons),
            'categoriesFilter' => $this->lessonCategoryRepository->findNotEmpty($lessons)
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
            'prevNextLinks' => $prevNextLinks,
            'content' => $lesson->getContent()
        ]);
    }
}
