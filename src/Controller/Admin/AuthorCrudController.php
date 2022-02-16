<?php

namespace App\Controller\Admin;

use App\Entity\User;
use App\Entity\Author;
use Doctrine\ORM\QueryBuilder;
use App\Repository\UserRepository;
use App\Repository\AuthorRepository;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Field\UrlField;
use EasyCorp\Bundle\EasyAdminBundle\Field\FormField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\EmailField;
use App\Controller\Admin\AbstractEntityCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;

class AuthorCrudController extends AbstractEntityCrudController
{
    private  $authorRepository;

    public function __construct(AuthorRepository $authorRepository)
    {
        $this->authorRepository = $authorRepository;
    }

    public static function getEntityFqcn(): string
    {
        return Author::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        $crud
            ->setEntityPermission('ROLE_ADMIN')
            ->setEntityLabelInPlural('Authors')
            ->setDefaultSort(['fullName' => 'ASC']);
        // page title
        $currentAuthor = $this->authorRepository->findOneByUser($this->getUser());
        $entityId = !empty($_GET['entityId']) ? $_GET['entityId'] : null;
        if ($currentAuthor->getId() == $entityId) {
            $crud->setPageTitle(Crud::PAGE_EDIT, 'My Author Profile');
        }
        return $crud;
    }

    public function setFields(): array
    {
        $fields = [
            IdField::new('id')->onlyOnDetail(),

            FormField::addPanel()->setCssClass('col-md-8'),
            TextField::new('fullName'),
            AssociationField::new('user')
                ->hideWhenUpdating()
                ->setQueryBuilder(
                    function (QueryBuilder $builder) {
                        return $builder
                            ->select('u')
                            ->from(User::class, 'u')
                            ->where('u.isAuthor IS NULL');
                    }
                ),
            TextareaField::new('bio')
                ->hideOnIndex(),
            // ImageField::new('avatar')
            //     ->setSortable(false),

            FormField::addPanel()->setCssClass('col-md-4'),
            EmailField::new('contactEmail')->hideOnIndex(),
            UrlField::new('website')->hideOnIndex(),
            Urlfield::new('github')
                ->hideOnIndex()
                ->setLabel('GitHub profile Url'),
            Urlfield::new('stackoverflow')
                ->hideOnIndex()
                ->setLabel('StackOverflow profile Url'),
            Urlfield::new('LinkedIn')
                ->hideOnIndex()
                ->setLabel('LinkedIn profile Url'),

            AssociationField::new('projects')->hideOnForm(),
            AssociationField::new('lessons')->hideOnForm(),
            AssociationField::new('posts')->hideOnForm()
        ];

        return $fields;
    }

    public function configureActions(Actions $actions): Actions
    {
        $actions
            ->remove(Crud::PAGE_INDEX, Action::DELETE)
            ->remove(Crud::PAGE_EDIT, Action::SAVE_AND_CONTINUE)
            ->add(Crud::PAGE_EDIT, Action::DELETE)
            ->reorder(Crud::PAGE_EDIT, [Action::SAVE_AND_RETURN, Action::DELETE]);
        return $actions;
    }
}
