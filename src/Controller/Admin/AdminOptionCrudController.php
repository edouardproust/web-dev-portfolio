<?php

namespace App\Controller\Admin;

use App\Entity\AdminOption;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use App\Controller\Admin\AbstractEntityCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;

class AdminOptionCrudController extends AbstractCategoryCrudController
{
    public static function getEntityFqcn(): string
    {
        return AdminOption::class;
    }

    public function setFields(): array
    {
        return [
            IdField::new('id'),
            TextField::new('constant'),
            TextareaField::new('value'),
        ];
    }
}
