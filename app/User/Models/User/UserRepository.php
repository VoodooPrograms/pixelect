<?php

namespace Quetzal\User\Models\User;

use Quetzal\Core\Database\Models\Model;
use Quetzal\Core\Database\Models\Repository;

class UserRepository extends Repository
{
    private $selectStmt;
    private $updateStmt;
    private $insertStmt;

    public function __construct()
    {
        parent::__construct();
        $this->selectStmt = $this->database->connect()->prepare('
            SELECT * FROM users WHERE id=?
        ');
        $this->updateStmt = $this->database->connect()->prepare('
            UPDATE users SET id = :id, name = :name, email = :email, password = :password WHERE id = :id
        ');
        $this->insertStmt = $this->database->connect()->prepare('
            INSERT INTO users ( name, email, password ) VALUES( :name, :email, :password )
        ');
    }

    public function findByEmail(string $email): ?User {
        $this->selectStmt = $this->database->connect()->prepare('
            SELECT * FROM users WHERE email=:email
        ');
        $this->selectStmt->bindValue(':email', $email);
        $this->selectStmt->execute();
        if (!$this->selectStmt->rowCount()) {
            return null;
        }
        return new User($this->selectStmt->fetch());
    }

    public function update(array $data, int $id)
    {
        $this->updateStmt->execute([
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
        $this->insertStmt->execute([
            $model->getName(),
            $model->getEmail(),
            $model->getPassword()
        ]);
    }

    protected function selectStmt(): \PDOStatement
    {
        return $this->selectStmt;
    }

    protected function targetClass(): string
    {
        return User::class;
    }
}