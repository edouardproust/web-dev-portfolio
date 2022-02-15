<?php

namespace App\Controller\Admin;

use App\Config;
use App\Entity\User;
use App\Service\EasyAdminService;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateField;
use EasyCorp\Bundle\EasyAdminBundle\Field\FormField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use App\Controller\Admin\AbstractEntityCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;

class UserCrudController extends AbstractEntityCrudController
{
    private $easyAdminService;

    public function __construct(
        EasyAdminService $easyAdminService
    ) {
        $this->easyAdminService = $easyAdminService;
    }

    public static function getEntityFqcn(): string
    {
        return User::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud->setEntityPermission('ROLE_ADMIN');
    }

    public function setFields(): array
    {
        $isCurrentUser = $this->easyAdminService->isCurrentUser($this->getUser());
        $fields = [
            IdField::new('id')
                ->onlyOnDetail(),

            FormField::addPanel()->setCssClass('col-md-8'),
            TextField::new('email'),
            DateField::new('createdAt')
                ->setLabel('Registered on')
                ->hideOnForm(),

            FormField::addPanel()->setCssClass('col-md-4'),
            'roles' => ChoiceField::new('roles')
                ->setChoices(Config::ROLES)
                ->allowMultipleChoices()
        ];
        if ($isCurrentUser) {
            /** @var ChoiceField $formField */
            $fields['roles']
                ->setDisabled(true)
                ->setHelp('Connected users can\'t update their own roles.');
        }
        return $fields;
    }

    public function configureActions(Actions $actions): Actions
    {
        $isAdmin = $this->easyAdminService->isAdmin();
        $actions
            ->remove(Crud::PAGE_INDEX, Action::DELETE)
            ->remove(Crud::PAGE_EDIT, Action::SAVE_AND_CONTINUE);
        if (!$isAdmin) {
            $actions
                ->add(Crud::PAGE_EDIT, Action::DELETE)
                ->reorder(Crud::PAGE_EDIT, [Action::SAVE_AND_RETURN, Action::DELETE]);
        }
        return $actions;
    }
}
