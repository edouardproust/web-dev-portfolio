<?php

namespace App\Controller\Admin;

use App\Entity\Project;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\UrlField;
use EasyCorp\Bundle\EasyAdminBundle\Field\FormField;
use EasyCorp\Bundle\EasyAdminBundle\Field\SlugField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;

class ProjectCrudController extends AbstractPosttypeCrudController
{

    protected $route = 'project';

    public static function getEntityFqcn(): string
    {
        return Project::class;
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
            UrlField::new('url')
                ->setLabel('Project link')
                ->hideOnIndex(),
            UrlField::new('repository')
                ->hideOnIndex(),
            BooleanField::new('featured'),
            AssociationField::new('categories')
                ->hideOnIndex(),
            AssociationField::new('codingLanguages')
                ->hideOnIndex()
                ->setLabel('Languages'),
            AssociationField::new('author')
                ->hideWhenCreating()
        ];
    }
}
