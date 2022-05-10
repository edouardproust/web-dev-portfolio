<?php

namespace App\Controller;

use App\Service\PostTypeService;
use App\Repository\LessonRepository;
use App\Repository\CommentRepository;
use App\Repository\AdminOptionRepository;
use App\Repository\CodingLanguageRepository;
use App\Repository\LessonCategoryRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class LessonController extends AbstractController
{
    private $lessonRepository;
    private $lessonCategoryRepository;
    private $codingLanguageRepository;
    private $postTypeService;
    private $adminOptions;
    private $commentRepository;

    public function __construct(
        LessonRepository $lessonRepository,
        LessonCategoryRepository $lessonCategoryRepository,
        CodingLanguageRepository $codingLanguageRepository,
        PostTypeService $postTypeService,
        AdminOptionRepository $adminOptions,
        CommentRepository $commentRepository
    ) {
        $this->lessonRepository = $lessonRepository;
        $this->lessonCategoryRepository = $lessonCategoryRepository;
        $this->codingLanguageRepository = $codingLanguageRepository;
        $this->postTypeService = $postTypeService;
        $this->adminOptions = $adminOptions;
        $this->commentRepository = $commentRepository;
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
    public function show($slug, $id, Request $request): Response
    {
        $lesson = $this->lessonRepository->find($id);
        $prevNextLinks = $this->postTypeService
            ->getprevNextLinks($lesson, $this->lessonRepository, 'lesson_show');

        if ($this->adminOptions->get('SHOW_COMMENTS_ON_LESSON')) {
            $commentForm = $this->postTypeService->getCommentForm($request, $lesson);
            $visibleComments = $this->commentRepository->findIsVisibleBy('lesson', $lesson);
            if ($commentForm->isSubmitted() && $commentForm->isValid()) {
                return $this->redirect('#respond');
            }
        }

        return $this->render('lesson/show.html.twig', [
            'lesson' => $lesson,
            'prevNextLinks' => $prevNextLinks,
            'content' => $lesson->getContent(),
            'commentForm' => $commentForm ? $commentForm->createView(): null,
            'visibleComments' => $visibleComments ??  []
        ]);
    }
}
