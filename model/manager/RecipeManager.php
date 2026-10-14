<?php
//model/manager/RecipeManager.php
declare(strict_types=1);
namespace model\manager;
use PDO;
use model\abstract\AbstractManager;
use model\mapping\RecipeMapping;

class RecipeManager extends AbstractManager{

    public function getAllRecipes():array{
        $sql = "SELECT * FROM recipes";
        $stmt = $this->connect->prepare($sql);
        $stmt->execute();
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $recipes = [];
        foreach ($rows as $row) {
            $recipes[] = new RecipeMapping($row);
        }
    
        return $recipes;
    }

    // les meilleures recettes : moyenne des notes la plus haute d'abord,
    // puis le nombre de notes ; sans note, les plus récentes passent en dernier
    public function getTopRecipes(int $limit = 3): array
    {
        $sql = "SELECT r.*, s.average_rating, COALESCE(s.ratings_count, 0) AS ratings_count
                FROM recipes AS r
                LEFT JOIN (
                    SELECT recipe_id, ROUND(AVG(rating), 1) AS average_rating, COUNT(*) AS ratings_count
                    FROM ratings
                    GROUP BY recipe_id
                ) AS s ON s.recipe_id = r.id
                ORDER BY s.average_rating IS NULL, s.average_rating DESC, ratings_count DESC, r.created_at DESC
                LIMIT :limit";
        $stmt = $this->connect->prepare($sql);
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();

        $recipes = [];
        foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $row) {
            $recipes[] = new RecipeMapping($row);
        }
        return $recipes;
    }

    public function getRecipeById(int $id): ?RecipeMapping
    {
        $sql = 'SELECT * FROM recipes WHERE id = :id LIMIT 1';
    
        $query = $this->connect->prepare($sql);
        $query->execute(['id' => $id]);
    
        $row = $query->fetch();
    
        if ($row === false) {
            return null;
        }
    
        return new RecipeMapping($row);
    } 

    // Nous recherchons une recette via son slug
public function getRecipeBySlug(string $slug):?RecipeMapping{

    $sql = "SELECT *
            FROM recipes
            WHERE slug = ?";

    $stmt = $this->connect->prepare($sql);
    $stmt->execute([$slug]);
    $recipe = $stmt->fetch(PDO::FETCH_ASSOC);

    if (empty($recipe)) {
        return null;
    }
    return new RecipeMapping($recipe);
}


}