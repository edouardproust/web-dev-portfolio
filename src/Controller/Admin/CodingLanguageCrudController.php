<?php

namespace App\Controller\Admin;

use App\Entity\CodingLanguage;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;

class CodingLanguageCrudController extends AbstractCategoryCrudController
{
    public static function getEntityFqcn(): string
    {
        return CodingLanguage::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInPlural('Coding Languages')
            ->setEntityLabelInSingular('Language')
            ->setDefaultSort(['label' => 'ASC']);
    }
}
