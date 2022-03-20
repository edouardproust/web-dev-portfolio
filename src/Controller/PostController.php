<?php

namespace App\Controller;

use App\Repository\PostCategoryRepository;
use App\Repository\PostRepository;
use App\Service\PostTypeService;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class PostController extends AbstractController
{
    private $postRepository;
    private $postCategoryRepository;
    private $postTypeService;

    public function __construct(
        PostRepository $postRepository,
        PostCategoryRepository $postCategoryRepository,
        PostTypeService $postTypeService
    ) {
        $this->postRepository = $postRepository;
        $this->postCategoryRepository = $postCategoryRepository;
        $this->postTypeService = $postTypeService;
    }

    /**
     * @Route("/blog", name="posts")
     */
    public function index(): Response
    {
        $posts = $this->postRepository->findBy([], ['createdAt' => 'DESC']);
        return $this->render('post/index.html.twig', [
            'posts' => $posts,
            'categories' => $this->postCategoryRepository->findNotEmpty($posts),
        ]);
    }

    /**
     * @Route("/blog/{slug}_{id}", name="post_show")
     */
    public function show(int $id): Response
    {
        $post = $this->postRepository->find($id);
        $prevNextLinks = $this->postTypeService
            ->getPrevNextLinks($post, $this->postRepository, 'post_show');

        return $this->render('post/show.html.twig', [
            'post' => $post,
            'prevNextLinks' => $prevNextLinks
        ]);
    }
}
