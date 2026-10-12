<?php

declare(strict_types=1);

require_once 'config.php';
require_once RACINE_PATH . '/vendor/autoload.php';
require_once RACINE_PATH . '/model/manager/MailManager.php';

use model\manager\MailManager;

echo "1. Création MailManager...\n";

try {

    $mailManager = new MailManager();

    echo "2. MailManager créé.\n";
    echo "3. Envoi de l'e-mail...\n";

    $mailManager->sendVerificationEmail(
        MAILER_EMAIL,
        'test-verification-key-123'
    );

    echo "4. Email envoyé !\n";

} catch (Throwable $e) {

    echo "ERREUR:\n";
    echo $e::class . "\n";
    echo $e->getMessage() . "\n";
}