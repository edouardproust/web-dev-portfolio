<?php

namespace App\Controller\Admin;

use App\Config;
use App\Entity\Tool;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\UrlField;
use EasyCorp\Bundle\EasyAdminBundle\Field\FormField;
use EasyCorp\Bundle\EasyAdminBundle\Field\SlugField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use App\Controller\Admin\AbstractCategoryCrudController;

class ToolCrudController extends AbstractCategoryCrudController
{
    public static function getEntityFqcn(): string
    {
        return Tool::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInPlural('Tools')
            ->setEntityLabelInSingular('Tool')
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
