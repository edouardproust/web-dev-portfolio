<?php

namespace App\Controller;

use App\Repository\LessonRepository;
use App\Service\AdminOptionService;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class LessonController extends AbstractController
{
    private $lessonRepository;
    private $paginator;
    private $adminOptionService;

    public function __construct(
        LessonRepository $lessonRepository,
        PaginatorInterface $paginator,
        AdminOptionService $adminOptionService
    ) {
        $this->lessonRepository = $lessonRepository;
        $this->paginator = $paginator;
        $this->adminOptionService = $adminOptionService;
    }

    /**
     * @Route("/lessons", name="lessons")
     * @see https://github.com/KnpLabs/KnpPaginatorBundle
     */
    public function index(Request $request): Response
    {
        $lessons = $this->paginator->paginate(
            $this->lessonRepository->findAll(),
            $request->query->getInt('page', 1),
            $this->adminOptionService->get('LESSONS_PER_PAGE')
        );

        return $this->render('lesson/index.html.twig', [
            'lessons' => $lessons,
        ]);
    }
    /**
     * @Route("/lessons/{slug}_{id}", name="lesson_show")
     */
    public function show(int $id): Response
    {
        return $this->render('lesson/show.html.twig', [
            'lesson' => $this->lessonRepository->find($id),
        ]);
    }
}
