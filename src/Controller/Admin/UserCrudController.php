<?php

namespace App\Controller\Admin;

use App\Config;
use App\Entity\User;
use App\Service\EasyAdminService;
use Symfony\Component\Security\Core\Security;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateField;
use EasyCorp\Bundle\EasyAdminBundle\Field\FormField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use App\Controller\Admin\AbstractEntityCrudController;

class UserCrudController extends AbstractEntityCrudController
{
    private $easyAdminService;
    private $currentUserId;

    public function __construct(
        EasyAdminService $easyAdminService,
        Security $security
    ) {
        $this->easyAdminService = $easyAdminService;
        /** @var User $user */
        $user = $security->getUser();
        $this->currentUserId = $user->getId();
        parent::__construct();
    }

    public static function getEntityFqcn(): string
    {
        return User::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        parent::configureCrud($crud);
        $crud
            ->setEntityLabelInPlural('Users')
            ->setDefaultSort(['createdAt' => 'ASC']);
        // permission
        if ($this->entityId && $this->entityId !== $this->currentUserId) {
            $crud->setEntityPermission(Config::ROLE_ADMIN);
        }
        // page title
        if ($this->currentUserId === $this->entityId) {
            $crud->setPageTitle(Crud::PAGE_EDIT, 'My Account');
        }
        return $crud;
    }

    public function setFields(): iterable
    {
        yield IdField::new('id')->onlyOnDetail();

        yield FormField::addPanel()->setCssClass(Config::ADMIN_FORM_MAIN_CSS_CLASS);
        yield TextField::new('email');
        yield $this->easyAdminService->userPasswordField();

        yield FormField::addPanel()->setCssClass(Config::ADMIN_FORM_SIDE_CSS_CLASS);
        yield $this->easyAdminService->userRolesField();
        yield DateField::new('createdAt', 'Registered on')->hideOnForm();
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
        // On Author panel only: if current user is editing his own account
        if ($this->entityId && $this->entityId === $this->currentUserId) {
            $actions
                ->remove(Crud::PAGE_EDIT, Action::SAVE_AND_RETURN)
                ->add(Crud::PAGE_EDIT, Action::SAVE_AND_CONTINUE);
            if (!$isAdmin) {
                $actions->remove(Crud::PAGE_EDIT, Action::DELETE);
            }
        }
        return $actions;
    }
}
