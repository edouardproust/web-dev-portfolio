<?php

namespace App\Controller;

use App\Repository\ProjectCategoryRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ProjectCategoryController extends AbstractController
{
    private $projectCategoryRepository;

    public function __construct(ProjectCategoryRepository $projectCategoryRepository)
    {
        $this->projectCategoryRepository = $projectCategoryRepository;
    }

    /**
     * @Route("/portfolio/category/{slug}", name="project_category")
     */
    public function index($slug): Response
    {
        $category = $this->projectCategoryRepository->findOneBy([
            'slug' => $slug
        ]);

        return $this->render('project_category/index.html.twig', [
            'category' => $category,
            'projects' => $category->getProjects()
        ]);
    }
}
