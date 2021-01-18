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

    public function update(array $data, int $id)
    {
        $updateStmt = $this->conn->prepare('
            UPDATE users SET id = :id, name = :name, email = :email, password = :password WHERE id = :id
        ');
        $updateStmt->execute([
            $id,
            $data['name'],
            $data['email'],
            $data['password'],
        ]);
    }

    protected function doCreateObject(array $data): Model
    {
        return new User($data);
    }

    protected function doInsert(Model $model)
    {
        $insertStmt = $this->conn->prepare('
            INSERT INTO users ( name, email, password ) VALUES( :name, :email, :password )
        ');
        $insertStmt->execute([
            $model->getName(),
            $model->getEmail(),
            $model->getPassword()
        ]);
    }

    protected function selectStmt(): \PDOStatement
    {
        return $this->conn->prepare('
            SELECT * FROM users WHERE id=?
        ');
    }

    protected function targetClass(): string
    {
        return User::class;
    }
}