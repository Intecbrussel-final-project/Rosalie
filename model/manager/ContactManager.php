<?php
// path: model/manager/ContactManager.php
// typage strict
declare(strict_types=1);

namespace model\manager;
use PDO;
use model\interface\ManagerInterface;
use model\mapping\ContactMapping;

class ContactManager implements ManagerInterface
{
    protected PDO $connect;

    public function __construct(PDO $connect)
    {
        $this->connect = $connect;
    }

    // enregistre un message du formulaire de contact (non lu par défaut)
    public function createMessage(ContactMapping $contact): bool
    {
        $sql = "INSERT INTO contact_messages (name, email, subject, message)
                VALUES (:name, :email, :subject, :message)";
        $stmt = $this->connect->prepare($sql);
        return $stmt->execute([
            'name' => $contact->getName(),
            'email' => $contact->getEmail(),
            'subject' => $contact->getSubject(),
            'message' => $contact->getMessage(),
        ]);
    }

    // nombre de messages non lus (badge de notification de l'admin)
    public function countUnread(): int
    {
        $stmt = $this->connect->query("SELECT COUNT(*) FROM contact_messages WHERE is_read = 0");
        return (int) $stmt->fetchColumn();
    }

    // tous les messages : les non lus d'abord, puis du plus récent au plus ancien
    public function getAllMessages(): array
    {
        $sql = "SELECT id, name, email, subject, message, is_read, created_at
                FROM contact_messages
                ORDER BY is_read ASC, created_at DESC, id DESC";
        $stmt = $this->connect->query($sql);

        $messages = [];
        foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $row) {
            $messages[] = new ContactMapping($row);
        }
        return $messages;
    }

    public function markAsRead(int $id): bool
    {
        $stmt = $this->connect->prepare("UPDATE contact_messages SET is_read = 1 WHERE id = :id");
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->rowCount() > 0;
    }

    public function markAllAsRead(): int
    {
        return (int) $this->connect->exec("UPDATE contact_messages SET is_read = 1 WHERE is_read = 0");
    }
}
