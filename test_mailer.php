<?php

declare(strict_types=1);

require_once 'config.php';
require_once RACINE_PATH . '/vendor/autoload.php';

use Symfony\Component\Mailer\Transport;

echo "Avant Transport<br>";

$dsn = sprintf(
    'smtp://%s:%s@smtp.gmail.com:587',
    rawurlencode(MAILER_EMAIL),
    rawurlencode(MAILER_APP_PASSWORD)
);

echo "DSN créé<br>";

$transport = Transport::fromDsn($dsn);

echo "Transport créé !<br>";