<?php

namespace App\Command;

use App\Repository\UserRepository;
use App\Service\EmailService;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'app:send:emails',
    description: 'Send emails to users',
)]
class SendEmailsCommand extends Command
{
    public function __construct(
        private readonly UserRepository $userRepository,
        private readonly EmailService $emailService,
    ) {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $users = $this->userRepository->findAll();
        foreach ($users as $user) {
            $this->emailService->sendEmail($user);
            $io->success('Email successfully sent to '.$user->getEmail());
        }

        return Command::SUCCESS;
    }
}
