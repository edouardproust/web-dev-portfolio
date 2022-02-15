<?php

namespace App\Controller\Admin;

use App\Entity\AdminOption;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use App\Controller\Admin\AbstractEntityCrudController;
use App\Repository\AdminOptionRepository;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Provider\AdminContextProvider;

class AdminOptionCrudController extends AbstractEntityCrudController
{

    private $adminOptionRepository;

    public function __construct(AdminOptionRepository $adminOptionRepository)
    {
        $this->adminOptionRepository = $adminOptionRepository;
    }

    public static function getEntityFqcn(): string
    {
        return AdminOption::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        $entityLabelSingular = 'Option';
        if (!empty($_GET["entityId"])) {
            /** @var AdminOption */
            $option = $this->adminOptionRepository->findOneBy(
                ['id' => $_GET["entityId"]]
            );
            $entityLabelSingular = $option->getLabel();
        }
        return $crud
            ->setEntityLabelInPlural('Options')
            ->setEntityLabelInSingular($entityLabelSingular)
            ->setEntityPermission('ROLE_ADMIN');
    }

    public function setFields(): array
    {
        return [
            IdField::new('id')
                ->onlyOnDetail(),
            TextField::new('label')
                ->hideOnForm()
                ->setLabel('Option'),
            TextareaField::new('value')
                ->setSortable(false),
        ];
    }

    public function configureActions(Actions $actions): Actions
    {
        return $actions
            ->remove(Crud::PAGE_INDEX, Action::DELETE)
            ->remove(Crud::PAGE_INDEX, Action::NEW);
    }
}
