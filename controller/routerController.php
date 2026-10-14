<?php
// path: controller/routerController.php


# Connexion PDO
try {
    $connectPDO = new PDO(
        DB_TYPE . ':host=' . DB_HOST . ';port=' . DB_PORT . ';dbname=' . DB_NAME . ';charset=' . DB_CHARSET,
        DB_LOGIN,
        DB_PWD,
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        ]
    );
} catch (Exception $e) {
    // en local (WAMP) l'erreur reste affichée ; en ligne elle va dans le log du serveur,
    // pour ne pas montrer l'hôte ou l'utilisateur de la base aux visiteurs
    error_log('Connexion à la base impossible : ' . $e->getMessage());
    http_response_code(500);
    die(ini_get('display_errors') ? $e->getMessage() : 'Le site est momentanément indisponible. Merci de réessayer plus tard.');
}

// si nous sommes connecté en tant qu'administrateur et que nous avons cliqué sur la partie administration
if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin' && isset($_GET['pg']) && $_GET['pg'] === 'admin') {
    // on charge le contrôleur admin
    require_once RACINE_PATH . "/controller/adminController.php";
}else{
    // on charge le contrôleur public
    require_once RACINE_PATH . "/controller/publicController.php";
}
// Bonne pratique
$connectPDO = null;

