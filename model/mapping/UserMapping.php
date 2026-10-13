<?php
//model/mapping/UserMapping.php

declare(strict_types=1);
namespace model\mapping;
use model\abstract\AbstractMapping;

class UserMapping extends AbstractMapping
{
    private ?int $id = null;
    private string $username = '';
    private string $email = '';
    private string $password_hash = '';
    private string $role = 'user';
    private ?string $created_at = null;
    private ?string $generated_key = "";

    public function getId(): ?int
    {
        return $this->id;
    }
    public function setId(?int $id): void
    {
        $this->id = $id;
    }
    public function getUsername(): string
    {
        return $this->username;
    }
    public function setUsername(string $username): void
    {
        $this->username = $username;
    }
    public function getEmail(): string
    {
        return $this->email;
    }
    public function setEmail(string $email): void
    {
        $this->email = $email;
    }
    public function getPasswordHash(): string
    {
        return $this->password_hash;
    }
    public function setPasswordHash(string $password_hash): void
    {
        $this->password_hash = $password_hash;
    }
    public function getRole(): string
    {
        return $this->role;
    }
    public function setRole(string $role): void
    {
        $this->role = $role;
    }
    public function getCreatedAt(): ?string
    {
        return $this->created_at;
    }
    public function setCreatedAt(?string $created_at): void
    {
        $this->created_at = $created_at;
    }

    
    public function getGeneratedKey(): ?string
    {
        return $this->generated_key;
    }
    public function setGeneratedKey(?string $generated_key): void
    {
        if(empty($generated_key)) {
            $this->generated_key= uniqid('',true)."-".bin2hex(random_bytes(52));
        }
        else{
            $this->generated_key = $generated_key;
        }
       
    }

}
