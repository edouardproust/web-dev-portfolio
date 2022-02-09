<?php

namespace App\Controller;

use App\Config;
use App\Repository\LessonRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class LessonController extends AbstractController
{

    public function __construct(
        LessonRepository $lessonRepository,
        PaginatorInterface $paginator
    ) {
        $this->lessonRepository = $lessonRepository;
        $this->paginator = $paginator;
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
            Config::LESSONS_PER_PAGE
        );

        return $this->render('lesson/index.html.twig', [
            'lessons' => $lessons,
        ]);
    }
    /**
     * @Route("/lessons/{slug}_{id}", name="lesson_show")
     */
    public function show(): Response
    {
        return new Response();
    }
}
