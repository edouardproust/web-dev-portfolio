<?php

namespace App\Service;

use App\Config;
use App\Entity\User;
use App\Helper\FileHelper;
use App\Path;
use Doctrine\ORM\QueryBuilder;
use App\Repository\UserRepository;
use App\Repository\AuthorRepository;
use App\Repository\AdminOptionRepository;
use Symfony\Component\Security\Core\Security;
use Vich\UploaderBundle\Form\Type\VichImageType;
use Symfony\Component\Validator\Constraints\File;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Field\UrlField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\EmailField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\HiddenField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

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

    /**
     * Generate a dynamic label for Crud controller
     *
     * @param bool $dynamicLabel Do you want the label to auto-generate based on the entity label or title?
     * Default: FALSE
     * @param null|string $fallbackLabel The non-dynamic label to display if dynamicLabel is on FALSE.
     * Default: NULL
     * @param null|ServiceEntityRepository $repository If dynamicLabel on TRUE: the Entity Repository.
     * Default: NULL
     * @param null|string $getterFn If dynamicLabel on TRUE: the getter function. Default: getLabel'
     * @return null|string The entity label
     */
    public function getEntityLabelSingular(
        bool $dynamicLabel = false,
        ?string $fallbackLabel = null,
        ?ServiceEntityRepository $repository = null,
        ?string $getterFn = 'getLabel'
    ): ?string {
        $labelSingular = $fallbackLabel;
        if ($dynamicLabel) {
            if (!empty($_GET["entityId"])) {
                /** @var AdminOption */
                $entity = $repository->findOneBy(['id' => $_GET["entityId"]]);
                $labelSingular = $entity->$getterFn();
            }
        }
        return $labelSingular;
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

        $textField = TextField::new('password', "New password")
            ->setFormType(PasswordType::class)
            ->onlyOnForms();

        if ($_GET['crudAction'] === Action::EDIT) {
            $textField->setRequired(false);
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
        if (!empty($_GET['entityId'])) {
            $adminOption = $this->adminOptionRepository->find($_GET['entityId']);
            $type = $adminOption->getType();
            // Label is hidden beacause it the same as page title (see AdminOptionCrudController::configureCrud)
            $label = $adminOption->getHelp();
            $allowedTypes = [
                Config::FIELD_TEXT => TextField::class,
                Config::FIELD_EMAIL => EmailField::class,
                Config::FIELD_BOOL => BooleanField::class,
                Config::FIELD_NUM => IntegerField::class,
                Config::FIELD_URL => UrlField::class
            ];
            foreach ($allowedTypes as $fieldType => $fieldClass) {
                if ($type === $fieldType) {
                    if ($type === Config::FIELD_BOOL) {
                        $valueField = $fieldClass::new('isActive', $label);
                    } elseif ($adminOption->getIsUploadable()) {
                        $valueField = TextField::new('fileFile', $label)
                            ->setFormType(VichImageType::class)
                            ->setFormTypeOption('constraints', [
                                    new File(['mimeTypes' => FileHelper::getMimeTypes('IMAGE_TYPE')])
                            ]);
                    } else {
                        $valueField = $fieldClass::new('value', $label);
                    }
                }
            }
            if ($adminOption->getIsRequired()) {
                $valueField->setRequired(true);
            }
            return $valueField
                ->onlyOnForms()
                ->setSortable(false);
        }
        return HiddenField::new('id')->onlyOnDetail();
    }

    public function thumbnailFileField()
    {
        $thumbnailField = TextField::new('thumbnailFile', 'Thumbnail')
            ->setFormType(VichImageType::class)
            ->setFormTypeOption('constraints', [
                new File(['mimeTypes' => FileHelper::getMimeTypes('IMAGE_TYPE')])
            ])
            ->onlyOnForms();
        if ($_GET['crudAction'] === Action::NEW) {
            $thumbnailField->setRequired(true);
        }
        if ($_GET['crudAction'] === Action::EDIT) {
            $thumbnailField->setFormTypeOption('row_attr', ['class' => 'vich-no-delete']);
        }
        return $thumbnailField;
    }

    public function isAdminPanelAccessGranted(): bool
    {
        $isGranted = false;
        $user = $this->security->getUser();
        
        if (!$user) {
            return false;
        }
        foreach ($user->getRoles() as $role) {
            // check if is admin or author
            if (in_array($role, Config::EASY_ADMIN_ROLES)) {
                $isGranted = true;
                // if is author: check if is approved
                if (!in_array(Config::ROLE_ADMIN, $user->getRoles())) { // if is not admin (= if is an author)
                    $author = $this->authorRepository->findOneByUser($user);
                    if (!$author->getIsApproved()) {
                        $isGranted = false;
                    }
                }
            }
        }
        return $isGranted;
    }
}
