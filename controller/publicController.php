<?php
// chemin vers les dépendances
use model\manager\MailManager;
use model\manager\RecipeManager;
use model\manager\IngredientsManager;
use model\manager\StepsManager;
use model\manager\UserManager;
use model\manager\CommentManager;
use model\manager\RatingManager;
use model\manager\ContactManager;

use model\mapping\UserMapping;
use model\mapping\CommentMapping;
use model\mapping\ContactMapping;

$recipeManager = new RecipeManager($connectPDO);
$ingredientsManager = new IngredientsManager($connectPDO);
$stepsPrepManager=new StepsManager($connectPDO);
$userManager = new UserManager($connectPDO);
$commentManager = new CommentManager($connectPDO);
$ratingManager = new RatingManager($connectPDO);
$contactManager = new ContactManager($connectPDO);

$page = $_GET['page']?? 'accueil';

if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}
/* ================= LOGOUT ================= */
if ($page === 'logout' && $_SERVER['REQUEST_METHOD'] === 'POST') {
        # destruction des variables de sessions (réinitialisation du tableau $_SESSION)
    $_SESSION = [];
        # suppression du cookie
    if (ini_get('session.use_cookies')) {
        $params = session_get_cookie_params();
        setcookie(
            session_name(),
            '',
            time() - 42000,
            $params['path'],
            $params['domain'],
            $params['secure'],
            $params['httponly']
        );
    }
        # Destruction du fichier lié sur le serveur
    session_destroy();
    header('Location: ?page=accueil');
    exit;
}
/* ================= LOGIN================= */

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['login_submit'])) {

    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    $user = $userManager->getUserByEmail($email);

    if ($user && password_verify($password, $user['password_hash'])) {

        if ($user['verification_status'] !== 'verified') {
            $_SESSION['login_error'] = "Veuillez confirmer votre adresse e-mail avant de vous connecter.";
            $_SESSION['login_email'] = $email;
    
            header('Location: ?page=accueil');
            exit;
        }
    
        session_regenerate_id(true);
    
        $_SESSION['user_id'] = (int) $user['id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['role'] = $user['role'];
    
        header('Location: ?page=accueil');
        exit;
    
    } else {
    
        $_SESSION['login_error'] = "E-mail ou mot de passe incorrect.";
        $_SESSION['login_email'] = $email;
    
        header('Location: ?page=accueil');
        exit;
    }
}


if ($page === 'accueil') {

    $recipes = $recipeManager->getAllRecipes();
    $topRecipes = $recipeManager->getTopRecipes(3);
    require_once RACINE_PATH . '/view/accueil.php';

} elseif ($page === 'apropos') {
    require_once RACINE_PATH. '/view/apropos.php';
}
elseif ($page === 'contact') {

    $errors = [];
    $success = false;

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {

        $name = trim($_POST['name'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $subject = trim($_POST['subject'] ?? '');
        $message = trim($_POST['message'] ?? '');

        if ($name === '') {
            $errors[] = "Le nom est obligatoire.";
        }
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors[] = "L'adresse e-mail n'est pas valide.";
        }
        if ($subject === '') {
            $errors[] = "Le sujet est obligatoire.";
        }
        if ($message === '') {
            $errors[] = "Le message est obligatoire.";
        }
        if (empty($errors)) {

            $contact = new ContactMapping([
                'name' => $name,
                'email' => $email,
                'subject' => $subject,
                'message' => $message
            ]);
            if ($contactManager->createMessage($contact)) {
                $success = true;

                (new MailManager())->notifyNewContactMessage(
                    $userManager->getAdminEmails(),
                    $name,
                    $email,
                    $subject,
                    $message
                );
            }
        }
    }

    require_once RACINE_PATH . '/view/contact.php';
}
elseif ($page === 'recettes') {

    $recipes = $recipeManager->getAllRecipes();
    require RACINE_PATH . '/view/recettes.php';
    exit;
}
elseif ($page === 'inscription') {
    $errors = [];
    $success = false;

    // traitement formulaire
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $passwordConfirm = $_POST['password_confirm'] ?? '';
    if ($userManager->emailOrUsernameExists($email, $username)) {
        $errors[] = "Ces informations sont déjà utilisées dans le système.";
    }
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "L'adresse email n'est pas valide";
    }
    if (empty($username) || strlen($username) < 3 || strlen($username) > 50) {
        $errors[] = "Le nom d'utilisateur n'est pas valide";
    }
    if (empty($password) || strlen($password) < 8) {
        $errors[] = "Le mot de passe doit contenir au moins 8 caractères";
    }
    if ($password !== $passwordConfirm) {
        $errors[] = "Les mots de passe ne correspondent pas";
    }
    if (empty($errors)) {

    
        $passwordHash = password_hash($password, PASSWORD_DEFAULT);
        $user = new UserMapping([
            'username' => $username,
            'email' => $email,
            'password_hash' => $passwordHash,
            'generated_key' => ''
        ]);

        if ($userManager->createUser($user)) {
            $success = true;
            

            
            $mailManager = new MailManager();
            $mailManager->sendVerificationEmail(
                $email,
                $user->getGeneratedKey()
            );
            $mailManager->notifyNewUser($userManager->getAdminEmails(), $username, $email);
            
        }
        
    }
}
    require_once RACINE_PATH. '/view/inscription.php';
    }


    elseif ($page === 'verifyEmail') {

        $key = $_GET['key'] ?? '';
    
        if ($key === '') {
            $verificationError = "Lien de vérification invalide.";
        } else {
            $verified = $userManager->verifyEmail($key);
    
            if ($verified) {
                $verificationSuccess = "Votre adresse e-mail a été confirmée.";
            } else {
                $verificationError = "Lien invalide ou déjà utilisé.";
            }
        }
    
        require RACINE_PATH . '/view/verifyEmail.php';
        exit;
    }




elseif ($page === 'recetteDetails') {

    if (!isset($_GET['slug'])) {
        http_response_code(404);
        require RACINE_PATH . '/view/404.php';
        exit;
    }

    $recetteDetails = $recipeManager->getRecipeBySlug($_GET['slug']);

    if ($recetteDetails === null) {
        http_response_code(404);
        require RACINE_PATH . '/view/404.php';
        exit;
    }
        $id = $recetteDetails->getId();
        $ingredients = $ingredientsManager->getIngredientsByRecipeId($id);
        $steps = $stepsPrepManager->getStepsById($id);

    // ---------- Avis (note + commentaire) ----------
    // l'utilisateur connecté est attendu dans $_SESSION['user_id'] et $_SESSION['username']
    $currentUserId = isset($_SESSION['user_id']) ? (int) $_SESSION['user_id'] : null;

    $reviewErrors = [];
    $reviewValues = ['rating' => 0, 'message' => ''];
    $reviewSent = isset($_GET['avis']) && $_GET['avis'] === 'ok';

    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['review_submit'])) {
        $reviewValues['rating'] = (int) ($_POST['rating'] ?? 0);
        $reviewValues['message'] = trim($_POST['message'] ?? '');

        if (!hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'] ?? '')) {
            $reviewErrors[] = "La session a expiré, veuillez réessayer.";
        }
        if ($currentUserId === null) {
            $reviewErrors[] = "Vous devez être connecté pour laisser un avis.";
        }
        if ($reviewValues['rating'] < 1 || $reviewValues['rating'] > 5) {
            $reviewErrors[] = "Choisissez une note entre 1 et 5 étoiles.";
        }
        if ($reviewValues['message'] === '' || mb_strlen($reviewValues['message']) > 500) {
            $reviewErrors[] = "Votre commentaire est obligatoire (500 caractères maximum).";
        }

        if (empty($reviewErrors)) {
            $comment = new CommentMapping([
                'author_id' => $currentUserId,
                'recipe_id' => $id,
                'message' => $reviewValues['message'],
            ]);
            try {
                $connectPDO->beginTransaction();
                $ratingManager->saveRating($currentUserId, $id, $reviewValues['rating']);
                $commentManager->addComment($comment);
                $connectPDO->commit();
            } catch (Exception $e) {
                $connectPDO->rollBack();
                $reviewErrors[] = "Une erreur est survenue, votre avis n'a pas été enregistré.";
            }
        }

        if (empty($reviewErrors)) {
            (new MailManager())->notifyNewReview(
                $userManager->getAdminEmails(),
                $recetteDetails->getTitle(),
                $recetteDetails->getSlug(),
                $_SESSION['username'] ?? '',
                $reviewValues['rating'],
                $reviewValues['message']
            );

            // Post/Redirect/Get : évite le double envoi au rafraîchissement
            header('Location: ?page=recetteDetails&slug=' . urlencode($recetteDetails->getSlug()) . '&avis=ok#avis');
            exit;
        }
    }

    // pagination des commentaires : ?avis_page=N, ramené entre 1 et la dernière page
    $commentsPerPage = 6;
    $commentsTotal = $commentManager->countPublishedByRecipeId($id);
    $commentsPages = max(1, (int) ceil($commentsTotal / $commentsPerPage));
    $commentsPage = min(max(1, (int) ($_GET['avis_page'] ?? 1)), $commentsPages);

    $comments = $commentManager->getPublishedByRecipeId(
        $id,
        $commentsPerPage,
        ($commentsPage - 1) * $commentsPerPage
    );
    $ratingSummary = $ratingManager->getSummary($id);


    require RACINE_PATH . '/view/recetteDetails.php';
    exit;
  }
