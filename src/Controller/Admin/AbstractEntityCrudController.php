<?php

namespace App\Controller\Admin;

use EasyCorp\Bundle\EasyAdminBundle\Field\FormField;
use EasyCorp\Bundle\EasyAdminBundle\Contracts\Field\FieldInterface;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

abstract class AbstractEntityCrudController extends AbstractCrudController
{
    abstract public static function getEntityFqcn(): string;

    abstract public function setFields(): array;

    public function configureFields(string $pageName): iterable
    {
        $fields = $this->setFields();

        // hide FormField on Crud::DETAIL
        foreach ($fields as $field) {
            $field->setColumns(12);
            /** @var FieldInterface $field */
            if ($field->getAsDto()->getFieldFqcn() === FormField::class) {
                /** @var FormField $field */
                $field->hideOnDetail();
            }
        }

        return $fields;
    }
}
