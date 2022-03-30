<?php

namespace App\Controller\Admin;

use App\Service\AdminOptionService;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Field\FormField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

abstract class AbstractEntityCrudController extends AbstractCrudController
{
    abstract public static function getEntityFqcn(): string;

    abstract public function setFields(): iterable;

    public function configureCrud(Crud $crud): Crud
    {
        parent::configureCrud($crud);
        return $crud
            ->addFormTheme('@FOSCKEditor/Form/ckeditor_widget.html.twig');
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
