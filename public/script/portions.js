const portions = document.querySelector('.portions');

const basePortions = Number(portions.dataset.basePortions);
let currentPortions = basePortions;

const portionCount = document.querySelector('#portion-count');
const plus = document.querySelector('#plus');
const minus = document.querySelector('#minus');

const ingredients = document.querySelectorAll('.ingredient-quantity');


function updateIngredients() {

    ingredients.forEach(ingredient => {

        // Quantité initiale de l'ingrédient
        const baseQuantity = Number(ingredient.dataset.baseQuantity);

        // Calcul de la nouvelle quantité selon le nombre de portions
        const newQuantity =
            baseQuantity * currentPortions / basePortions;

        // Mise à jour de la quantité avec un arrondi à une décimale
        ingredient.textContent = Math.round(newQuantity * 10) / 10;
    });

    // Mise à jour du nombre de portions
    portionCount.textContent = currentPortions;
}


plus.addEventListener('click', function () {

    // Augmenter le nombre de portions
    currentPortions++;

    updateIngredients();
});


minus.addEventListener('click', function () {

    // Empêcher d'avoir moins d'une portion
    if (currentPortions > 1) {
        currentPortions--;

        updateIngredients();
    }

});