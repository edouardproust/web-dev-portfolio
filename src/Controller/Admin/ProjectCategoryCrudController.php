<?php

namespace App\Controller\Admin;

use App\Config;
use App\Entity\ProjectCategory;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\FormField;
use EasyCorp\Bundle\EasyAdminBundle\Field\SlugField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;

class ProjectCategoryCrudController extends AbstractCategoryCrudController
{
    public static function getEntityFqcn(): string
    {
        return ProjectCategory::class;
    }

    public function setFields(): iterable
    {
        yield IdField::new('id')
            ->onlyOnDetail();
        yield FormField::addPanel()->setCssClass(Config::ADMIN_FORM_MAIN_CSS_CLASS);
        yield TextField::new('label');
        yield TextareaField::new('description')
            ->hideOnIndex()
            ->setMaxLength(255);

        yield FormField::addPanel()->setCssClass(Config::ADMIN_FORM_SIDE_CSS_CLASS);
        yield SlugField::new('slug')
            ->setTargetFieldName('label')
            ->hideOnIndex();
    }
}
