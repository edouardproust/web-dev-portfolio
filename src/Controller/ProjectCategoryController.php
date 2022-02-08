<?php

namespace App\Controller;

use App\Repository\ProjectCategoryRepository;
use App\Repository\ProjectRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ProjectCategoryController extends AbstractController
{

    /** @var ProjectCategoryRepository */
    private $projectCategoryRepository;

    public function __construct(
        ProjectCategoryRepository $projectCategoryRepository
    ) {
        $this->projectCategoryRepository = $projectCategoryRepository;
    }

    /**
     * @Route("/projects/category/{slug}", name="project_category")
     */ 
    public function index($slug): Response
    {
        return $this->render('project_category/index.html.twig', [
            'category' => $this->projectCategoryRepository->findOneBy([
                'slug' => $slug
            ])
        ]);
    }
}
