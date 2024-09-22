<?php

namespace App\Factory;

use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\PasswordHasher\Hasher\PasswordHasherFactoryInterface;

class UserFactory
{
    public function __construct(
        private readonly EntityManagerInterface $em,
        private readonly PasswordHasherFactoryInterface $passwordHasherFactory,
        private readonly UserRepository $userRepository,
    ) {
    }

    public function generateUser(User $user): User
    {
        $username = $this->generateUsername($user);
        $planPassword = $this->generatePassword();

        $encoder = $this->passwordHasherFactory->getPasswordHasher($user);
        $password = $encoder->hash($planPassword);

        $user->setUsername($username);
        $user->setPlainPassword($planPassword);
        $user->setPassword($password);
        $user->setRoles([User::ROLE_USER]);
        $user->setEnable(true);

        $this->em->persist($user);
        $this->em->flush();

        return $user;
    }

    private function generateUsername(User $user): string
    {
        do {
            // Username must be the 4 first letters of the lastname and the first letter of the firstname
            $username = strtoupper(substr($user->getLastName(), 0, 4).substr($user->getFirstName(), 0, 1));

            // Add 2 random numbers at the end of the username
            $username .= random_int(10, 99);
        } while ($this->userRepository->findOneBy(['username' => $username]));

        return $username;
    }

    private function generatePassword(): string
    {
        // Password must be 6 characters long with upper letters and numbers
        return substr(str_shuffle('ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789'), 0, 6);
    }
}
