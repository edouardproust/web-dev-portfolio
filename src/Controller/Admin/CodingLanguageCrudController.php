<?php

namespace App\Controller\Admin;

use App\Entity\CodingLanguage;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class CodingLanguageCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return CodingLanguage::class;
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
