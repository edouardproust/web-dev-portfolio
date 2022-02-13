<?php

namespace App\Controller\Admin;

use App\Entity\Lesson;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\UrlField;
use EasyCorp\Bundle\EasyAdminBundle\Field\FormField;
use EasyCorp\Bundle\EasyAdminBundle\Field\SlugField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use App\Controller\Admin\AbstractPosttypeCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;

class LessonCrudController extends AbstractPosttypeCrudController
{

    protected $route = 'lesson';

    public static function getEntityFqcn(): string
    {
        return Lesson::class;
    }

    public function setFields(): array
    {
        return [
            IdField::new('id')
                ->onlyOnDetail(),

            FormField::addPanel()->setCssClass('col-md-8'),
            TextField::new('title'),
            TextareaField::new('headline')
                ->hideOnIndex(),
            TextEditorField::new('content')
                ->hideOnIndex(),

            FormField::addPanel()->setCssClass('col-md-4'),
            SlugField::new('slug')
                ->setTargetFieldName('title')
                ->hideOnIndex(),
            DateTimeField::new('createdAt')
                ->hideOnForm()
                ->setFormat('medium')
                ->setLabel('Creation date'),
            // ImageField::new('mainImage')
            //     ->setLabel('Featured image')
            //     ->setSortable(false),
            UrlField::new('videoUrl')
                ->hideOnIndex(),
            UrlField::new('url')
                ->setLabel('Project link')
                ->hideOnIndex(),
            UrlField::new('repository')
                ->hideOnIndex(),
            AssociationField::new('codingLanguage')
                ->hideOnIndex(),
            AssociationField::new('categories')
                ->hideOnIndex(),
            AssociationField::new('author')
                ->hideWhenCreating()
        ];
    }
}
