<?php

namespace App\Service;

use App\Entity\User;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class EmailService
{
    public function __construct(
        private readonly MailerInterface $mailer,
        private readonly UrlGeneratorInterface $urlGenerator,
    ) {
    }

    public function sendEmail(User $user): void
    {
        $url = $this->urlGenerator->generate(
            'user_form',
            ['token' => $user->getToken()],
            UrlGeneratorInterface::ABSOLUTE_URL
        );

        $email = (new Email())
            ->from('no-reply@example.com') // TODO: Change this email
            ->to($user->getEmail())
            ->subject('Remplis ta liste Noël !') // TODO: Change this subject
            ->text('Salut '.$user->getFirstname().', remplis ta liste de Noël en cliquant sur ce lien : '.$url)
        ;

        $this->mailer->send($email);
    }
}
