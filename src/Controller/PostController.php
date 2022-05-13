<?php

namespace App\Controller;

use App\Repository\AdminOptionRepository;
use App\Repository\CommentRepository;
use App\Service\PostTypeService;
use App\Repository\PostRepository;
use App\Repository\PostCategoryRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class PostController extends AbstractController
{
    private $postRepository;
    private $postCategoryRepository;
    private $postTypeService;
    private $adminOptions;
    private $commentRepository;

    public function __construct(
        PostRepository $postRepository,
        PostCategoryRepository $postCategoryRepository,
        PostTypeService $postTypeService,
        AdminOptionRepository $adminOptions,
        CommentRepository $commentRepository
    ) {
        $this->postRepository = $postRepository;
        $this->postCategoryRepository = $postCategoryRepository;
        $this->postTypeService = $postTypeService;
        $this->adminOptions = $adminOptions;
        $this->commentRepository = $commentRepository;
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
    public function show(string $slug, int $id, Request $request): Response
    {
        $post = $this->postRepository->find($id);
        $prevNextLinks = $this->postTypeService
            ->getPrevNextLinks($post, $this->postRepository, 'post_show', ['createdAt' => 'DESC']);

        if ($this->adminOptions->get('SHOW_COMMENTS_ON_POST')) {
            $commentForm = $this->postTypeService->getCommentForm($request, $post);
            $visibleComments = $this->commentRepository->findIsVisibleBy('post', $post);
            if ($commentForm->isSubmitted() && $commentForm->isValid()) {
                return $this->redirect('#respond');
            }
        }


        return $this->render('post/show.html.twig', [
            'post' => $post,
            'prevNextLinks' => $prevNextLinks,
            'commentForm' => $commentForm ? $commentForm->createView(): null,
            'visibleComments' => $visibleComments ?? []
        ]);
    }
}
