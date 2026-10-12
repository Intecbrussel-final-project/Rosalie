<?php

declare(strict_types=1);

ini_set('display_errors', '1');

ini_set('display_startup_errors', '1');

error_reporting(E_ALL);

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