<?php

namespace App\Service;

use App\Config;
use App\Entity\User;
use Doctrine\ORM\QueryBuilder;
use App\Repository\UserRepository;
use App\Repository\AuthorRepository;
use App\Repository\AdminOptionRepository;
use Symfony\Component\Security\Core\Security;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Field\UrlField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\EmailField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use EasyCorp\Bundle\EasyAdminBundle\Contracts\Field\FieldInterface;

class EasyAdminService
{
    private $userRepository;
    private $authorRepository;
    private $security;
    private $adminOptionRepository;

    public function __construct(
        UserRepository $userRepository,
        AuthorRepository $authorRepository,
        Security $security,
        AdminOptionRepository $adminOptionRepository
    ) {
        $this->userRepository = $userRepository;
        $this->authorRepository = $authorRepository;
        $this->security = $security;
        $this->adminOptionRepository = $adminOptionRepository;
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
                ->setLabel('<i class="fas fa-user-check"></i>');
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

    public function adminOptionValueField()
    {
        $adminOption = $this->adminOptionRepository->find($_GET['entityId']);
        $type = $adminOption->getType();
        $label = $adminOption->getLabel();
        $allowedTypes = [
            'text' => TextField::class,
            'email' => EmailField::class,
            'boolean' => BooleanField::class,
            'number' => IntegerField::class,
            'url' => UrlField::class
        ];
        foreach ($allowedTypes as $fieldType => $fieldClass) {
            if ($type === $fieldType) {
                $valueField = $fieldClass::new('value', $label);
                if ($type = 'boolean') {
                    $valueField = $fieldClass::new('isActive', $label);
                }
            }
        }
        return $valueField
            ->onlyOnForms()
            ->setSortable(false);
    }
}
