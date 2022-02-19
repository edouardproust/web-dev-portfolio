<?php

namespace App\Service;

use App\Config;
use App\Entity\User;
use Doctrine\ORM\QueryBuilder;
use App\Repository\UserRepository;
use App\Repository\AuthorRepository;
use Symfony\Component\Security\Core\Security;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;

class EasyAdminService
{
    private $userRepository;
    private $authorRepository;
    private $security;

    public function __construct(
        UserRepository $userRepository,
        AuthorRepository $authorRepository,
        Security $security
    ) {
        $this->userRepository = $userRepository;
        $this->authorRepository = $authorRepository;
        $this->security = $security;
    }

    /**
     * @param User $user
     * @return bool
     */
    public function isCurrentUser(User $user): bool
    {
        $isCurrentUser = false;
        if (!empty($_GET['entityId'])) {
            $currentUser = $this->userRepository->find($_GET['entityId']);
            if ($user === $currentUser) {
                $isCurrentUser = true;
            }
        }
        return $isCurrentUser;
    }

    /** @return bool  */
    public function isAdmin()
    {
        $isAdmin = false;
        if (!empty($_GET['entityId'])) {
            $user = $this->userRepository->find($_GET['entityId']);
            if (in_array('ROLE_ADMIN', $user->getRoles())) {
                $isAdmin = true;
            }
        }
        return $isAdmin;
    }

    public function authorIsApprovedField(): BooleanField
    {
        $isApproveField = BooleanField::new('isApproved')
            ->setLabel('Approve this author')
            ->setHelp('You must approve this author for him to be able to 
            connect and write blogposts and lessons. As soon as you approve the author, 
            a confirmation email will be send to him containing his credentials.')
            ->onlyOnIndex()
            ->setSortable(false);
        if (isset($_GET['entityId'])) {
            $author = $this->authorRepository->find($_GET['entityId']);
            if ($author && !$author->getIsApproved()) {
                $isApproveField->onlyWhenUpdating();
            }
        }
        if (isset($_GET['crudAction']) && $_GET['crudAction'] === Action::INDEX) {
            $isApproveField
                ->setDisabled(true)
                ->setLabel('');
        }
        return $isApproveField;
    }

    public function authorUserField(): AssociationField
    {
        return AssociationField::new('user')
            ->hideWhenUpdating()
            ->setQueryBuilder(function (QueryBuilder $builder) {
                return $builder
                    ->select('u')
                    ->from(User::class, 'u')
                    ->where('u.isAuthor IS NULL');
            });
    }

    public function userPasswordField(): ?TextField
    {
        $entityId = !empty($_GET['entityId']) ? $_GET['entityId'] : null;

        $textField = TextField::new('password')
            ->setFormType(PasswordType::class)
            ->onlyOnDetail();

        /** @var User $currentUser */
        $currentUser = $this->security->getUser();
        if ($entityId == $currentUser->getId()) {
            return $textField->onlyWhenUpdating();
        }
        return $textField;
    }

    public function userRolesField(): ChoiceField
    {
        $isCurrentUser = $this->isCurrentUser($this->security->getUser());

        $rolesField = ChoiceField::new('roles')
            ->setChoices(Config::ROLES)
            ->allowMultipleChoices();
        if ($isCurrentUser) {
            $rolesField
                ->setDisabled(true)
                ->setHelp('This field is disabled: connected user can\'t update their own roles.');
        }
        return $rolesField;
    }
}
