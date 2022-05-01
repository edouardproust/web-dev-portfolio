<?php

namespace App\Controller;

use App\Repository\AdminOptionRepository;
use App\Service\HomeService;
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
    private $adminOptionRepository;
    private $homeService;

    public function __construct(
        ProjectRepository $projectRepository,
        LessonRepository $lessonRepository,
        PostRepository $postRepository,
        AdminOptionRepository $adminOptionRepository,
        HomeService $homeservice
    ) {
        $this->projectRepository = $projectRepository;
        $this->lessonRepository = $lessonRepository;
        $this->postRepository = $postRepository;
        $this->adminOptionRepository = $adminOptionRepository;
        $this->homeService = $homeservice;
    }

    /**
     * @Route("/", name="home")
     */
    public function index(): Response
    {
        $aor = $this->adminOptionRepository;
        $featuredProjects = $this->projectRepository->findFeatured(
            $aor->get('HOME_FEATURED_PROJECTS')->getValue()
        );

        return $this->render('home/index.html.twig', [
            'projects' => $this->projectRepository->findLast($aor->get('HOME_PROJECTS')->getValue()),
            'featuredProjects' => $this->homeService->prepareFeaturedProjects($featuredProjects),
            'lessons' => $this->lessonRepository->findLast($aor->get('HOME_LESSONS')->getValue()),
            'posts' => $this->postRepository->findLast($aor->get('HOME_PROJECTS')->getValue()),
            'stats' => $this->homeService->getStatistics()
        ]);
    }
}
