<?php

namespace App\Controller;

use App\Repository\ProjectRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProjectController extends AbstractController
{

    /** @var ProjectRepository */
    private $projectRepository;

    public function __construct(ProjectRepository $projectRepository)
    {
        
        $this->projectRepository = $projectRepository;
    }

    /**
     * @Route("/projects", name="projects")
     */
    public function index(): Response
    {
        return $this->render('project/index.html.twig', [
            'projects' => $this->projectRepository->findAll(),
        ]);
    }

    /**
     * @Route("/project/{slug}_{id}", name="project_show")
     */
    public function show($slug, $id): Response
    {
        return $this->render('project/show.html.twig', [
            'project' => $this->projectRepository->find($id),
        ]);
    }
}
