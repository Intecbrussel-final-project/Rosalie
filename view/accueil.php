<?php
require_once __DIR__ . "/inc/header.php";

// Image du praliné : version Figma optimisée si elle existe, sinon la vignette de la carte
function pralineImage(string $mainImage): string
{
    $name = pathinfo($mainImage, PATHINFO_FILENAME);
    return file_exists(RACINE_PATH . "/public/images/figma/$name.webp")
        ? "images/figma/$name.webp"
        : "images/cards/$mainImage";
}
?>

<main>
     

    <section class="hero">
        <!--      <img class="hero_decoration" src="images/main-recipe-img/cocoa-branch.png" alt=""> -->
        <div class="container">
        
            <div class="hero-text">
                <h1 class="hero-title">
                    Nos créations,<br>
                    pour vos moments !
                </h1>
                <p class="hero-subtitle">Découvrez nos nouvelle recettes. Une création printanière inspirée des fruits
                    confits. Des saveurs délicates, imaginées pour surprendre.
                </p>
                <a class="hero-link" href="?page=recettes">Découvrez nos recttes</a>
            </div>
            <div class="hero-img">
                <img src="images/figma/orange.webp" width="600" height="647" alt="Praliné à l'orange glacé, décoré d'une feuille et d'un zeste confit">
            </div>
        </div>
    </section>
    <section class="top-recipes-section" aria-labelledby="top-recipes-title">
        <h2 class="creations-eyebrow" id="top-recipes-title">Nos meilleures recettes</h2>
        <p class="creations-subtitle">Les créations préférées de nos gourmands.</p>

        <?php if (empty($topRecipes)): ?>
            <p class="creations-empty">Nos recettes arrivent bientôt.</p>
        <?php else: ?>
            <ol class="top-recipes">
                <?php foreach ($topRecipes as $topRecipe): ?>
                    <li>
                        <a class="top-recipe-link" href="?page=recetteDetails&amp;slug=<?= urlencode($topRecipe->getSlug()) ?>">
                            <img src="<?= htmlspecialchars(pralineImage($topRecipe->getMainImage())) ?>"
                                alt="<?= htmlspecialchars($topRecipe->getTitle()) ?>" loading="lazy">
                            <span class="top-recipe-name"><?= htmlspecialchars($topRecipe->getTitle()) ?></span>
                        </a>
                        <?php if ($topRecipe->getAverageRating() !== null): ?>
                            <span class="stars" style="--rating: <?= $topRecipe->getAverageRating() ?>;" role="img"
                                aria-label="Note moyenne : <?= number_format($topRecipe->getAverageRating(), 1, ',', '') ?> sur 5 (<?= $topRecipe->getRatingsCount() ?> avis)"></span>
                        <?php endif; ?>
                    </li>
                <?php endforeach; ?>
            </ol>
        <?php endif; ?>
    </section>
     <section class="hero-video">
   <!--  <img class="video" src="/images/main-video.gif" alt="Description of the animation"> -->
    <video class="video" autoplay muted loop playsinline>

    <source src="/images/main-video.mp4" type="video/mp4">

</video>
    </section>
</main>
<?php
require_once __DIR__ . "/inc/footer.php";
?>
