<?php

namespace App\Controller;

use App\Repository\ProjectCategoryRepository;
use App\Repository\ProjectRepository;
use App\Service\AdminOptionService;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ProjectController extends AbstractController
{
    private $projectRepository;
    private $paginator;
    private $adminOptionService;
    private $projectCategoryRepository;

    public function __construct(
        ProjectRepository $projectRepository,
        PaginatorInterface $paginator,
        AdminOptionService $adminOptionService,
        ProjectCategoryRepository $projectCategoryRepository
    ) {
        $this->projectRepository = $projectRepository;
        $this->paginator = $paginator;
        $this->adminOptionService = $adminOptionService;
        $this->projectCategoryRepository = $projectCategoryRepository;
    }

    /**
     * @Route("/portfolio", name="projects")
     * @see https://github.com/KnpLabs/KnpPaginatorBundle
     */
    public function index(Request $request): Response
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
        return $this->render('project/show.html.twig', [
            'project' => $this->projectRepository->find($id),
        ]);
    }
}
