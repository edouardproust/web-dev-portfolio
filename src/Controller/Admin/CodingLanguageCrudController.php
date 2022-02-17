<?php

namespace App\Controller\Admin;

use App\Config;
use App\Entity\CodingLanguage;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\FormField;
use EasyCorp\Bundle\EasyAdminBundle\Field\SlugField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

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
            ->setDefaultSort(['label' => 'ASC'])
            ->setEntityPermission('ROLE_ADMIN');
    }

    public function setFields(): array
    {
        return [
            IdField::new('id')
                ->onlyOnDetail(),

            FormField::addPanel()->setCssClass(Config::ADMIN_FORM_MAIN_CSS_CLASS),
            TextField::new('label'),
            FormField::addPanel()->setCssClass(Config::ADMIN_FORM_SIDE_CSS_CLASS),
            SlugField::new('slug')
                ->setTargetFieldName('label')
                ->hideOnIndex(),
        ];
    }
}
