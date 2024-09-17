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

    public function sendEmailDoYourList(User $user): void
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

    public function sendEmailDrawnUser(User $user): void
    {
        $drawnUser = $user->getDrawnUser();
        if ($drawnUser === null) {
            return;
        }

        $email = (new Email())
            ->from('no-reply@example.com') // TODO: Change this email
            ->to($user->getEmail())
            ->subject('Le tirage au sort de Noël a eu lieu !') // TODO: Change this subject
            ->html('
                <p>Salut ' . $user->getFirstname() . ',</p>
                <p>Tu dois offrir un cadeau à ' . $drawnUser->getFirstname() . '.</p>
                <p>Voici sa liste de Noël :</p>
                <ul>
                    <li>' . $drawnUser->getChoice1() . '</li>
                    <li>' . $drawnUser->getChoice2() . '</li>
                    <li>' . $drawnUser->getChoice3() . '</li>
                </ul>
                <p>À bientôt !</p>
            ');

        $this->mailer->send($email);
    }
}
