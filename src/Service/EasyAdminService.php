<?php

namespace App\Service;

use App\Entity\User;
use Doctrine\ORM\QueryBuilder;
use App\Repository\UserRepository;
use App\Repository\AuthorRepository;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;

class EasyAdminService
{
    private $userRepository;
    private $authorRepository;

    public function __construct(
        UserRepository $userRepository,
        AuthorRepository $authorRepository
    ) {
        $this->userRepository = $userRepository;
        $this->authorRepository = $authorRepository;
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

    public function authorIsApprovedField()
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

    public function authorUserField()
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
}
