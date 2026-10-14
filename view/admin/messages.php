<?php
require_once RACINE_PATH . '/view/inc/header.php';
?>

<main class="admin-main admin-messages">
    <div class="container">

        <div class="admin-messages-head">
            <div>
                <h1 class="admin-title admin-messages-title">Messages de contact</h1>
                <p class="admin-messages-count">
                    <?php if ($unreadContactCount === 1): ?>
                        1 nouveau message non lu
                    <?php elseif ($unreadContactCount > 1): ?>
                        <?= $unreadContactCount ?> nouveaux messages non lus
                    <?php else: ?>
                        Aucun nouveau message
                    <?php endif; ?>
                </p>
            </div>

            <?php if ($unreadContactCount > 0): ?>
                <form method="post" action="?pg=admin&amp;page=messages">
                    <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['csrf_token']) ?>">
                    <button class="admin-btn admin-btn-edit" type="submit" name="mark_all_read" value="1">Tout marquer comme lu</button>
                </form>
            <?php endif; ?>
        </div>

        <?php if (empty($messages)): ?>
            <p class="admin-empty">Aucun message reçu pour le moment.</p>
        <?php else: ?>
            <ul class="admin-messages-list">
                <?php foreach ($messages as $contactMessage): ?>
                    <?php $replyLink = 'mailto:' . $contactMessage->getEmail() . '?subject=' . rawurlencode('Re: ' . $contactMessage->getSubject()); ?>
                    <li class="admin-message<?= $contactMessage->getIsRead() ? '' : ' is-unread' ?>">
                        <div class="admin-message-head">
                            <?php if (!$contactMessage->getIsRead()): ?>
                                <span class="admin-message-badge">Nouveau</span>
                            <?php endif; ?>
                            <h2 class="admin-message-subject"><?= htmlspecialchars($contactMessage->getSubject()) ?></h2>
                            <time class="admin-message-date" datetime="<?= htmlspecialchars((string) $contactMessage->getCreatedAt()) ?>">
                                <?= date('d/m/Y à H:i', strtotime((string) $contactMessage->getCreatedAt())) ?>
                            </time>
                        </div>

                        <p class="admin-message-from">
                            <?= htmlspecialchars($contactMessage->getName()) ?> ·
                            <a href="mailto:<?= htmlspecialchars($contactMessage->getEmail()) ?>"><?= htmlspecialchars($contactMessage->getEmail()) ?></a>
                        </p>

                        <p class="admin-message-body"><?= nl2br(htmlspecialchars($contactMessage->getMessage())) ?></p>

                        <div class="admin-message-actions">
                            <a class="admin-btn admin-btn-edit" href="<?= htmlspecialchars($replyLink) ?>">Répondre</a>
                            <?php if (!$contactMessage->getIsRead()): ?>
                                <form method="post" action="?pg=admin&amp;page=messages">
                                    <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['csrf_token']) ?>">
                                    <button class="admin-btn admin-btn-edit" type="submit" name="mark_read"
                                        value="<?= (int) $contactMessage->getId() ?>">Marquer comme lu</button>
                                </form>
                            <?php endif; ?>
                        </div>
                    </li>
                <?php endforeach; ?>
            </ul>
        <?php endif; ?>

    </div>
</main>

<?php
require_once RACINE_PATH . '/view/inc/footer.php';
?>
