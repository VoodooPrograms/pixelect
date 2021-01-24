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

    public function findUserById(int $id) {
        $stmt = $this->conn->prepare('
            SELECT users.*, (SELECT count(*) FROM likes WHERE likes.user_id = :id) as likes
            FROM users
                     LEFT JOIN likes ON users.id = likes.user_id
            WHERE users.id = :id
            GROUP BY users.id
        ');

        $stmt->bindValue(':id', $id);
        $stmt->execute();

        if (!$stmt->rowCount()) {
            return null;
        }
        return new User($stmt->fetch());
    }
}