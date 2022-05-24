<?php

namespace App\Controller;

use App\Repository\ProjectRepository;
use App\Service\HomeService;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AboutController extends AbstractController
{
    private $projectRepository;
    private $homeService;

    public function __construct(ProjectRepository $projectRepository, HomeService $homeService)
    {
        $this->projectRepository = $projectRepository;
        $this->homeService = $homeService;
    }

    /**
     * @Route("/about", name="about")
     */
    public function index(): Response
    {
        return $this->render('about/index.html.twig', [
            'projects' => $this->projectRepository->findBy(
                ['featured' => true],
                ['createdAt' => 'DESC']
            ),
            'stats' => $this->homeService->getStatistics()
        ]);
    }
}
