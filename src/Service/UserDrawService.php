<?php

namespace App\Service;

use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;

class UserDrawService
{
    public function __construct(
        private readonly EntityManagerInterface $em,
        private readonly UserRepository $userRepository,
        private readonly EmailService $emailService,
    )
    {
    }

    public function drawUser(): void
    {
        $users = $this->userRepository->findAll();
        if (!$this->checkIfAllUsersSubmitted($users)) {
            return;
        }

        // 1. Shuffle the users
        shuffle($users);

        // 2. Assign each user to the next one
        $usersCount = count($users);
        /** @var User $user */
        foreach ($users as $i => $user) {
            $nextUser = $users[($i + 1) % $usersCount];
            $user->setDrawnUser($nextUser);
        }
        $this->em->flush();

        // 3. Send an email to each user with the name of the person they have to offer a gift to
        foreach ($users as $user) {
            $this->emailService->sendEmailDrawnUser($user);
        }
    }

    private function checkIfAllUsersSubmitted(array $users): bool
    {
        foreach ($users as $user) {
            if ($user->getSubmittedAt() === null) {
                return false;
            }
        }

        return true;
    }
}