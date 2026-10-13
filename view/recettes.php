<?php
require_once __DIR__ . "/inc/header.php";
?>
<main>

    <div class="container">
        <!-- <h1 class="apropos_title">Recettes de Maison Rosalie</h1> -->

        <section class="recipes">


            <?php foreach ($recipes as $recipe): ?>
                <div class="card-recipe">

                    <?php
                    $title = $recipe->getTitle();
                    if (str_starts_with($title, 'La tablette')) {
                        $productType = 'La tablette';
                        $typeClass = 'tablette';
                        $flavor = trim(str_replace('La tablette', '', $title));
                    } else {
                        $productType = 'La Praline';
                        $typeClass = 'praline';
                        $flavor = trim(str_replace('La Praline', '', $title));
                    }

                    $flavorClass = strtolower($flavor);
                    $flavorClass = str_replace(
                        ["l’", "l'", "l�"],
                        '',
                        $flavorClass
                    );
                    $flavorClass = trim($flavorClass);
                    ?>
                    <div class="card-heading">
                        <h3 class="card-title"><?= htmlspecialchars($productType) ?></h3>
                        <p class="card-flavor <?= $typeClass ?> <?= $flavorClass ?>">
                            <?= htmlspecialchars($flavor) ?>
                        </p>
                    </div>
                    <a class="card-link" href="?page=recetteDetails&slug=<?= urlencode($recipe->getSlug()) ?>">
                        <img class="card-img" src="images/cards/<?= htmlspecialchars($recipe->getMainImage()) ?>"
                            alt="<?= htmlspecialchars($title) ?>">
                        <span class="card-arrow" aria-hidden="true">↑</span>
                    </a>
                </div>
            <?php endforeach; ?>
        </section>
    </div>

</main>
<?php
require_once __DIR__ . "/inc/footer.php";
?>