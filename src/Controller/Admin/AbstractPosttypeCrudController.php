<?php

namespace App\Controller\Admin;

use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;

abstract class AbstractPosttypeCrudController extends AbstractEntityCrudController
{
    const MANDATORY_PROPERTIES_IN_CHILD = ['route'];

    abstract public static function getEntityFqcn(): string;

    abstract public function setFields(): array;

    public function __construct()
    {
        $this->checkProperties();
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInPlural(ucfirst($this->route) . 's')
            ->setDefaultSort(['createdAt' => 'DESC']);
    }

    public function configureActions(Actions $actions): Actions
    {
        $view = Action::new('VIEW', false, 'fa fa-eye')
            ->linkToRoute($this->route . '_show', function (object $entity) {
                return ['id' => $entity->getId(), 'slug' => $entity->getSlug()];
            });
        return $this->setActionsOnIndex($actions, $view);
        return $actions;
    }

    private function setActionsOnIndex(Actions $actions, Action ...$newActions): Actions
    {
        $reorder = [Action::EDIT, Action::DELETE];
        foreach ($newActions as $action) {
            $actions->add(Crud::PAGE_INDEX, $action);
            $reorder[] = $action->__toString();
        }
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
            )
            ->reorder(Crud::PAGE_INDEX, $reorder);
        return $actions;
    }

    private function checkProperties()
    {
        foreach (self::MANDATORY_PROPERTIES_IN_CHILD as $property) {
            if (!isset($this->$property)) {
                throw new  \Exception('Protected property $' . $property . ' must be declared in class ' . get_called_class());
            }
        }
    }
}
