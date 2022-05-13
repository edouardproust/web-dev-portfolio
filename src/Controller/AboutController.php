<?php

namespace App\Controller;

use App\Repository\ProjectRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AboutController extends AbstractController
{
    private $projectRepository;

    public function __construct(ProjectRepository $projectRepository)
    {
        $this->projectRepository = $projectRepository;
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
            )
        ]);
    }
}
