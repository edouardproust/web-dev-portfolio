<?php

namespace App\Controller\Admin;

use App\Entity\Author;
use App\Service\EasyAdminService;
use Doctrine\ORM\QueryBuilder;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;

abstract class AbstractPosttypeCrudController extends AbstractEntityCrudController
{
    const MANDATORY_PROPERTIES_IN_CHILD = ['route'];

    protected $route;
    protected $easyAdminService;

    abstract public static function getEntityFqcn(): string;

    abstract public function setFields(): iterable;

    public function __construct(EasyAdminService $easyAdminService)
    {
        $this->checkProperties();
        $this->easyAdminService = $easyAdminService;
    }

    public function configureActions(Actions $actions): Actions
    {
        parent::configureActions($actions);
        $view = Action::new('VIEW', false, 'fa fa-eye')
            ->linkToRoute($this->route . '_show', function (object $entity) {
                return ['id' => $entity->getId(), 'slug' => $entity->getSlug()];
            })
            ->setHtmlAttributes(['target' => '_blank'])
            ->setCssClass('btn btn-light admin-crud-row-btn');

        return $actions
            ->add(Crud::PAGE_INDEX, $view)
            ->add(Crud::PAGE_EDIT, $view)
            ->remove(Crud::PAGE_NEW, Action::SAVE_AND_ADD_ANOTHER)
            ->reorder(Crud::PAGE_INDEX, [Action::EDIT, 'VIEW', Action::DELETE])
            ->reorder(Crud::PAGE_EDIT, [Action::SAVE_AND_RETURN, Action::SAVE_AND_CONTINUE, 'VIEW'])
        ;
    }

    protected function associationFieldAuthor(): AssociationField
    {
        return AssociationField::new('author')
            ->hideWhenCreating()
            ->hideOnIndex()
            ->setQueryBuilder(function (QueryBuilder $builder) {
                return $builder
                    ->select('a')
                    ->from(Author::class, 'a')
                    ->where('a.isApproved = 1');
            });
    }

    private function checkProperties()
    {
        foreach (self::MANDATORY_PROPERTIES_IN_CHILD as $property) {
            if (!isset($this->$property)) {
                throw new \Exception(
                    'Protected property $' . $property . ' must be declared in class ' . get_called_class()
                );
            }
        }
    }
}
