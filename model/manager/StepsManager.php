<?php
//model/manager/RecipeManager.php
declare(strict_types=1);
namespace model\manager;
use PDO;
use model\abstract\AbstractManager;
use model\mapping\StepsMapping;

class StepsManager extends AbstractManager{

    public function getStepsById(int $id):array{
        $sql = "SELECT s.title, s.description, s.image, s.step_number FROM recipes AS r JOIN steps s ON s.recipe_id=r.id WHERE r.id=:id";
        $stmt = $this->connect->prepare($sql);
        $stmt->execute(['id' => $id]);
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        // var_dump($rows);
        $steps = [];
        foreach ($rows as $row) {
            $steps[] = new StepsMapping($row);
        } 
        // var_dump($steps);
    
        return $steps;
       
    }

}