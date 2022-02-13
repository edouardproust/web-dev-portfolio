<?php

namespace App\Controller\Admin;

use App\Entity\Post;
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

class PostCrudController extends AbstractPosttypeCrudController
{
    protected $route = 'post';

    public static function getEntityFqcn(): string
    {
        return Post::class;
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
            AssociationField::new('categories')
                ->hideOnIndex(),
            AssociationField::new('author')
                ->hideWhenCreating()

        ];
    }
}
