<?php
require_once __DIR__ . "/inc/header.php";
?>
<main>
    <div class="container">
        <section class="recipe-description">
            <div class="recipe-grid">
                <div class="recipe-info">
                    <?php

                    $title = $recetteDetails->getTitle();
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
                        ["l’", "l'", "l"],
                        '',
                        $flavorClass
                    );
                    $flavorClass = trim($flavorClass);
                    ?>


                    <h2 class="recipe-title"> <?= htmlspecialchars($productType) ?></h2>
                    <h2 class="card-flavor <?= $typeClass ?> <?= $flavorClass ?>">
                        <?= htmlspecialchars($flavor) ?>
                    </h2>

                    <img class="recipe-inside-img"
                        src="images/cards/<?= htmlspecialchars($recetteDetails->getMainImage()) ?>"
                        alt="<?= htmlspecialchars($recetteDetails->getTitle()) ?>">
                </div>
                <div class="recipe-content">
                    <p class="recipe-text">
                        <?= htmlspecialchars($recetteDetails->getDescription()) ?>

                    </p>
                    <img class="recipe-chocolate-img" src="images/choco_melt.jpg" alt="Préparation du chocolat">
                </div>
                <div class="recipe-main">
                    <img class="recipe-img"
                        src="images/main-recipe-img/<?= htmlspecialchars($recetteDetails->getMainImage()) ?>"
                        alt="<?= htmlspecialchars($recetteDetails->getTitle()) ?>">
                </div>
            </div>

        </section>
    </div>

    <?php require __DIR__ . "/inc/reviews.php"; ?>

    <div class="container">

        <section class="recipe-description ">

            <div class="line">
                <h2 class="section-title">Les Ingrédients</h2>
                <img class="bowl-svg" src="images/svg/bowl.svg" alt="">
            </div>
            <div class="portions" data-base-portions="<?= $recetteDetails->getPortions() ?>">

                <button type="button" id="minus">−</button>

                <span id="portion-count">
                    <?= $recetteDetails->getPortions() ?>
                </span>

                <button type="button" id="plus">+</button>
                <p id="person-label">

<?= $recetteDetails->getPortions() == 1 ? 'Personne' : 'Personnes' ?>

</p>
            </div>
            <div class="ingredients">
                <?php foreach ($ingredients as $ingredient): ?>
                    <div class="card">
                        <img src="images/ingridients/<?= htmlspecialchars($ingredient->getImgIngredient()) ?>" alt="">

                        <p>
                            <?= htmlspecialchars($ingredient->getName()) ?>

                            <span class="ingredient-quantity"
                                data-base-quantity="<?= htmlspecialchars($ingredient->getQuantity()) ?>">
                                <?= htmlspecialchars($ingredient->getQuantity()) ?>
                            </span>

                            <?= htmlspecialchars($ingredient->getUnit()) ?>
                        </p>
                    </div>
                <?php endforeach; ?>
            </div>
        </section>
        <section class="recipe-description">
            <div class="line">
                <h2 class="section-title">La Préparation</h2>
                <img class="svg" src="images/svg/whisk.svg" alt="">
            </div>
            <div class="preparation">
                <?php foreach ($steps as $step): ?>
                    <div class="steps">
                        <p class="step-num"><?= htmlspecialchars($step->getStepNumber()) ?>
                            <?= htmlspecialchars($step->getTitle()) ?>
                        </p>
                        <p class="step-desc"><?= htmlspecialchars($step->getDescription()) ?></p>
                        <img src="images/steps/<?= $step->getImage() ?>" alt="<?= htmlspecialchars($step->getTitle()) ?>">

                    </div>
                <?php endforeach; ?>
            </div>
            <div class="recipe-details">
                <div class="recipe-detail">
                    <img src="images/svg/time.svg" alt="">
                    <p class="recipe-detail-desc">
                        <?= htmlspecialchars($recetteDetails->formatCookingTime($recetteDetails->getCookTimeMinutes())) ?>
                    </p>
                </div>
                <div class="recipe-detail">
                    <img src="images/svg/difficulty.svg" alt="">
                    <p class="recipe-detail-desc"><?= htmlspecialchars($recetteDetails->getDifficulty()) ?></p>
                </div>
                <div class="recipe-detail">
                    <img src="images/svg/money.svg" alt="">
                    <p class="recipe-detail-desc"><?= htmlspecialchars($recetteDetails->getDifficulty()) ?></p>
                </div>
            </div>
        </section>
    </div>
</main>
<?php
require_once __DIR__ . "/inc/footer.php";
?>