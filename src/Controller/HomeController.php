<?php

namespace App\Controller;

use App\Repository\PostRepository;
use App\Repository\LessonRepository;
use App\Repository\ProjectRepository;
use App\Service\AdminOptionService;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class HomeController extends AbstractController
{
    private $projectRepository;
    private $lessonRepository;
    private $postRepository;
    private $adminOptionService;

    public function __construct(
        ProjectRepository $projectRepository,
        LessonRepository $lessonRepository,
        PostRepository $postRepository,
        AdminOptionService $adminOptionService
    ) {
        $this->projectRepository = $projectRepository;
        $this->lessonRepository = $lessonRepository;
        $this->postRepository = $postRepository;
        $this->adminOptionService = $adminOptionService;
    }

    /**
     * @Route("/", name="home")
     */
    public function index(): Response
    {
        $ao = $this->adminOptionService;
        return $this->render('home/index.html.twig', [
            'projects' => $this->projectRepository->findLast($ao->get('HOME_PROJECTS')),
            'featuredProjects' => $this->projectRepository->findFeatured($ao->get('HOME_FEATURED_PROJECTS')),
            'lessons' => $this->lessonRepository->findLast($ao->get('HOME_LESSONS')),
            'posts' => $this->postRepository->findLast($ao->get('HOME_PROJECTS')),
        ]);
    }
}
