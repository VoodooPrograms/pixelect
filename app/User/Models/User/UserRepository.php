<?php

namespace Quetzal\User\Models\User;

use Quetzal\Core\Database\Models\Model;
use Quetzal\Core\Database\Models\Repository;

class UserRepository extends Repository
{
    private $conn;

    public function __construct()
    {
        parent::__construct();
        $this->conn = $this->database->connect();
    }

    public function findByEmail(string $email): ?User {
        $stmt = $this->conn->prepare('
            SELECT * FROM users WHERE email=:email
        ');
        $stmt->bindValue(':email', $email);
        $stmt->execute();
        if (!$stmt->rowCount()) {
            return null;
        }
        return new User($stmt->fetch());
    }
}