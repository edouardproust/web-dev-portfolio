<?php

namespace App\Controller;

use App\Repository\PostCategoryRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class PostCategoryController extends AbstractController
{
    private $postCategoryRepository;

    public function __construct(PostCategoryRepository $postCategoryRepository)
    {
        $this->postCategoryRepository = $postCategoryRepository;
    }

    /**
     * @Route("/blog/category/{slug}", name="post_category")
     */
    public function index($slug): Response
    {
        $category = $this->postCategoryRepository->findOneBy([
            'slug' => $slug
        ]);

        return $this->render('post_category/index.html.twig', [
            'category' => $category,
            'posts' => $category->getPosts()
        ]);
    }
}
