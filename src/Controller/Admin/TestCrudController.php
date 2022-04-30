<?php

namespace App\Controller\Admin;

use App\Entity\Test;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use App\Controller\Admin\AbstractEntityCrudController;

class TestCrudController extends AbstractEntityCrudController
{
    public static function getEntityFqcn(): string
    {
        return Test::class;
    }

    public function setFields(): iterable
    {
        yield IdField::new('id')->onlyOnDetail();
        yield TextField::new('label');
    }

    /*
    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id'),
            TextField::new('title'),
            TextEditorField::new('description'),
        ];
    }
    */
}
