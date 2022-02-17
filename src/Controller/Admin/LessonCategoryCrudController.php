<?php

namespace App\Controller\Admin;

use App\Config;
use App\Entity\LessonCategory;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\FormField;
use EasyCorp\Bundle\EasyAdminBundle\Field\SlugField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;

class LessonCategoryCrudController extends AbstractCategoryCrudController
{
    public static function getEntityFqcn(): string
    {
        return LessonCategory::class;
    }

    public function setFields(): array
    {
        return [
            IdField::new('id')
                ->onlyOnDetail(),
            FormField::addPanel()->setCssClass(Config::ADMIN_FORM_MAIN_CSS_CLASS),
            TextField::new('label'),
            TextareaField::new('description')
                ->hideOnIndex()
                ->setMaxLength(255),

            FormField::addPanel()->setCssClass(Config::ADMIN_FORM_SIDE_CSS_CLASS),
            SlugField::new('slug')
                ->setTargetFieldName('label')
                ->hideOnIndex(),
        ];
    }
}
