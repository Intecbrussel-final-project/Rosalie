<?php
// model/mapping/RecipeMapping.php

declare(strict_types=1);
namespace model\mapping;
use Exception;
use model\abstract\AbstractMapping;

class RecipeMapping extends AbstractMapping
{
    private ?int $id = null;
    private string $title = '';
    private string $slug = '';
    private string $description = '';
    private string $main_image = '';
    private  ?int $prep_time_minutes =null;
    private ?int $cook_time_minutes = null;
    private string $difficulty = '';
    private string $budget = '';
    // rempli seulement par RecipeManager::getTopRecipes()
    private ?float $average_rating = null;
    private int $ratings_count = 0;
    private int $portions = 0;
    // getters and setters
    public function getId():?int
    {
        return $this->id;
    }
    public function setId(?int $id): void
    {
        if($id<=0) throw new Exception("id doit être un entier positif");
        $this->id = $id;
    }
    public function getTitle(): string
    {
        return $this->title;
    }
    public function setTitle(string $title): void
{
    $title = trim(strip_tags($title));
    if (mb_strlen($title) < 3 || mb_strlen($title) > 120) {
        throw new Exception("Le titre de la recette doit faire entre 3 et 120 caractères");
    }
    $this->title = $title;
}
    public function getSlug(): string
    {
        return $this->slug;
    }
    public function setSlug(string $slug): void
    {
       $slug = strip_tags(trim($slug));
if(mb_strlen($slug)<3 || mb_strlen($slug)>170){
    throw new Exception("Le slug de la recette doit faire entre 3 et 170 caractères");
}
        $this->slug = $slug;
    }
    public function getDescription(): string
    {
        return $this->description;
    }
    public function setDescription(string $title): void
    {
     $this->description= $title;
    }
    public function getMainImage(): string
{
    return $this->main_image;
}

public function setMainImage(string $main_image): void
{
    $this->main_image = $main_image;
}

public function getPrepTimeMinutes(): ?int
{
    return $this->prep_time_minutes;
}

public function setPrepTimeMinutes(?int $prep_time_minutes): void
{
    $this->prep_time_minutes = $prep_time_minutes;
}
public function getDifficulty(): string
{
    return $this->difficulty;
}

public function setDifficulty(string $difficulty): void
{
    $this->difficulty = $difficulty;
}
public function getCookTimeMinutes(): ?int
{
    return $this->cook_time_minutes;
}

public function setCookTimeMinutes(?int $cook_time_minutes): void
{
    $this->cook_time_minutes = $cook_time_minutes;
}
public function getAverageRating(): ?float
{
    return $this->average_rating;
}

public function setAverageRating(float|string|null $average_rating): void
{
    $this->average_rating = $average_rating === null ? null : (float) $average_rating;
}

public function getRatingsCount(): int
{
    return $this->ratings_count;
}

public function setRatingsCount(int|string $ratings_count): void
{
    $this->ratings_count = (int) $ratings_count;
}

public function formatCookingTime(?int $cook_time_minutes ):string{
$hours=intdiv($cook_time_minutes,60);
$min=$cook_time_minutes%60;
$min=($min<10)?"0".$min:(string)$min;
return $hours."h".$min;
}

public function getPortions(): ?int
{
    return $this->portions;
}

public function setPortions(?int $portions): void
{
    $this->portions = $portions;
}


public function getBudget(): string
{
    return $this->budget;
}

public function setBudget(string $budget): void
{
    $this->budget = $budget;
}

}
