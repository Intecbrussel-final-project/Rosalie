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
            . '/?page=verifyEmail&key='
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

    // adresse du dossier public/ du site, ex. https://2026.webdev-cf2m.be/choco2/public
    // SITE_URL (config.php) si elle est définie, sinon déduite de la requête en cours
    private function siteUrl(): string
    {
        if (defined('SITE_URL')) {
            return rtrim(SITE_URL, '/');
        }

        $https = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off')
            || ($_SERVER['HTTP_X_FORWARDED_PROTO'] ?? '') === 'https'
            || ($_SERVER['SERVER_PORT'] ?? '') == 443;
        $host = $_SERVER['HTTP_HOST'] ?? 'localhost';
        $dir = rtrim(str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'] ?? '/')), '/');

        return ($https ? 'https' : 'http') . '://' . $host . $dir;
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
}
