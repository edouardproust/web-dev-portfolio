<?php

namespace App\Controller\Admin;

use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Field\FormField;
use EasyCorp\Bundle\EasyAdminBundle\Field\SlugField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use App\Controller\Admin\AbstractEntityCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;

abstract class AbstractCategoryCrudController extends AbstractEntityCrudController
{

    abstract public static function getEntityFqcn(): string;

    public function setFields(): array
    {
        $fields = [
            IdField::new('id')
                ->onlyOnDetail(),

            FormField::addPanel()->setCssClass('col-md-8'),
            TextField::new('label'),
            FormField::addPanel()->setCssClass('col-md-4'),
            SlugField::new('slug')
                ->setTargetFieldName('label')
                ->hideOnIndex(),
        ];
        return $this->mergeFields($fields);
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInPlural('Categories')
            ->setEntityLabelInSingular('Category')
            ->setDefaultSort(['label' => 'ASC']);
    }

    public function configureActions(Actions $actions): Actions
    {
        $actions
            ->update(
                Crud::PAGE_INDEX,
                Action::EDIT,
                fn (Action $action) => $action->setIcon('fa fa-edit')->setLabel(false)
            )
            ->update(
                Crud::PAGE_INDEX,
                Action::DELETE,
                fn (Action $action) => $action->setIcon('fa fa-trash')->setLabel(false)
            );
        return $actions;
    }
}
