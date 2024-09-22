<?php

namespace App\Command;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\PasswordHasher\Hasher\PasswordHasherFactoryInterface;

#[AsCommand(
    name: 'app:create:admin',
    description: 'Create a new admi',
)]
class CreateUserCommand extends Command
{
    public function __construct(
        private readonly EntityManagerInterface $em,
        private readonly PasswordHasherFactoryInterface $passwordHasherFactory
    ) {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->setDescription('Create a new admin')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $username = $io->ask('Username');
        $password = $io->askHidden('Password');

        $user = new User();

        $encoder = $this->passwordHasherFactory->getPasswordHasher($user);
        $password = $encoder->hash($password);

        $user->setUsername($username);
        $user->setPassword($password);
        $user->setRoles([User::ROLE_ADMIN]);

        $this->em->persist($user);
        $this->em->flush();

        $io->success('Admin ['.$username.'] created successfully!');

        return Command::SUCCESS;
    }
}
