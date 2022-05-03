<?php

namespace App\Controller\Admin;

use App\Config;
use App\Entity\Post;
use App\Entity\Tool;
use App\Entity\User;
use App\Entity\Author;
use App\Entity\Lesson;
use App\Entity\Comment;
use App\Entity\Project;
use App\Entity\Technology;
use App\Entity\AdminOption;
use App\Entity\PostCategory;
use App\Service\UserService;
use App\Entity\CodingLanguage;
use App\Entity\LessonCategory;
use App\Entity\ProjectCategory;
use App\Repository\AuthorRepository;
use App\Helper\CKFinderAuthenticator;
use App\Repository\AdminOptionRepository;
use App\Controller\Admin\AuthorCrudController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Config\UserMenu;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use Symfony\Component\Security\Core\User\UserInterface;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;

class DashboardController extends AbstractDashboardController
{
    private $authorRepository;
    private $adminUrlGenerator;
    private $adminOptionRepository;
    private $userService;

    public function __construct(
        AuthorRepository $authorRepository,
        AdminUrlGenerator $adminUrlGenerator,
        AdminOptionRepository $adminOptionRepository,
        UserService $userService
    ) {
        $this->authorRepository = $authorRepository;
        $this->adminUrlGenerator = $adminUrlGenerator;
        $this->adminOptionRepository = $adminOptionRepository;
        $this->userService = $userService;
    }

    /**
     * @Route("/admin", name="admin")
     */
    public function index(): Response
    {
        // Cards
        $highestRole = $this->userService->getHighestRole($this->getUser());
        $cards = [];
        if ($highestRole === Config::ROLE_ADMIN) {
            $cards['approveAuthors'] = [
                'authors' => $this->authorRepository->findIsNotApproved(),
                'link' => $this->adminUrlGenerator->setController(AuthorCrudController::class)->generateUrl()
            ];
            $cards['purgeFiles'] = [
                'link' => $this->generateUrl('admin_files_purge')
            ];
        }

        return $this->render('admin/dashboard.html.twig', [
            'user' => $this->getUser(),
            'author' => $this->authorRepository->findOneByUser($this->getUser()),
            'cards' => $cards
        ]);
    }

    /**
     * Default templates location: vendor/easycorp/easyadmin-bundle/src/Resources/views
     * @return Crud
     */
    public function configureCrud(): Crud
    {
        // Prepare CKFinder
        CKFinderAuthenticator::setData(
            ['roles' => $this->getUser()->getRoles()],
            ['entity' => $_GET['entityId'] ?? null],
        );
        CKFinderAuthenticator::isGranted();

        $crud = Crud::new();
        return $crud
            ->showEntityActionsInlined()
            ->overrideTemplate('layout', 'admin/default/layout.html.twig');
    }

    public function configureDashboard(): Dashboard
    {
        $dashboard = Dashboard::new()
            ->setTitle($this->adminOptionRepository->get('SITE_NAME')->getValue() ?? '')
            ->setFaviconPath($this->adminOptionRepository->get('SITE_FAVICON')->getValue() ?? '');
        return $dashboard;
    }

    public function configureMenuItems(): iterable
    {
        yield MenuItem::linkToDashboard('Dashboard', 'fas fa-tachometer-alt');
        yield MenuItem::linkToUrl('View website', 'fas fa-eye', '/')
            ->setPermission('ROLE_ADMIN')
            ->setLinkTarget('_blank');

        yield MenuItem::section('Content');
        yield MenuItem::subMenu('Portfolio', 'fas fa-images')->setSubItems([
            MenuItem::linkToCrud('Projects', 'fas fa-list', Project::class),
            MenuItem::linkToCrud('New', 'fas fa-plus', Project::class)
                ->setAction(Action::NEW),
            MenuItem::linkToCrud('Categories', 'fas fa-tags', ProjectCategory::class)
        ])->setPermission('ROLE_ADMIN');
        yield MenuItem::subMenu('Lessons', 'fas fa-book')->setSubItems([
            MenuItem::linkToCrud('List', 'fas fa-list', Lesson::class),

            MenuItem::linkToCrud('New', 'fas fa-plus', Lesson::class)
                ->setAction(Action::NEW),
            MenuItem::linkToCrud('Categories', 'fas fa-tags', LessonCategory::class)
                ->setPermission('ROLE_ADMIN')
        ]);
        yield MenuItem::subMenu('Blog', 'fas fa-newspaper')->setSubItems([
            MenuItem::linkToCrud('Posts', 'fas fa-list', Post::class),
            MenuItem::linkToCrud('New post', 'fas fa-plus', Post::class)
                ->setAction(Action::NEW),
            MenuItem::linkToCrud('Categories', 'fas fa-tags', PostCategory::class)
                ->setPermission('ROLE_ADMIN')
        ]);
        yield MenuItem::linkToCrud('Comments', 'fas fa-comment', Comment::class)
            ->setPermission('ROLE_ADMIN');
        yield MenuItem::linkToCrud('Authors', 'fas fa-feather', Author::class);

        if ($this->isGranted('ROLE_ADMIN')) {
            yield MenuItem::section('Meta data');
            yield MenuItem::linkToCrud('Coding languages', 'fas fa-code', CodingLanguage::class);
            yield MenuItem::linkToCrud('Technologies', 'fas fa-cubes', Technology::class);
            yield MenuItem::linkToCrud('Tools', 'fas fa-wrench', Tool::class);

            yield MenuItem::section('Settings');
            yield MenuItem::linkToCrud('Options', 'fas fa-cog', AdminOption::class);
            yield MenuItem::linkToCrud('Users', 'fas fa-user', User::class);
        }
    }

    public function configureUserMenu(UserInterface $user): UserMenu
    {
        $author = $this->authorRepository->findOneByUser($user);

        /** @var User $user */
        $links = parent::configureUserMenu($user);
        if ($author) {
            $links
                ->setName($author->getFullName())
                ->addMenuItems([MenuItem::linkToUrl(
                    'My Author profile',
                    'fas fa-feather',
                    $this->adminUrlGenerator
                        ->setController(AuthorCrudController::class)
                        ->setEntityId($author->getId())
                        ->setAction(Action::EDIT)
                        ->generateUrl()
                )]);
        }
        $links->addMenuItems([MenuItem::linkToUrl(
            'My account',
            'fas fa-user',
            $this->adminUrlGenerator
                ->setController(UserCrudController::class)
                ->setEntityId($user->getId())
                ->setAction(Action::EDIT)
                ->generateUrl()
        )]);
        return $links;
    }

    // public function configureAssets(): Assets
    // {
    //     return Assets::new()
    //         ->addWebpackEncoreEntry('admin');
    // }
}
