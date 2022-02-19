<?php

namespace App\Controller\Admin;

use App\Config;
use App\Entity\Author;
use App\Entity\Project;
use Doctrine\ORM\QueryBuilder;
use Vich\UploaderBundle\Form\Type\VichImageType;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\UrlField;
use EasyCorp\Bundle\EasyAdminBundle\Field\FormField;
use EasyCorp\Bundle\EasyAdminBundle\Field\SlugField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;

class ProjectCrudController extends AbstractPosttypeCrudController
{

    protected $route = 'project';

    public static function getEntityFqcn(): string
    {
        return Project::class;
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
        yield TextField::new('mainImageFile', 'Featured image')
            ->setFormType(VichImageType::class)
            ->setSortable(false);
        yield UrlField::new('url', 'Project link')->hideOnIndex();
        yield UrlField::new('repository')->hideOnIndex();
        yield BooleanField::new('featured');
        yield AssociationField::new('categories')->hideOnIndex();
        yield AssociationField::new('codingLanguages', 'Languages')->hideOnIndex();
        yield $this->associationFieldAuthor();
        yield DateTimeField::new('createdAt', 'Creation date')
            ->hideWhenCreating()
            ->setFormat('medium');
    }
}
