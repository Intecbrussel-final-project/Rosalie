<?php

declare(strict_types=1);

require_once RACINE_PATH . '/view/inc/header.php';
?>

<main class="verification-page">

    <?php if (isset($verificationSuccess)): ?>

        <section class="verification-card verification-card--success">

            <div class="verification-icon">✓</div>

            <h1 class="verification-title">
                <?= htmlspecialchars($verificationSuccess, ENT_QUOTES, 'UTF-8') ?>
            </h1>

            <p class="verification-text">
                Votre compte est maintenant activé.<br>
                Vous pouvez vous connecter.
            </p>

            <a class="verification-button" href="?page=accueil">
                Retour à l'accueil
            </a>

        </section>

    <?php elseif (isset($verificationError)): ?>

        <section class="verification-card verification-card--error">

            <div class="verification-icon">!</div>

            <h1 class="verification-title">
                <?= htmlspecialchars($verificationError, ENT_QUOTES, 'UTF-8') ?>
            </h1>

            <p class="verification-text">
                La vérification de votre adresse e-mail n'a pas pu être effectuée.
            </p>

            <a class="verification-button" href="?page=accueil">
                Retour à l'accueil
            </a>

        </section>

    <?php endif; ?>

</main>

<?php
require_once RACINE_PATH . '/view/inc/footer.php';
?>