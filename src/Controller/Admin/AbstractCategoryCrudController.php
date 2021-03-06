<?php

namespace App\Controller\Admin;

use App\Config;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;

abstract class AbstractCategoryCrudController extends AbstractEntityCrudController
{
    const MANDATORY_PROPERTIES_IN_CHILD = ['route'];

    protected $route;

    abstract public static function getEntityFqcn(): string;

    abstract public function setFields(): iterable;

    public function configureCrud(Crud $crud): Crud
    {
        parent::configureCrud($crud);
        return $crud
            ->setEntityLabelInPlural('Categories')
            ->setEntityLabelInSingular('Category')
            ->setDefaultSort(['label' => 'ASC'])
            ->setEntityPermission(Config::ROLE_ADMIN);
        ;
    }

    public function configureActions(Actions $actions): Actions
    {
        parent::configureActions($actions);
        return $actions
            ->update(
                Crud::PAGE_INDEX,
                Action::EDIT,
                function (Action $action) {
                    return $action
                    ->setIcon('fa fa-edit')
                    ->setLabel(false)
                    ->setCssClass('btn btn-primary admin-crud-row-btn');
                }
            )
            ->update(
                Crud::PAGE_INDEX,
                Action::DELETE,
                function (Action $action) {
                    return $action
                    ->setIcon('fa fa-trash')->setLabel(false)
                    ->setCssClass('btn btn-light admin-crud-row-btn');
                }
            )
            ->remove(Crud::PAGE_EDIT, Action::SAVE_AND_CONTINUE);
    }
}
