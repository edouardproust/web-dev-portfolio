<?php

namespace App\Controller\Admin;

use App\Entity\Author;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\SlugField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use App\Controller\Admin\AbstractEntityCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;

class AuthorCrudController extends AbstractEntityCrudController
{
    public static function getEntityFqcn(): string
    {
        return Author::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud->setEntityPermission('ROLE_ADMIN');
    }

    public function setFields(): array
    {
        return [
            IdField::new('id'),
            SlugField::new('slug')
                ->setTargetFieldName('label'),
            TextField::new('title'),
            TextEditorField::new('description'),
        ];
    }
}
