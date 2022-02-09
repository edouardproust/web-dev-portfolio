<?php

namespace App\Controller;

use App\Config;
use Knp\Component\Pager\PaginatorInterface;
use App\Repository\ProjectCategoryRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ProjectCategoryController extends AbstractController
{

    /** @var ProjectCategoryRepository */
    private $projectCategoryRepository;

    /** @var PaginatorInterface */
    private $paginator;

    public function __construct(
        ProjectCategoryRepository $projectCategoryRepository,
        PaginatorInterface $paginator
    ) {
        $this->projectCategoryRepository = $projectCategoryRepository;
        $this->paginator = $paginator;
    }

    /**
     * @Route("/projects/category/{slug}", name="project_category")
     */
    public function index($slug, Request $request): Response
    {
        $category = $this->projectCategoryRepository->findOneBy([
            'slug' => $slug
        ]);
        $projects = $this->paginator->paginate(
            $category->getProjects(),
            $request->query->getInt('page', 1),
            Config::PROJECTS_PER_PAGE
        );

        return $this->render('project_category/index.html.twig', [
            'category' => $category,
            'projects' => $projects
        ]);
    }
}
