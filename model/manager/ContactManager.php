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




} 