<?php

namespace App\Controller;

use Knp\Component\Pager\PaginatorInterface;
use App\Repository\ProjectCategoryRepository;
use App\Service\AdminOptionService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ProjectCategoryController extends AbstractController
{
    private $projectCategoryRepository;
    private $paginator;
    private $adminOptionService;

    public function __construct(
        ProjectCategoryRepository $projectCategoryRepository,
        PaginatorInterface $paginator,
        AdminOptionService $adminOptionService
    ) {
        $this->projectCategoryRepository = $projectCategoryRepository;
        $this->paginator = $paginator;
        $this->adminOptionService = $adminOptionService;
    }

    /**
     * @Route("/portfolio/category/{slug}", name="project_category")
     */
    public function index($slug, Request $request): Response
    {
        $category = $this->projectCategoryRepository->findOneBy([
            'slug' => $slug
        ]);
        $projects = $this->paginator->paginate(
            $category->getProjects(),
            $request->query->getInt('page', 1),
            $this->adminOptionService->get('PROJECTS_PER_PAGE')
        );

        return $this->render('project_category/index.html.twig', [
            'category' => $category,
            'projects' => $projects
        ]);
    }
}
