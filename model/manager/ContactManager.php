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



public function createMessage(ContactMapping $contact): bool
{
 $sql="INSERT INTO contact_messages (`name`, `email`,`subject`,`message`)
    VALUES (:name, :email, :subject, :message)";
            $stmt = $this->connect->prepare($sql);
            $stmt->bindValue(':name',$contact->getName());
            $stmt->bindValue(':email',$contact->getEmail());
            $stmt->bindValue(':subject',$contact->getSubject());
            $stmt->bindValue(':message',$contact->getMessage());
            return $stmt->execute();
}



} 