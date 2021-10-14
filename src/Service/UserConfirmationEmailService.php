<?php

namespace App\Service;

use App\Entity\User;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

class UserConfirmationEmailService
{
    public function __construct(
        private MailerInterface $mailer
    ){}

    public function sendConfirmationEmail(User $user): void
    {
        $confirmationUrl = sprintf('http://127.0.0.1:8000/confirmation/%s', $user->getConfirmationHash());

        $email = (new Email())
            ->from('system@finances.com')
            ->to($user->getEmail())
            ->subject('Email confirmation')
            ->text($confirmationUrl)
            ->html('<p>'.$confirmationUrl.'</p>');

        $this->mailer->send($email);
    }
}