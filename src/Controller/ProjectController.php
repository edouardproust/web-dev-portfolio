<?php

namespace App\Controller;

use App\Config;
use App\Repository\ProjectRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ProjectController extends AbstractController
{

    /** @var ProjectRepository */
    private $projectRepository;

    /** @var PaginatorInterface */
    private $paginator;

    public function __construct(
        ProjectRepository $projectRepository,
        PaginatorInterface $paginator
    ) {
        $this->projectRepository = $projectRepository;
        $this->paginator = $paginator;
    }

    /**
     * @Route("/projects", name="projects")
     * @see https://github.com/KnpLabs/KnpPaginatorBundle
     */
    public function index(Request $request): Response
    {
        $projects = $this->paginator->paginate(
            $this->projectRepository->findAll(),
            $request->query->getInt('page', 1),
            Config::PROJECTS_PER_PAGE
        );

        return $this->render('project/index.html.twig', [
            'projects' => $projects,
        ]);
    }

    /**
     * @Route("/projects/{slug}_{id}", name="project_show")
     */
    public function show($slug, $id): Response
    {
        return $this->render('project/show.html.twig', [
            'project' => $this->projectRepository->find($id),
        ]);
    }
}
