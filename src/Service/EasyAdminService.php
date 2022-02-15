<?php

namespace App\Service;

use App\Entity\User;
use App\Repository\UserRepository;

class EasyAdminService
{

    private $urlGenerator;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
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
}
