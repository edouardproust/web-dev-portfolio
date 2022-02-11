<?php

namespace App\Controller;

use App\Config;
use App\Repository\PostRepository;
use App\Repository\LessonRepository;
use App\Repository\ProjectRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class HomeController extends AbstractController
{

    private $projectRepository;
    private $lessonRepository;
    private $postRepository;

    public function __construct(
        ProjectRepository $projectRepository,
        LessonRepository $lessonRepository,
        PostRepository $postRepository
    ) {
        $this->projectRepository = $projectRepository;
        $this->lessonRepository = $lessonRepository;
        $this->postRepository = $postRepository;
    }

    /**
     * @Route("/", name="home")
     */
    public function index(): Response
    {
        return $this->render('home/index.html.twig', [
            'projects' => $this->projectRepository->findLast(Config::HOME_PROJECTS),
            'lessons' => $this->lessonRepository->findLast(Config::HOME_LESSONS),
            'posts' => $this->postRepository->findLast(Config::HOME_POSTS),
        ]);
    }
}
