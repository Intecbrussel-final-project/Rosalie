<?php

declare(strict_types=1);

namespace model\manager;

use Symfony\Component\Mailer\Mailer;
use Symfony\Component\Mailer\Transport;
use Symfony\Component\Mime\Email;
use Throwable;

class MailManager
{
    private ?Mailer $mailer = null;
    private string $senderEmail;
    private string $logFile;

    public function __construct()
    {
        $this->senderEmail = MAILER_EMAIL;
        $this->logFile = RACINE_PATH . '/mail_debug.log';

        $this->log('INIT', 'Initialisation du MailManager');

        try {
            // urlencode() aussi sur le mot de passe: indispensable si celui-ci
            // contient @, :, /, #, ?, etc.
            $dsn = sprintf(
                'smtp://%s:%s@smtp.webdev-cf2m.be:587?encryption=tls&verify_peer=0',
                urlencode(MAILER_EMAIL),
                urlencode(MAILER_PASSWORD)
            );

            $transport = Transport::fromDsn($dsn);
            $this->mailer = new Mailer($transport);

            $this->log('INIT_OK', 'Transport SMTP créé: smtp.webdev-cf2m.be:587 / TLS');
        } catch (Throwable $e) {
            $this->logException('INIT_ERROR', $e);
        }
    }

    public function sendVerificationEmail(string $email, string $generatedKey): void
    {
        if ($this->mailer === null) {
            $this->log('SEND_ABORTED', 'Mailer non initialisé. Destinataire: ' . $email);
            return;
        }

        $verificationLink =
            $this->siteUrl()
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

        $this->log('SEND_START', 'Tentative d’envoi vers: ' . $email);

        try {
            $this->mailer->send($message);
            $this->log('SEND_OK', 'E-mail accepté par le transport SMTP pour: ' . $email);
        } catch (Throwable $e) {
            $this->logException('SEND_ERROR', $e);
        }
    }

    private function log(string $type, string $message): void
    {
        $line = sprintf(
            "[%s] [%s] %s%s",
            date('Y-m-d H:i:s'),
            $type,
            $message,
            PHP_EOL
        );

        @file_put_contents($this->logFile, $line, FILE_APPEND | LOCK_EX);
    }

    private function logException(string $type, Throwable $e): void
    {
        $this->log(
            $type,
            sprintf(
                "%s: %s | file=%s:%d",
                get_class($e),
                $e->getMessage(),
                $e->getFile(),
                $e->getLine()
            )
        );
    }


    /*
     * Notification à l'administrateur : un visiteur a écrit via le formulaire de contact.
     * Le Reply-To permet de répondre au visiteur directement depuis la boîte mail.
     */
    public function sendContactNotification(
        string $name,
        string $email,
        string $subject,
        string $content
    ): bool {
        $adminLink = $this->siteUrl() . '?pg=admin&page=messages';

        $message = (new Email())
            ->from($this->senderEmail)
            ->to($this->adminEmail())
            ->replyTo($email)
            ->subject('Nouveau message de contact : ' . $this->singleLine($subject))
            ->text(
                "Nouveau message depuis Maison Rosalie\n\n" .
                "Nom : {$name}\n" .
                "E-mail : {$email}\n" .
                "Sujet : {$subject}\n\n" .
                "Message :\n{$content}\n\n" .
                "Voir les messages : {$adminLink}"
            )
            ->html($this->layout(
                'Nouveau message de contact',
                '<p>Un visiteur vient de vous écrire depuis le formulaire de contact.</p>'
                . $this->recap([
                    'Nom' => $name,
                    'E-mail' => $email,
                    'Sujet' => $subject,
                    'Message' => $content,
                ])
                . $this->button('Voir les messages', $adminLink)
                . '<p style="margin:0;color:#927F6E;font-size:13px;">Répondez directement à cet e-mail pour écrire à '
                . $this->e($name) . '.</p>'
            ));

        return $this->deliver($message, 'CONTACT', $email);
    }

    /*
     * Accusé de réception envoyé au visiteur qui a écrit via le formulaire de contact.
     */
    public function sendContactConfirmation(
        string $name,
        string $email,
        string $subject,
        string $content
    ): bool {
        $message = (new Email())
            ->from($this->senderEmail)
            ->to($email)
            ->replyTo($this->adminEmail())
            ->subject('Nous avons bien reçu votre message – Maison Rosalie')
            ->text(
                "Bonjour {$name},\n\n" .
                "Merci d'avoir contacté Maison Rosalie ! Nous avons bien reçu votre message " .
                "et nous vous répondrons dans les plus brefs délais.\n\n" .
                "Rappel de votre message\n" .
                "Sujet : {$subject}\n\n" .
                "{$content}\n\n" .
                "À très bientôt,\nL'équipe Maison Rosalie"
            )
            ->html($this->layout(
                'Merci pour votre message !',
                '<p>Bonjour ' . $this->e($name) . ',</p>'
                . '<p>Merci d’avoir contacté Maison Rosalie ! Nous avons bien reçu votre message '
                . 'et nous vous répondrons dans les plus brefs délais.</p>'
                . '<p style="margin:24px 0 8px;color:#ED6E00;font-size:13px;text-transform:uppercase;">Rappel de votre message</p>'
                . $this->recap([
                    'Sujet' => $subject,
                    'Message' => $content,
                ])
                . '<p style="margin:0;">À très bientôt,<br>L’équipe Maison Rosalie</p>'
            ));

        return $this->deliver($message, 'CONTACT_CONFIRM', $email);
    }

    // envoi commun aux e-mails de contact, avec journalisation dans mail_debug.log
    private function deliver(Email $message, string $type, string $about): bool
    {
        if ($this->mailer === null) {
            $this->log($type . '_ABORTED', 'Mailer non initialisé. Message de: ' . $about);
            return false;
        }

        $this->log($type . '_SEND_START', 'Tentative d’envoi pour le message de: ' . $about);

        try {
            $this->mailer->send($message);
            $this->log($type . '_SEND_OK', 'E-mail envoyé pour le message de: ' . $about);
            return true;
        } catch (Throwable $e) {
            $this->logException($type . '_SEND_ERROR', $e);
            return false;
        }
    }

    // destinataire des notifications : ADMIN_EMAIL (config.php) s'il est renseigné, sinon l'adresse d'envoi
    private function adminEmail(): string
    {
        return defined('ADMIN_EMAIL') && ADMIN_EMAIL !== '' ? ADMIN_EMAIL : $this->senderEmail;
    }

    // racine du site pour les liens des e-mails : SITE_URL (config.php) si défini
    private function siteUrl(): string
    {
        return defined('SITE_URL') && SITE_URL !== '' ? rtrim(SITE_URL, '/') . '/' : 'http://localhost/';
    }

    private function e(string $value): string
    {
        return htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
    }

    // un sujet d'e-mail tient sur une seule ligne
    private function singleLine(string $value): string
    {
        return trim((string) preg_replace('/\s+/u', ' ', $value));
    }

    // gabarit HTML commun (styles en ligne pour les clients mail)
    private function layout(string $title, string $body): string
    {
        return '<div style="margin:0;padding:32px 16px;background:#FAF3E3;">'
            . '<div style="max-width:560px;margin:0 auto;padding:32px;background:#ffffff;border-radius:12px;'
            . 'color:#432205;font-family:Arial,Helvetica,sans-serif;font-size:15px;line-height:1.6;">'
            . '<p style="margin:0 0 8px;color:#ED6E00;font-size:12px;letter-spacing:.12em;text-transform:uppercase;">Maison Rosalie</p>'
            . '<h1 style="margin:0 0 20px;font-family:Georgia,serif;font-size:24px;font-weight:normal;">'
            . $this->e($title) . '</h1>'
            . $body
            . '</div></div>';
    }

    // tableau récapitulatif du message (libellé => valeur saisie par le visiteur)
    private function recap(array $rows): string
    {
        $html = '<table role="presentation" style="width:100%;margin:0 0 24px;border-collapse:collapse;">';
        foreach ($rows as $label => $value) {
            $html .= '<tr>'
                . '<th style="width:90px;padding:10px 12px 10px 0;border-top:1px solid #EFE6D6;'
                . 'color:#927F6E;font-weight:normal;text-align:left;vertical-align:top;">' . $this->e($label) . '</th>'
                . '<td style="padding:10px 0;border-top:1px solid #EFE6D6;word-break:break-word;">'
                . nl2br($this->e($value)) . '</td>'
                . '</tr>';
        }
        return $html . '</table>';
    }

    private function button(string $label, string $url): string
    {
        return '<p style="margin:0 0 24px;">'
            . '<a href="' . $this->e($url) . '" style="display:inline-block;padding:10px 24px;border-radius:20px;'
            . 'background:#432205;color:#ffffff;text-decoration:none;font-size:14px;letter-spacing:.04em;text-transform:uppercase;">'
            . $this->e($label) . '</a></p>';
    }

}
