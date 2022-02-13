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

    /** 
     * Add fields to the ones set by default
     * - For a Category entity, please refer to this child class: 
     * App\Controller\Admin\AbstractCategoryCrudController
     * - For a PostType entity, refer to:
     * App\Controller\Admin\AbstractPosttypeCrudController
     * - To set the position of a new field: 
     * TextareaField::new('description')->setCustomOption('position', 3)
     * - The optionName must be 'position', followed by the index (int)
     * @return array Array of EasyAdmin Field objects (eg. TextField, SlugField) 
     */
    public function setAdditionalFields(): array
    {
        return [];
    }

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
        foreach ($this->setAdditionalFields() as $field) {
            $position = $field->getAsDto()->getCustomOption('position');
            if (count($fields) <= $position) {
                echo 'if / ';
                $fields = array_merge($fields, [$field]);
            } elseif ($position <= 0) {
                echo 'elseif / ';
                $fields = array_merge([$field], $fields);
            } else {
                $start = array_slice($fields, 0, $position);
                $end = array_slice($fields, $position);
                $fields = array_merge($start, [$field], $end);
                dump('after', $fields);
            }
        }
        return $fields;
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
