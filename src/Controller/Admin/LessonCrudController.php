<?php

namespace App\Controller\Admin;

use App\Config;
use App\Entity\Lesson;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\UrlField;
use EasyCorp\Bundle\EasyAdminBundle\Field\FormField;
use EasyCorp\Bundle\EasyAdminBundle\Field\SlugField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use App\Controller\Admin\AbstractPosttypeCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;

class LessonCrudController extends AbstractPosttypeCrudController
{
    protected $route = 'lesson';

    public static function getEntityFqcn(): string
    {
        return Lesson::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        parent::configureCrud($crud);
        return $crud
            ->setEntityLabelInSingular(ucfirst($this->route))
            ->setEntityLabelInPlural(ucfirst($this->route) . 's')
            ->setDefaultSort(['createdAt' => 'DESC']);
    }

    public function setFields(): iterable
    {
        yield IdField::new('id')->onlyOnDetail();

        yield FormField::addPanel()->setCssClass(Config::ADMIN_FORM_MAIN_CSS_CLASS);
        yield TextField::new('titleExtract', 'Title')->onlyOnIndex();
        yield TextField::new('title')->onlyOnForms();
        yield TextareaField::new('headline')->hideOnIndex();
        yield TextareaField::new('content')
            ->setFormTypeOption('attr', ['class' => 'ckeditorField adminCrud'])
            ->hideOnIndex();
            
        yield FormField::addPanel()->setCssClass(Config::ADMIN_FORM_SIDE_CSS_CLASS);
        yield SlugField::new('slug')
            ->setTargetFieldName('title')
            ->hideOnIndex();
        yield AssociationField::new('codingLanguage', "Language")
            ->setRequired(true);
        yield AssociationField::new('categories')->hideOnIndex();
        yield UrlField::new('videoUrl')->hideOnIndex();
        yield UrlField::new('url', 'Project link')->hideOnIndex();
        yield UrlField::new('repository')->hideOnIndex();
        yield $this->associationFieldAuthor()->onlyOnIndex(); // all: show on index
        yield $this->associationFieldAuthor()->setPermission('ROLE_ADMIN'); // authors: hide field on edit
        yield DateTimeField::new('createdAt', 'Creation date') // all: show on index
            ->onlyOnIndex()
            ->setFormat('medium');
        yield DateTimeField::new('createdAt', 'Creation date') // auhtors: hide field on edit
            ->hideWhenCreating()
            ->hideOnIndex()
            ->setFormat('medium')
            ->setPermission('ROLE_ADMIN');
    }
}
