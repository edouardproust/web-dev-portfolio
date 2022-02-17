<?php

namespace App\Controller\Admin;

use App\Config;
use App\Entity\Lesson;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\UrlField;
use EasyCorp\Bundle\EasyAdminBundle\Field\FormField;
use EasyCorp\Bundle\EasyAdminBundle\Field\SlugField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use App\Controller\Admin\AbstractPosttypeCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;

class LessonCrudController extends AbstractPosttypeCrudController
{

    protected $route = 'lesson';

    public static function getEntityFqcn(): string
    {
        return Lesson::class;
    }

    public function setFields(): iterable
    {
        yield IdField::new('id')->onlyOnDetail();

        yield FormField::addPanel()->setCssClass(Config::ADMIN_FORM_MAIN_CSS_CLASS);
        yield TextField::new('title');
        yield TextareaField::new('headline')->hideOnIndex();
        yield TextEditorField::new('content')->hideOnIndex();

        yield FormField::addPanel()->setCssClass(Config::ADMIN_FORM_SIDE_CSS_CLASS);
        yield SlugField::new('slug')
            ->setTargetFieldName('title')
            ->hideOnIndex();
        // yield ImageField::new('mainImage')
        //     ->setLabel('Featured image')
        //     ->setSortable(false);
        yield UrlField::new('videoUrl')->hideOnIndex();
        yield UrlField::new('url')
            ->setLabel('Project link')
            ->hideOnIndex();
        yield UrlField::new('repository')->hideOnIndex();
        yield AssociationField::new('codingLanguage')->hideOnIndex();
        yield AssociationField::new('categories')->hideOnIndex();
        yield AssociationField::new('author')->hideWhenCreating();
        yield DateTimeField::new('createdAt')
            ->hideWhenCreating()
            ->setFormat('medium')
            ->setLabel('Creation date');
    }
}
