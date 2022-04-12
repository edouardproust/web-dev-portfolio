<?php

namespace App\Controller\Admin;

use App\Path;
use App\Config;
use App\Entity\Project;
use App\Form\GalleryItemType;
use FOS\CKEditorBundle\Form\Type\CKEditorType;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
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
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\CollectionField;

class ProjectCrudController extends AbstractPosttypeCrudController
{
    protected $route = 'project';

    public static function getEntityFqcn(): string
    {
        return Project::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        parent::configureCrud($crud);
        return $crud
            ->addFormTheme('admin/form.html.twig')
            //->setFormOptions(['attr' => ['novalidate' => 'novalidate']])
            ->setEntityLabelInSingular(ucfirst($this->route))
            ->setEntityLabelInPlural(ucfirst($this->route) . 's')
            ->setDefaultSort(['createdAt' => 'DESC'])
            ->setEntityPermission(Config::ROLE_ADMIN);
    }

    public function setFields(): iterable
    {
        yield IdField::new('id')->onlyOnDetail();

        yield FormField::addPanel()->setCssClass(Config::ADMIN_FORM_MAIN_CSS_CLASS);
        yield TextField::new('titleExtract', 'Title')->onlyOnIndex();
        yield TextField::new('title')->onlyOnForms();
        yield TextareaField::new('headline')->hideOnIndex();
        yield TextField::new('content')->setFormType(CKEditorType::class)->hideOnIndex();

        yield FormField::addPanel()->setCssClass(Config::ADMIN_FORM_SIDE_CSS_CLASS);
        yield SlugField::new('slug')
            ->setTargetFieldName('title')
            ->hideOnIndex();
        yield ImageField::new('thumbnail')
            ->setBasePath(Path::UPLOADS_PROJECTS_THUMB)
            ->onlyOnIndex()
            ->setSortable(false);
        yield TextField::new('thumbnailFile', 'Thumbnail')
            ->setFormType(VichImageType::class)
            // ->setFormTypeOption('required', true)
            ->onlyOnForms();
        yield CollectionField::new('gallery')
            ->setEntryType(GalleryItemType::class)
            ->setFormTypeOption('block_name', 'custom_gallery')
            ->onlyOnForms();
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
