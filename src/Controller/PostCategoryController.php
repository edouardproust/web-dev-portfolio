<?php

namespace App\Controller;

use App\Config;
use Knp\Component\Pager\PaginatorInterface;
use App\Repository\PostCategoryRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class PostCategoryController extends AbstractController
{

    /** @var PostCategoryRepository */
    private $postCategoryRepository;

    /** @var PaginatorInterface */
    private $paginator;

    public function __construct(
        PostCategoryRepository $postCategoryRepository,
        PaginatorInterface $paginator
    ) {
        $this->postCategoryRepository = $postCategoryRepository;
        $this->paginator = $paginator;
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
            Config::POSTS_PER_PAGE
        );

        return $this->render('post_category/index.html.twig', [
            'category' => $category,
            'posts' => $posts
        ]);
    }
}
