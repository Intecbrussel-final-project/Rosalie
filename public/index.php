<?php
declare(strict_types=1);
putenv('OBJC_DISABLE_INITIALIZE_FORK_SAFETY=YES');

// cookie de session inaccessible au JavaScript, et envoyé seulement en HTTPS quand le site l'est
$isHttps = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off')
    || ($_SERVER['HTTP_X_FORWARDED_PROTO'] ?? '') === 'https';
session_set_cookie_params([
    'httponly' => true,
    'samesite' => 'Lax',
    'secure' => $isHttps,
]);
session_start();

require_once '../config.php';
require_once RACINE_PATH . '/vendor/autoload.php';

spl_autoload_register(function ($class) {

    $class = str_replace('\\', '/', $class);
    $file = RACINE_PATH . '/' . $class . '.php';

    if (file_exists($file)) {
        require_once $file;
    }
});

require_once RACINE_PATH . '/controller/routerController.php';