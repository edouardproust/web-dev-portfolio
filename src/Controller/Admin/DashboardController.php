<?php

namespace App\Controller\Admin;

use App\Config;
use App\Entity\Post;
use App\Entity\User;
use App\Entity\Author;
use App\Entity\Lesson;
use App\Entity\Project;
use App\Entity\AdminOption;
use App\Entity\PostCategory;
use App\Entity\CodingLanguage;
use App\Entity\LessonCategory;
use App\Entity\ProjectCategory;
use App\Repository\AuthorRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Assets;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Config\UserMenu;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use Symfony\Component\Security\Core\User\UserInterface;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;

class DashboardController extends AbstractDashboardController
{

    private $authorRepository;

    public function __construct(AuthorRepository $authorRepository)
    {
        $this->authorRepository = $authorRepository;
    }

    /**
     * @Route("/admin", name="admin")
     */
    public function index(): Response
    {
        // return parent::index();
        // return $this->redirect(
        //     $this->adminUrlGenerator->setController(OneOfYourCrudController::class)->generateUrl()
        // );
        return $this->render('admin/dashboard.html.twig', [
            'user' => $this->getUser(),
            'author' => $this->authorRepository->findOneByUser($this->getUser())
        ]);
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle(Config::SITE_NAME);
    }

    public function configureMenuItems(): iterable
    {
        yield MenuItem::linkToDashboard('Dashboard', 'fas fa-tachometer-alt');
        yield MenuItem::linkToUrl('View website', 'fas fa-eye', '/')
            ->setPermission('ROLE_ADMIN');

        yield MenuItem::section('Content');
        yield MenuItem::subMenu('Portfolio', 'fas fa-images')->setSubItems([
            MenuItem::linkToCrud('Projects', 'fas fa-list', Project::class),
            MenuItem::linkToCrud('New', 'fas fa-plus', Project::class)
                ->setAction('new'),
            MenuItem::linkToCrud('Categories', 'fas fa-tags', ProjectCategory::class)
                ->setPermission('ROLE_ADMIN')
        ]);
        yield MenuItem::subMenu('Learn', 'fas fa-book')->setSubItems([
            MenuItem::linkToCrud('Lessons', 'fas fa-list', Lesson::class),
            MenuItem::linkToCrud('New', 'fas fa-plus', Lesson::class)
                ->setAction('new'),
            MenuItem::linkToCrud('Categories', 'fas fa-tags', LessonCategory::class)
                ->setPermission('ROLE_ADMIN')
        ]);
        yield MenuItem::subMenu('Blog', 'fas fa-newspaper')->setSubItems([
            MenuItem::linkToCrud('Posts', 'fas fa-list', Post::class),
            MenuItem::linkToCrud('New post', 'fas fa-plus', Post::class)
                ->setAction('new'),
            MenuItem::linkToCrud('Categories', 'fas fa-tags', PostCategory::class)
                ->setPermission('ROLE_ADMIN')
        ]);

        if ($this->isGranted('ROLE_ADMIN')) {
            yield MenuItem::section('Settings');
            yield MenuItem::linkToCrud('Options', 'fas fa-cog', AdminOption::class);
            yield MenuItem::linkToCrud('Users', 'fas fa-user', User::class);
            yield MenuItem::linkToCrud('Authors', 'fas fa-feather', Author::class);
            yield MenuItem::linkToCrud('Coding languages', 'fas fa-code', CodingLanguage::class);
        }
    }

    public function configureUserMenu(UserInterface $user): UserMenu
    {
        // 'parent' method: gives menu items already created ("sign out", "exit impersonation", etc.)
        // create the user menu from scratch: return UserMenu::new()->...
        return parent::configureUserMenu($user)
            // you can use any type of menu item, except submenus
            ->setName($this->authorRepository->findOneByUser($user)->getFullName())
            ->addMenuItems([
                MenuItem::linkToRoute('My Profile', 'fas fa-user', '...')
            ]);
    }

    /**
     * Default templates location: vendor/easycorp/easyadmin-bundle/src/Resources/views
     * @return Crud
     */
    public function configureCrud(): Crud
    {
        $crud = Crud::new();
        return $crud
            ->showEntityActionsInlined()
            ->overrideTemplate('layout', 'admin/default/layout.html.twig');
    }

    public function configureAssets(): Assets
    {
        return Assets::new()
            ->addWebpackEncoreEntry('admin-app');
    }
}
