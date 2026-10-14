<?php
require_once __DIR__ . "/inc/header.php";
?>

<main class="register">
    <h1 class="register-title">Créer un compte</h1>
    <?php if (!empty($errors)): ?>
        <ul class="register-errors">
            <?php foreach ($errors as $error): ?>
                <li><?= htmlspecialchars($error) ?></li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>

    <form class="register-form" method="post" action="?page=inscription">

        <div class="field" id="f-name">
            <label for="register-username">Nom d'utilisateur</label>

            <input class="js-input-name" type="text" id="register-username" name="username" maxlength="50"
                value="<?= $success ? '' : htmlspecialchars($_POST['username'] ?? '') ?>" required>

            <div class="msg">Au moins 3 caractères</div>
        </div>


        <div class="field" id="f-email">
            <label for="register-email">E-mail</label>

            <input class="js-input-email" type="email" id="register-email" name="email" maxlength="254"
                value="<?= $success ? '' : htmlspecialchars($_POST['email'] ?? '') ?>" required>

            <div class="msg">Respectez le format mail</div>
        </div>


        <div class="field" id="f-pwd">
            <label for="register-password">Mot de passe</label>

            <input class="js-input-pwd" type="password" id="register-password" name="password" required>

            <div class="msg">
                Au moins 8 caractères, une majuscule, une minuscule, un chiffre et un caractère spécial
            </div>
        </div>


        <div class="field" id="f-confirm">
            <label for="register-confirm">Confirmer le mot de passe</label>

            <input class="js-input-confirm" type="password" id="register-confirm" name="password_confirm" required>

            <div class="msg">Les mots de passe ne correspondent pas</div>
        </div>


        <button class="register-submit" type="submit">
            Créer mon compte
        </button>

    </form>
</main>
<?php if ($success): ?>
    <div class="modal-overlay">
        <div class="modal-box">
            <button type="button" class="modal-close" aria-label="Fermer" data-modal-close>
                &times;
            </button>
            <h2>Compte créé !</h2>

            <p class="modal-info">
                Un e-mail de confirmation a été envoyé à
                <strong>
                    <?= htmlspecialchars($_POST['email'] ?? '') ?>
                </strong>
                <br>
                Cliquez sur le lien reçu pour activer votre compte.
            </p>

            <p class="modal-spam-hint">
                Vous ne trouvez pas l’e-mail ? Vérifiez vos spams.
            </p>

        </div>
    </div>
<?php endif; ?>
<?php
require_once __DIR__ . "/inc/footer.php";
?>