<?php

namespace App\Service;

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

    public function buildUser(array $data): User
    {
        $user = new User;
        return $user
            ->setEmail($data['email'])
            ->setCreatedAt(new \DateTime())
            ->setPassword($this->hasher->hashPassword($user, $data['password']));
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
}
