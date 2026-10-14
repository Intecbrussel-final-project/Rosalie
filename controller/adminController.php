<?php

// Vérification des droits administrateur
if (
    !isset($_SESSION['user_id']) ||
    !isset($_SESSION['role']) ||
    $_SESSION['role'] !== 'admin'
) {
    http_response_code(403);
    exit('Accès interdit');
}

echo "Admin";
