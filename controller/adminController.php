<?php
// chemin vers les dépendances
use model\manager\ContactManager;

// Vérification des droits administrateur
if (
    !isset($_SESSION['user_id']) ||
    !isset($_SESSION['role']) ||
    $_SESSION['role'] !== 'admin'
) {
    http_response_code(403);
    exit('Accès interdit');
}

$contactManager = new ContactManager($connectPDO);

$page = $_GET['page'] ?? 'messages';

/* ================= MESSAGES DE CONTACT (notifications) ================= */
if ($page === 'messages') {

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {

        if (hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'] ?? '')) {
            if (isset($_POST['mark_all_read'])) {
                $contactManager->markAllAsRead();
            } elseif (isset($_POST['mark_read'])) {
                $contactManager->markAsRead((int) $_POST['mark_read']);
            }
        }

        // Post/Redirect/Get : le badge du header est recalculé au rechargement
        header('Location: ?pg=admin&page=messages');
        exit;
    }

    $messages = $contactManager->getAllMessages();
    require_once RACINE_PATH . '/view/admin/messages.php';

} else {
    http_response_code(404);
    require RACINE_PATH . '/view/404.php';
}
