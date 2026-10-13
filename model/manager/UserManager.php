<?php
// path: model/manager/UserManager.php
// typage strict
declare(strict_types=1);

namespace model\manager;
use PDO;
use model\interface\ManagerInterface;
use model\mapping\UserMapping;

class UserManager implements ManagerInterface
{
    protected PDO $connect;

    public function __construct(PDO $connect)
    {
        $this->connect = $connect;
    }
public function emailOrUsernameExists(string $email, string $username): bool{
$sql="SELECT email,username FROM users WHERE email=:email OR username=:username";
$stmt = $this->connect->prepare($sql);
$stmt->execute([
    'email' => $email,
    'username' => $username
]);
$row = $stmt->fetch(PDO::FETCH_ASSOC);
    return $row !== false;
}


public function createUser(UserMapping $user): bool
{
 $sql="INSERT INTO users (`username`, `email`,`password_hash`,`generated_key`)
    VALUES (:username, :email, :password_hash, :generated_key)";
            $stmt = $this->connect->prepare($sql);
            $stmt->bindValue(':username',$user->getUsername());
            $stmt->bindValue(':email',$user->getEmail());
            $stmt->bindValue(':password_hash',$user->getPasswordHash());
            $stmt->bindValue(':generated_key',$user->getGeneratedKey());
            return $stmt->execute();
}
public function getUserByEmail(string $email): array|false
{
    $sql = "SELECT id, username, email, password_hash, role ,  verification_status
            FROM users
            WHERE email = :email
            LIMIT 1";

    $stmt = $this->connect->prepare($sql);

    $stmt->execute([
        'email' => $email
    ]);

    return $stmt->fetch(PDO::FETCH_ASSOC);
}

// adresses des administrateurs, pour les notifications par e-mail
public function getAdminEmails(): array
{
    $sql = "SELECT email FROM users WHERE role = 'admin'";
    $stmt = $this->connect->prepare($sql);
    $stmt->execute();

    return $stmt->fetchAll(PDO::FETCH_COLUMN);
}

public function verifyEmail(string $generatedKey): bool
{
    $sql = "UPDATE users
            SET verification_status = 'verified',
                generated_key = NULL
            WHERE generated_key = :generated_key
            AND verification_status = 'pending'";

    $stmt = $this->connect->prepare($sql);

    $stmt->execute([
        'generated_key' => $generatedKey
    ]);

    return $stmt->rowCount() > 0;
}

} 