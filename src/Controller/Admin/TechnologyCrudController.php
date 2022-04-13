<?php

namespace App\Controller\Admin;

use App\Config;
use App\Entity\Technology;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\FormField;
use EasyCorp\Bundle\EasyAdminBundle\Field\SlugField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\UrlField;

class TechnologyCrudController extends AbstractCategoryCrudController
{
    public static function getEntityFqcn(): string
    {
        return Technology::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInPlural('Technologies')
            ->setEntityLabelInSingular('Technology')
            ->setDefaultSort(['label' => 'ASC'])
            ->setEntityPermission(Config::ROLE_ADMIN);
    }

    public function setFields(): iterable
    {
        yield IdField::new('id')->onlyOnDetail();

        yield FormField::addPanel()->setCssClass(Config::ADMIN_FORM_MAIN_CSS_CLASS);
        yield TextField::new('label');
        yield UrlField::new('url');

        yield FormField::addPanel()->setCssClass(Config::ADMIN_FORM_SIDE_CSS_CLASS);
        yield SlugField::new('slug')
            ->setTargetFieldName('label')
            ->hideOnIndex();
    }
}
