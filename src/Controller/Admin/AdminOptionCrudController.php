<?php

namespace App\Controller\Admin;

use App\Entity\AdminOption;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class AdminOptionCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return AdminOption::class;
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
