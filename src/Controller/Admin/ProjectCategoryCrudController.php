<?php

namespace App\Controller\Admin;

use App\Entity\ProjectCategory;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class ProjectCategoryCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return ProjectCategory::class;
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
