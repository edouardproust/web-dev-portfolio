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
