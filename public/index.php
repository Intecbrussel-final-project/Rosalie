<?php
declare(strict_types=1);
putenv('OBJC_DISABLE_INITIALIZE_FORK_SAFETY=YES');

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