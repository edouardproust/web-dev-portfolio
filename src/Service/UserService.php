<?php

namespace App\Service;

use App\Config;
use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserService
{
    private $hasher;
    private $userRepository;
    private $entityManager;
    private $flash;

    public function __construct(
        UserPasswordHasherInterface $hasher,
        UserRepository $userRepository,
        EntityManagerInterface $entityManager,
        FlashBagInterface $flash
    ) {
        $this->hasher = $hasher;
        $this->userRepository = $userRepository;
        $this->entityManager = $entityManager;
        $this->flash = $flash;
    }

    public function buildUser($data): User
    {
        $email = is_array($data) ? $data['email'] : $data->email ?? null;
        $password = is_array($data) ? $data['password'] : $data->password ?? null;
        $user = new User;
        return $user
            ->setEmail($email)
            ->setCreatedAt(new \DateTime())
            ->setPassword($this->hasher->hashPassword($user, $password));
    }

    /**
     * @param User $user
     * @return bool TRUE on success / FALSE on failure
     */
    public function persistUser(User $user): bool
    {
        // check if username is unique
        $isUniqueUsername = true;
        foreach ($this->userRepository->findAll() as $existingUser) {
            if ($existingUser->getEmail() === $user->getEmail()) {
                $isUniqueUsername = false;
            }
        }
        // persist or show flash error
        if ($isUniqueUsername) {
            $this->entityManager->persist($user);
            $this->entityManager->flush();
            return true;
        } else {
            $this->flash->add('danger', 'This email is already taken.');
            return false;
        }
    }

    public function getHighestRole(?User $user, bool $humanVersion = false, bool $capitalizeOutput = false): ?string
    {
        if (!$user) {
            return null;
        }
        $roles = $user->getRoles();
        $role = null;
        if (in_array(Config::ROLE_ADMIN, $roles)) {
            $role = $humanVersion ? 'admin' : Config::ROLE_ADMIN;
        } elseif (in_array(Config::ROLE_AUTHOR, $roles)) {
            $role = $humanVersion ? 'author' : Config::ROLE_AUTHOR;
        }
        if ($role && $capitalizeOutput) {
            $role = ucfirst($role);
        }
        return $role;
    }
}
