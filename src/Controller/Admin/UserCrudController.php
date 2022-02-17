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
use Symfony\Component\Form\Extension\Core\Type\PasswordType;

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
        $crud
            ->setEntityPermission('ROLE_ADMIN')
            ->setEntityLabelInPlural('Users');
        // page title
        /** @var User $currentUser */
        $currentUser = $this->getUser();
        $entityId = !empty($_GET['entityId']) ? $_GET['entityId'] : null;
        if ($currentUser->getId() == $entityId) {
            $crud->setPageTitle(Crud::PAGE_EDIT, 'My Account');
        }
        return $crud;
    }

    public function setFields(): iterable
    {
        $isCurrentUser = $this->easyAdminService->isCurrentUser($this->getUser());
        yield IdField::new('id')->onlyOnDetail();
        yield DateField::new('createdAt')
            ->setLabel('Registered on')
            ->hideOnForm();

        // row 1
        yield FormField::addPanel()->setCssClass(Config::ADMIN_FORM_MAIN_CSS_CLASS);
        yield TextField::new('email');
        // password (only for current user)
        $entityId = !empty($_GET['entityId']) ? $_GET['entityId'] : null;
        /** @var User $currentUser */
        $currentUser = $this->getUser();
        if ($entityId == $currentUser->getId()) {
            yield TextField::new('password')
                ->setFormType(PasswordType::class)
                ->onlyWhenUpdating();
        }

        // row 2
        yield FormField::addPanel()->setCssClass(Config::ADMIN_FORM_SIDE_CSS_CLASS);
        $rolesField = ChoiceField::new('roles')
            ->setChoices(Config::ROLES)
            ->allowMultipleChoices();
        if ($isCurrentUser) {
            $rolesField
                ->setDisabled(true)
                ->setHelp('This field is disabled: connected user can\'t update their own roles.');
        }
        yield $rolesField;
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
