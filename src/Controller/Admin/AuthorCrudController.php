<?php

namespace App\Controller\Admin;

use App\Config;
use App\Entity\Author;
use App\Service\EasyAdminService;
use App\Repository\AuthorRepository;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use Vich\UploaderBundle\Form\Type\VichImageType;
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
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;

class AuthorCrudController extends AbstractEntityCrudController
{
    private $authorRepository;
    private $easyAdminService;

    public function __construct(
        AuthorRepository $authorRepository,
        EasyAdminService $easyAdminService
    ) {
        $this->authorRepository = $authorRepository;
        $this->easyAdminService = $easyAdminService;
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

    public function configureActions(Actions $actions): Actions
    {
        $actions
            ->remove(Crud::PAGE_INDEX, Action::DELETE)
            ->remove(Crud::PAGE_EDIT, Action::SAVE_AND_CONTINUE)
            ->add(Crud::PAGE_EDIT, Action::DELETE)
            ->reorder(Crud::PAGE_EDIT, [Action::SAVE_AND_RETURN, Action::DELETE]);
        return $actions;
    }

    public function setFields(): iterable
    {
        yield IdField::new('id')->onlyOnDetail();

        yield FormField::addPanel()->setCssClass(Config::ADMIN_FORM_MAIN_CSS_CLASS);
        yield $this->easyAdminService->authorIsApprovedField();
        yield TextField::new('fullName');
        yield $this->easyAdminService->authorUserField();
        yield TextareaField::new('bio')->hideOnIndex();
        yield ImageField::new('avatar', 'Photo')->setBasePath('/uploads/authors/')->onlyOnIndex();
        yield TextField::new('avatarFile', 'Photo')
            ->setFormType(VichImageType::class)
            ->setSortable(false);

        yield FormField::addPanel()->setCssClass(Config::ADMIN_FORM_SIDE_CSS_CLASS);
        yield EmailField::new('contactEmail')->hideOnIndex();
        yield UrlField::new('website')->hideOnIndex();
        yield Urlfield::new('github', 'GitHub profile Url')->hideOnIndex();
        yield Urlfield::new('stackoverflow', 'StackOverflow profile Url')->hideOnIndex();
        yield Urlfield::new('LinkedIn', 'LinkedIn profile Url')->hideOnIndex();
        yield AssociationField::new('projects')->hideOnForm();
        yield AssociationField::new('lessons')->hideOnForm();
        yield AssociationField::new('posts')->hideOnForm();
    }
}
