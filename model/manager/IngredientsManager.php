<?php
//model/manager/IngredientsManager.php
declare(strict_types=1);
namespace model\manager;
use PDO;
use model\abstract\AbstractManager;
use model\mapping\IngredientsMapping;

class IngredientsManager extends AbstractManager{

   
    public function getIngredientsByRecipeId(int $id): array
    {
        $sql = "SELECT i.*, ri.*
                FROM recipe_ingredients AS ri
                JOIN ingredients AS i ON i.id = ri.ingredient_id
                WHERE ri.recipe_id = :id";
    
        $stmt = $this->connect->prepare($sql);
        $stmt->execute(['id' => $id]);
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $ingredients = [];
        foreach ($rows as $row) {
            $ingredients[] = new IngredientsMapping($row);
        }

        return $ingredients;
    }

}