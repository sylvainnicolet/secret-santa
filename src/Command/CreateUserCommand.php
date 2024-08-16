<?php

namespace App\Command;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'app:create:user',
    description: 'Add a short description for your command',
)]
class CreateUserCommand extends Command
{
    public function __construct(private readonly EntityManagerInterface $em)
    {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $email = $io->ask('Email');

        $user = new User();
        $token = bin2hex(random_bytes(32));

        $user->setEmail($email);
        $user->setToken($token);

        $this->em->persist($user);
        $this->em->flush();

        $io->success('User '.$email.' created successfully!');

        return Command::SUCCESS;
    }
}
