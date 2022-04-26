<?php

namespace App\Controller\Admin;

use App\Config;
use App\Entity\AdminOption;
use App\Service\EasyAdminService;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Field\FormField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use App\Controller\Admin\AbstractEntityCrudController;

class AdminOptionCrudController extends AbstractEntityCrudController
{
    private $easyAdminService;

    public function __construct(
        EasyAdminService $easyAdminService
    ) {
        $this->easyAdminService = $easyAdminService;
    }

    public static function getEntityFqcn(): string
    {
        return AdminOption::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        parent::configureCrud($crud);
        return $crud
            ->setEntityLabelInPlural('Options')
            ->setDefaultSort(['id' => 'ASC'])
            ->setEntityLabelInSingular(
                $this->easyAdminService->getEntityLabelSingular(false, 'Option')
            )
            ->setEntityPermission(Config::ROLE_ADMIN)
            ->setPaginatorPageSize(100)
            ->setPageTitle(Crud::PAGE_EDIT, fn ($entity) => 'Edit ' . $entity->getLabel());
    }

    public function setFields(): iterable
    {
        yield IdField::new('id')->onlyOnDetail();

        // index
        yield TextField::new('label', 'Option')
            ->hideOnForm()
            ->setSortable(false);
        yield TextField::new('unifiedValue', 'Value')->onlyOnIndex();

        // form
        yield FormField::addPanel()->setCssClass(Config::ADMIN_FORM_MAIN_CSS_CLASS);
        yield $this->easyAdminService->adminOptionValueField();
    }

    public function configureActions(Actions $actions): Actions
    {
        parent::configureActions($actions);
        return $actions
            ->remove(Crud::PAGE_INDEX, Action::DELETE)
            ->remove(Crud::PAGE_INDEX, Action::NEW)
            ->remove(Crud::PAGE_EDIT, Action::SAVE_AND_CONTINUE);
    }
}
