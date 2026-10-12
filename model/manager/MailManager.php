<?php

declare(strict_types=1);

namespace model\manager;

use Symfony\Component\Mailer\Mailer;
use Symfony\Component\Mailer\Transport\Smtp\EsmtpTransport;
use Symfony\Component\Mime\Email;

class MailManager
{
    private Mailer $mailer;
    private string $senderEmail;

    public function __construct()
    {
        $this->senderEmail = MAILER_EMAIL;

        $transport = new EsmtpTransport(
            'smtp.gmail.com',
            587,
            false
        );

        $transport->setUsername(MAILER_EMAIL);
        $transport->setPassword(MAILER_APP_PASSWORD);

        $this->mailer = new Mailer($transport);
    }

    public function sendVerificationEmail(
        string $email,
        string $generatedKey
    ): void {

        $verificationLink =
            'http://localhost/'
            . '?page=verifyEmail&key='
            . urlencode($generatedKey);

        $message = (new Email())
            ->from($this->senderEmail)
            ->to($email)
            ->subject('Confirmez votre adresse e-mail')
            ->html(
                '<h2>Bienvenue chez Maison Rosalie !</h2>
                <p>Veuillez confirmer votre adresse e-mail.</p>
                <p>
                    <a href="' . htmlspecialchars($verificationLink, ENT_QUOTES, 'UTF-8') . '">
                        Confirmer mon adresse e-mail
                    </a>
                </p>'
            );

        $this->mailer->send($message);
    }
}