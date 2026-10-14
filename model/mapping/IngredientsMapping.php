<?php
// model/mapping/IngredientsMapping.php

declare(strict_types=1);

namespace model\mapping;

use Exception;
use model\abstract\AbstractMapping;

class IngredientsMapping extends AbstractMapping
{
    private ?int $id = null;
    private string $name = '';
    private string $img_ingredient = '';
    private ?float $quantity = null;
    private ?string $unit = null;
    // getters and setters
    public function getId(): ?int
    {
        return $this->id;
    }
    public function setId(?int $id): void
    {
        if ($id <= 0) throw new Exception("id doit être un entier positif");
        $this->id = $id;
    }
    public function getName(): string
    {
        return $this->name;
    }
    public function setName(string $title): void
    {
       $name = strip_tags(trim($title));
if(mb_strlen($name)<3 || mb_strlen($name)>120){
    throw new Exception("Le nom de l'ingrédient doit faire entre 3 et 120 caractères");
}
        $this->name = $name;
    }
    public function getImgIngredient(): string
    {
        return $this->img_ingredient;
    }

    public function setImgIngredient(string $img_ingredient): void
    {
        $this->img_ingredient = $img_ingredient;
    }


    public function getQuantity(): ?float
    {
        return $this->quantity;
    }
    public function setQuantity(float|string|null $quantity): void
    {
        $this->quantity = $quantity === null ? null : (float) $quantity;
    }

    public function getUnit(): ?string
    {
        return $this->unit;
    }

    public function setUnit(?string $unit): void
    {
        $this->unit = $unit;
    }
}
