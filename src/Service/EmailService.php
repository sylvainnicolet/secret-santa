<?php

namespace App\Service;

use App\Entity\User;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;
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
        $url = 'https://santa.hescsen.com/form/'.$user->getToken();

        $email = (new Email())
            ->from(new Address('info@hescsen.com', 'Les lutins de la chocolaterie'))
            ->to($user->getEmail())
            ->subject('Remplis ta liste Noël ! 🎅🎄')
            ->html('<p>Salut '.$user->getFirstname().', tu es invité.e à passer le réveillon de Noël le 24 au soir chez les lutins de la chocolaterie.</p>
                <p>Clique sur ce lien pour en savoir plus : <a href="'.$url.'">En savoir plus</a></p>')
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
            ->from(new Address('info@hescsen.com', 'Les lutins de la chocolaterie'))
            ->to($user->getEmail())
            ->subject('Le tirage au sort de Noël a eu lieu ! 🎅🎄')
            ->html('
                <p>Salut ' . $user->getFirstname() . ',</p>
                <p>Voici le nom de ton lutin secret : ' . $drawnUser->getFirstname() . '</p>
                <p>Choisis un cadeau parmi ses 3 souhaits :</p>
                <ul>
                    <li>' . $drawnUser->getChoice1() . '</li>
                    <li>' . $drawnUser->getChoice2() . '</li>
                    <li>' . $drawnUser->getChoice3() . '</li>
                </ul>
                <p>PS : Garde le secret ! 🤫🎄</p>
                <p>Les lutins de la chocolaterie se réjouissent de te voir le 24 au soir pour la distribution des cadeaux.</p>
            ');

        $this->mailer->send($email);
    }
}
