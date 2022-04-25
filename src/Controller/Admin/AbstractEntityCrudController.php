<?php

namespace App\Controller\Admin;

use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
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
            ->setPageTitle(Crud::PAGE_EDIT, 'Edit %entity_label_singular% "<b>%entity_as_string%</b>"')
            ->setPageTitle(Crud::PAGE_NEW, 'Create a new %entity_label_singular%')
        ;
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
