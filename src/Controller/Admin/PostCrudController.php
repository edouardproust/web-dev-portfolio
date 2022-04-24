<?php

namespace App\Controller\Admin;

use App\Path;
use App\Config;
use App\Entity\Post;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use Vich\UploaderBundle\Form\Type\VichImageType;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\FormField;
use EasyCorp\Bundle\EasyAdminBundle\Field\SlugField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use App\Controller\Admin\AbstractPosttypeCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\HiddenField;

class PostCrudController extends AbstractPosttypeCrudController
{
    protected $route = 'post';

    public static function getEntityFqcn(): string
    {
        return Post::class;
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
        yield HiddenField::new('content')
            ->setFormTypeOption('attr', ['class' => 'ckeditorField adminCrud'])
            ->hideOnIndex();

        yield FormField::addPanel()->setCssClass(Config::ADMIN_FORM_SIDE_CSS_CLASS);
        yield SlugField::new('slug')
            ->setTargetFieldName('title')
            ->hideOnIndex();
        yield ImageField::new('mainImage', 'Featured image')
            ->setBasePath(Path::UPLOADS_POSTS)
            ->onlyOnIndex()
            ->setSortable(false);
        yield TextField::new('mainImageFile')
            ->setFormType(VichImageType::class)
            ->onlyOnForms();
        yield AssociationField::new('categories')->hideOnIndex();
        yield $this->associationFieldAuthor()->onlyOnIndex(); // all: show on index
        yield $this->associationFieldAuthor()->setPermission('ROLE_ADMIN'); // authors: hide field on edit
        yield DateTimeField::new('createdAt', 'Creation date') // all: show on index
            ->onlyOnIndex()
            ->setFormat('medium');
        yield DateTimeField::new('createdAt', 'Creation date') // authors: hide field on edit
            ->hideWhenCreating()
            ->hideOnIndex()
            ->setFormat('medium')
            ->setPermission('ROLE_ADMIN');
    }
}
