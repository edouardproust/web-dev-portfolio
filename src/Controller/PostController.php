<?php

namespace App\Controller;

use App\Config;
use App\Repository\PostRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class PostController extends AbstractController
{

    /** @var PostRepository */
    private $postRepository;

    /** @var PaginatorInterface */
    private $paginator;

    public function __construct(
        PostRepository $postRepository,
        PaginatorInterface $paginator
    ) {
        $this->postRepository = $postRepository;
        $this->paginator = $paginator;
    }

    /**
     * @Route("/blog", name="posts")
     * @see https://github.com/KnpLabs/KnpPaginatorBundle
     */
    public function index(Request $request): Response
    {
        $posts = $this->paginator->paginate(
            $this->postRepository->findAll(),
            $request->query->getInt('page', 1),
            Config::POSTS_PER_PAGE
        );

        return $this->render('post/index.html.twig', [
            'posts' => $posts,
        ]);
    }

    /**
     * @Route("/blog/{slug}_{id}", name="post_show")
     */
    public function show($slug, $id): Response
    {
        return $this->render('post/show.html.twig', [
            'post' => $this->postRepository->find($id),
        ]);
    }
}
