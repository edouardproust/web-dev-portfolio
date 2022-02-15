<?php

namespace App\Controller\Admin;

use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use InvalidArgumentException;

abstract class AbstractEntityCrudController extends AbstractCrudController
{
    abstract public static function getEntityFqcn(): string;

    abstract public function setFields(): array;

    public function configureFields(string $pageName): iterable
    {
        $fields = $this->setFields();

        foreach ($fields as $field) {
            $field->setColumns(12);
        }
        return $fields;
    }
}
