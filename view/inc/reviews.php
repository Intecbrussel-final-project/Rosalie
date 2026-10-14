<?php
// view/inc/reviews.php — section « Avis De Gourmands » de la page recette
// variables fournies par le contrôleur : $comments, $ratingSummary, $reviewErrors,
// $reviewValues, $reviewSent, $currentUserId, $recetteDetails

$average = $ratingSummary['average'];
$total = $ratingSummary['total'];
$isLogged = $currentUserId !== null;
?>
<section class="reviews" id="avis">
    <div class="container reviews-top">

        <div class="reviews-summary">
            <h2 class="reviews-title">Avis De Gourmands</h2>
            <p class="reviews-subtitle">Vos Commentaires Nous Touchent<br>Et Nous Inspirent</p>

            <div class="reviews-score">
                <?php if ($total > 0): ?>
                    <span class="reviews-average"><?= number_format($average, 1, ',', '') ?></span>
                    <span class="stars stars-lg" style="--rating: <?= $average ?>;"
                        role="img" aria-label="Note moyenne : <?= number_format($average, 1, ',', '') ?> sur 5"></span>
                <?php else: ?>
                    <span class="reviews-average">–</span>
                    <span class="stars stars-lg" style="--rating: 0;" aria-hidden="true"></span>
                <?php endif; ?>
            </div>
            <p class="reviews-count">
                <?= $total > 0 ? 'Basé sur ' . $total . ' avis' : 'Aucun avis pour le moment' ?>
            </p>
        </div>

        <div class="review-card">
            <?php if ($isLogged): ?>
                <form class="review-form" method="post"
                    action="?page=recetteDetails&amp;slug=<?= urlencode($recetteDetails->getSlug()) ?>#avis">
                    <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['csrf_token']) ?>">

                    <div class="review-form-head">
                        <svg class="review-pen" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <path d="M17 3a2.8 2.8 0 0 1 4 4L8 20l-5 1 1-5z" />
                            <path d="m15 5 4 4" />
                        </svg>
                        <h3 class="review-form-title">Laisser un avis</h3>

                        <fieldset class="star-input">
                            <legend class="sr-only">Votre note</legend>
                            <?php for ($i = 5; $i >= 1; $i--): ?>
                                <input type="radio" id="star-<?= $i ?>" name="rating" value="<?= $i ?>"
                                    <?= $reviewValues['rating'] === $i ? 'checked' : '' ?> required>
                                <label for="star-<?= $i ?>" title="<?= $i ?> étoile<?= $i > 1 ? 's' : '' ?>">
                                    <span class="sr-only"><?= $i ?> étoile<?= $i > 1 ? 's' : '' ?></span>
                                </label>
                            <?php endfor; ?>
                        </fieldset>
                    </div>

                    <div class="review-form-body">
                        <p class="review-form-hint">Partagez votre expérience et aidez d'autres gourmands à découvrir cette recette !</p>
                        <div class="review-form-field">
                            <label class="sr-only" for="review-message">Votre commentaire</label>
                            <textarea id="review-message" name="message" maxlength="500" rows="3"
                                placeholder="Votre commentaire…" required><?= htmlspecialchars($reviewValues['message']) ?></textarea>
                            <button class="review-submit" type="submit" name="review_submit" value="1">Publier</button>
                        </div>
                    </div>

                    <?php if ($reviewSent): ?>
                        <p class="review-alert review-alert--success" role="status">Merci ! Votre avis a bien été publié.</p>
                    <?php endif; ?>
                    <?php if (!empty($reviewErrors)): ?>
                        <ul class="review-alert review-alert--error" role="alert">
                            <?php foreach ($reviewErrors as $error): ?>
                                <li><?= htmlspecialchars($error) ?></li>
                            <?php endforeach; ?>
                        </ul>
                        
                    <?php endif; ?>
                </form>
            <?php else: ?>
                <div class="review-form-head">
                    <svg class="review-pen" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                        stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <path d="M17 3a2.8 2.8 0 0 1 4 4L8 20l-5 1 1-5z" />
                        <path d="m15 5 4 4" />
                    </svg>
                    <h3 class="review-form-title">Laisser un avis</h3>
                </div>
                <div class="review-login">
                    <p class="review-form-hint">Connectez-vous pour partager votre expérience et aider d'autres gourmands à découvrir cette recette !</p>
                    <button class="review-submit" type="button" data-open-login>Se connecter</button>
                </div>
            <?php endif; ?>
        </div>
    </div>

       <div class="container">
        <?php if (empty($comments)): ?>
            <p class="reviews-empty">Soyez le premier à donner votre avis sur cette recette.</p>
        <?php else: ?>
            <ul class="review-list">
                <?php foreach ($comments as $comment): ?>
                    <li class="review-item">
                        <div class="review-item-head">
                            <p class="review-author"><?= htmlspecialchars($comment->getUsername()) ?></p>
                            <time class="review-date" datetime="<?= htmlspecialchars((string) $comment->getCreatedAt()) ?>">
                                <?= htmlspecialchars($comment->formatDate()) ?>
                            </time>
                        </div>
                        <?php if ($comment->getRating() !== null): ?>
                            <span class="stars" style="--rating: <?= $comment->getRating() ?>;"
                                role="img" aria-label="<?= $comment->getRating() ?> sur 5"></span>
                        <?php endif; ?>
                        <p class="review-message"><?= nl2br(htmlspecialchars($comment->getMessage())) ?></p>
                    </li>
                <?php endforeach; ?>
            </ul>

            <?php if ($totalPages > 1):
                $baseUrl = '?page=recetteDetails&slug=' . urlencode($recetteDetails->getSlug()) . '&avis_page=';
            ?>
                <nav class="reviews-pagination" aria-label="Pagination des avis">
                    <?php if ($currentPage > 1): ?>
                        <a href="<?= htmlspecialchars($baseUrl . ($currentPage - 1)) ?>#avis" rel="prev">‹ Précédent</a>
                    <?php endif; ?>

                    <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                        <?php if ($i === $currentPage): ?>
                            <span aria-current="page"><?= $i ?></span>
                        <?php else: ?>
                            <a href="<?= htmlspecialchars($baseUrl . $i) ?>#avis"><?= $i ?></a>
                        <?php endif; ?>
                    <?php endfor; ?>

                    <?php if ($currentPage < $totalPages): ?>
                        <a href="<?= htmlspecialchars($baseUrl . ($currentPage + 1)) ?>#avis" rel="next">Suivant ›</a>
                    <?php endif; ?>
                </nav>
            <?php endif; ?>
        <?php endif; ?>
    </div>
</section>
