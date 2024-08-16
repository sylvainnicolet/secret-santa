<?php

namespace App\Service;

use App\Entity\User;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

class EmailService
{
    public function __construct(
        private readonly MailerInterface $mailer,
    ) {
    }

    public function sendEmail(User $user): void
    {
        $email = (new Email())
            ->from('no-reply@example.com') // TODO: Change this email
            ->to($user->getEmail())
            ->subject('Remplis ta liste Noël !') // TODO: Change this subject
            ->text('Bonjour '.$user->getEmail().', remplis ta liste de Noël en cliquant sur ce lien : http://localhost:8000/liste') // TODO: Change this message
        ;

        $this->mailer->send($email);
    }
}
