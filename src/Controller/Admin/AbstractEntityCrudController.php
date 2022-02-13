<?php

namespace App\Controller\Admin;

use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

abstract class AbstractEntityCrudController extends AbstractCrudController
{
    abstract public static function getEntityFqcn(): string;

    public function configureFields(string $pageName): iterable
    {
        $fields = $this->setFields();

        foreach ($fields as $field) {
            $field->setColumns(12);
        }
        return $fields;
    }

    abstract public function setFields(): array;

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

    protected function mergeFields(array $fields): array
    {
        foreach ($this->setAdditionalFields() as $field) {
            $position = $field->getAsDto()->getCustomOption('position');
            if (count($fields) <= $position) {
                $fields = array_merge($fields, [$field]);
            } elseif ($position <= 0) {
                $fields = array_merge([$field], $fields);
            } else {
                $start = array_slice($fields, 0, $position);
                $end = array_slice($fields, $position);
                $fields = array_merge($start, [$field], $end);
            }
        }
        return $fields;
    }
}
