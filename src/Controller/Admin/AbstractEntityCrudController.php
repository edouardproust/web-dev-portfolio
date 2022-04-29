<?php

namespace App\Controller\Admin;

use App\Helper\StringHelper;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Field\FormField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

abstract class AbstractEntityCrudController extends AbstractCrudController
{
    protected $entityId;

    abstract public static function getEntityFqcn(): string;

    abstract public function setFields(): iterable;

    public function __construct()
    {
        $this->entityId = !empty($_GET['entityId']) ? (int)$_GET['entityId'] : null;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            // Change page title options for every CRUDs here
            // Title options: https://symfony.com/bundles/EasyAdminBundle/current/crud.html#title-and-help-options
            // it can include these placeholders:
            //   %entity_name%, %entity_as_string%, %entity_id%, %entity_short_id%
            //   %entity_label_singular%, %entity_label_plural%
            ->setPageTitle(Crud::PAGE_INDEX, 'All %entity_label_plural%')
            ->setPageTitle(Crud::PAGE_NEW, 'Create a new %entity_label_singular%')
            ->setPageTitle(Crud::PAGE_EDIT, function ($entity) {
                $title = '';
                if (property_exists($entity, 'title')) {
                    $title = StringHelper::extract($entity->getTitle(), 30);
                    $title = ' <i>' . $title . '</i>';
                }
                return 'Edit %entity_label_singular%' . $title;
            })
        ;
    }

    public function configureActions(Actions $actions): Actions
    {
        return $actions
            ->update(
                Crud::PAGE_EDIT,
                Action::SAVE_AND_RETURN,
                fn (Action $action) => $action->setLabel('Save and return')
            )
            ->update(
                Crud::PAGE_EDIT,
                Action::SAVE_AND_CONTINUE,
                fn (Action $action) => $action->setLabel('Save and continue')
            )
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
                        ->setIcon('fa fa-trash')
                        ->setLabel(false)
                        ->setCssClass('action-delete btn btn-light admin-crud-row-btn');
                }
            );
    }

    public function configureFields(string $pageName): iterable
    {
        foreach ($this->setFields() as $field) {
            $field->setColumns(12);
            if ($field->getAsDto()->getFieldFqcn() === FormField::class) {
                $field->hideOnDetail();
            }
            yield $field;
        }
    }
}
