<?php

declare(strict_types=1);

require_once RACINE_PATH . '/view/inc/header.php';
?>

<main>
    <?php if (isset($verificationSuccess)): ?>

        <h1>
            <?= htmlspecialchars($verificationSuccess, ENT_QUOTES, 'UTF-8') ?>
        </h1>

        <p>
            Votre compte est maintenant activé.
            Vous pouvez vous connecter.
        </p>

        <a href="?page=accueil">
            Retour à l'accueil
        </a>

    <?php elseif (isset($verificationError)): ?>

        <h1>
            <?= htmlspecialchars($verificationError, ENT_QUOTES, 'UTF-8') ?>
        </h1>

        <a href="?page=accueil">
            Retour à l'accueil
        </a>

    <?php endif; ?>
</main>

<?php
require_once RACINE_PATH . '/view/inc/footer.php';
?>