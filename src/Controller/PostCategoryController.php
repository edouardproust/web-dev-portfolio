<?php

namespace App\Controller;

use Knp\Component\Pager\PaginatorInterface;
use App\Repository\PostCategoryRepository;
use App\Service\AdminOptionService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class PostCategoryController extends AbstractController
{
    private $postCategoryRepository;
    private $paginator;
    private $adminOptionService;

    public function __construct(
        PostCategoryRepository $postCategoryRepository,
        PaginatorInterface $paginator,
        AdminOptionService $adminOptionService
    ) {
        $this->postCategoryRepository = $postCategoryRepository;
        $this->paginator = $paginator;
        $this->adminOptionService = $adminOptionService;
    }

    /**
     * @Route("/blog/category/{slug}", name="post_category")
     */
    public function index($slug, Request $request): Response
    {
        $category = $this->postCategoryRepository->findOneBy([
            'slug' => $slug
        ]);
        $posts = $this->paginator->paginate(
            $category->getPosts(),
            $request->query->getInt('page', 1),
            $this->adminOptionService->get('POSTS_PER_PAGE')
        );

        return $this->render('post_category/index.html.twig', [
            'category' => $category,
            'posts' => $posts
        ]);
    }
}
