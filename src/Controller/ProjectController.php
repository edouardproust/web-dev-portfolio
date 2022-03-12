<?php

namespace App\Controller;

use App\Repository\ProjectRepository;
use App\Repository\ProjectCategoryRepository;
use App\Service\PostTypeService;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ProjectController extends AbstractController
{
    private $projectRepository;
    private $projectCategoryRepository;
    private $postTypeService;

    public function __construct(
        ProjectRepository $projectRepository,
        ProjectCategoryRepository $projectCategoryRepository,
        PostTypeService $postTypeService
    ) {
        $this->projectRepository = $projectRepository;
        $this->projectCategoryRepository = $projectCategoryRepository;
        $this->postTypeService = $postTypeService;
    }

    /**
     * @Route("/portfolio", name="projects")
     */
    public function index(): Response
    {
        $projects = $this->projectRepository->findAll();
        return $this->render('project/index.html.twig', [
            'projects' => $projects,
            'categories' => $this->projectCategoryRepository->findNotEmpty()
        ]);
    }

    /**
     * @Route("/portfolio/{slug}_{id}", name="project_show")
     */
    public function show(int $id): Response
    {
        $project = $this->projectRepository->find($id);
        $prevNextLinks = $this->postTypeService
            ->getPrevNextLinks($project, $this->projectRepository, 'project_show');
        return $this->render('project/show.html.twig', [
            'project' => $project,
            'relatedProjects' => $this->projectRepository->findRelated($project),
            'prevNextLinks' => $prevNextLinks
        ]);
    }
}
