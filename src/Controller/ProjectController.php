<?php

namespace App\Controller;

use App\Service\PostTypeService;
use App\Repository\CommentRepository;
use App\Repository\ProjectRepository;
use App\Repository\AdminOptionRepository;
use App\Repository\ProjectCategoryRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ProjectController extends AbstractController
{
    private $projectRepository;
    private $projectCategoryRepository;
    private $postTypeService;
    private $adminOptions;
    private $commentRepository;

    public function __construct(
        ProjectRepository $projectRepository,
        ProjectCategoryRepository $projectCategoryRepository,
        PostTypeService $postTypeService,
        AdminOptionRepository $adminOptions,
        CommentRepository $commentRepository
    ) {
        $this->projectRepository = $projectRepository;
        $this->projectCategoryRepository = $projectCategoryRepository;
        $this->postTypeService = $postTypeService;
        $this->adminOptions = $adminOptions;
        $this->commentRepository = $commentRepository;
    }

    /**
     * @Route("/portfolio", name="projects")
     */
    public function index(): Response
    {
        $projects = $this->projectRepository->findBy([], ['createdAt' => 'DESC']);

        return $this->render('project/index.html.twig', [
            'projects' => $projects,
            'categories' => $this->projectCategoryRepository->findNotEmpty($projects),
            'featuredProjects' => $this->projectRepository->findBy(
                ['featured' => true],
                ['createdAt' => 'DESC']
            )
        ]);
    }

    /**
     * @Route("/portfolio/{slug}_{id}", name="project_show")
     */
    public function show($slug, $id, Request $request): Response
    {
        $project = $this->projectRepository->find($id);
        $prevNextLinks = $this->postTypeService
            ->getPrevNextLinks($project, $this->projectRepository, 'project_show');

        if ($this->adminOptions->get('SHOW_COMMENTS_ON_PROJECT')) {
            $commentForm = $this->postTypeService->getCommentForm($request, $project);
            $visibleComments = $this->commentRepository->findIsVisibleBy('project', $project);
            if ($commentForm->isSubmitted() && $commentForm->isValid()) {
                return $this->redirect('#respond');
            }
        }

        return $this->render('project/show.html.twig', [
            'project' => $project,
            'relatedProjects' => $this->projectRepository->findRelated($project),
            'prevNextLinks' => $prevNextLinks,
            'gallery' => $project->getGallery(),
            'commentForm' => $commentForm ? $commentForm->createView(): null,
            'visibleComments' => $visibleComments ?? []
        ]);
    }
}
