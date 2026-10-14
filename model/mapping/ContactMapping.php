<?php
//model/mapping/ContactMapping.php

declare(strict_types=1);
namespace model\mapping;
use model\abstract\AbstractMapping;

class ContactMapping extends AbstractMapping
{
    private ?int $id = null;
    private string $name = '';
    private string $email = '';
    private string $subject = '';
    private string $message = '';
    // notification admin : false tant que le message n'a pas été lu
    private bool $is_read = false;
    private ?string $created_at = null;
 

    public function getId(): ?int
    {
        return $this->id;
    }
    public function setId(?int $id): void
    {
        $this->id = $id;
    }
    public function getName(): string
    {
        return $this->name;
    }
    public function setName(string $name): void
    {
        $this->name = $name;
    }
    public function getEmail(): string
    {
        return $this->email;
    }
    public function setEmail(string $email): void
    {
        $this->email = $email;
    }
    public function getMessage(): string
    {
        return $this->message;
    }
    public function setMessage(string $message): void
    {
        $this->message = $message;
    }
    public function getSubject(): string
    {
        return $this->subject;
    }
    public function setSubject(string $subject): void
    {
        $this->subject = $subject;
    }
    public function getIsRead(): bool
    {
        return $this->is_read;
    }
    public function setIsRead(bool $is_read): void
    {
        $this->is_read = $is_read;
    }
    public function getCreatedAt(): ?string
    {
        return $this->created_at;
    }
    public function setCreatedAt(?string $created_at): void
    {
        $this->created_at = $created_at;
    }
    
}
