<?php

namespace App\Controller\Admin;

use App\Entity\AdminOption;
use App\Entity\Author;
use App\Entity\CodingLanguage;
use App\Entity\Lesson;
use App\Entity\LessonCategory;
use App\Entity\Post;
use App\Entity\PostCategory;
use App\Entity\Project;
use App\Entity\ProjectCategory;
use App\Entity\User;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DashboardController extends AbstractDashboardController
{
    /**
     * @Route("/admin", name="admin")
     */
    public function index(): Response
    {
        return parent::index();
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('Edouard Proust Portfolio');
    }

    public function configureMenuItems(): iterable
    {
        yield MenuItem::linkToDashboard('Dashboard', 'fa fa-home');

        yield MenuItem::section('Content');
        yield MenuItem::subMenu('Portfolio', 'fa fa-images')->setSubItems([
            MenuItem::linkToCrud('Projects', 'fas fa-list', Project::class),
            MenuItem::linkToCrud('New', 'fas fa-plus', Project::class),
            MenuItem::linkToCrud('Categories', 'fas fa-tags', ProjectCategory::class),
        ]);
        yield MenuItem::subMenu('Learn', 'fas fa-book')->setSubItems([
            MenuItem::linkToCrud('Lessons', 'fas fa-list', Lesson::class),
            MenuItem::linkToCrud('New', 'fas fa-plus', Post::class),
            MenuItem::linkToCrud('Categories', 'fas fa-tags', LessonCategory::class),
        ]);
        yield MenuItem::subMenu('Blog', 'fas fa-newspaper')->setSubItems([
            MenuItem::linkToCrud('Posts', 'fas fa-list', Post::class),
            MenuItem::linkToCrud('New post', 'fas fa-plus', Post::class),
            MenuItem::linkToCrud('Categories', 'fas fa-tags', PostCategory::class),
        ]);

        yield MenuItem::section('Settings');
        yield MenuItem::linkToCrud('Options', 'fas fa-cog', AdminOption::class);
        yield MenuItem::linkToCrud('Authors', 'fas fa-feather', Author::class);
        yield MenuItem::linkToCrud('Users', 'fas fa-user', User::class);
        yield MenuItem::linkToCrud('Coding languages', 'fas fa-code', CodingLanguage::class);


    }
}
