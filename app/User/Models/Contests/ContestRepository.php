<?php

namespace Quetzal\User\Models\Contests;

use Quetzal\Core\Database\Models\Model;
use Quetzal\Core\Database\Models\Repository;

class ContestRepository extends Repository
{
    private $conn;

    public function __construct()
    {
        parent::__construct();
        $this->conn = $this->database->connect();
    }

    public function findAll() {
        $stmt = $this->conn->prepare('
            SELECT contests.*,
                (SELECT count(*) FROM contest_likes WHERE contest_likes.contest_id = contests.id) as likes,
                (SELECT count(*) FROM contest_pictures WHERE contest_pictures.contest_id = contests.id) as pictures
            FROM contests
                LEFT JOIN contest_likes ON contests.id = contest_likes.contest_id
                LEFT JOIN contest_pictures ON contests.id = contest_pictures.contest_id
            GROUP BY contests.id;
        ');
        $stmt->execute();

        return $stmt->fetchAll(\PDO::FETCH_CLASS, Contest::class);
    }

    public function findById(int $id) {
        $stmt = $this->conn->prepare('
            SELECT contests.*,
                (SELECT count(*) FROM contest_likes WHERE contest_likes.contest_id = contests.id) as likes,
                (SELECT count(*) FROM contest_pictures WHERE contest_pictures.contest_id = contests.id) as pictures
            FROM contests
                LEFT JOIN contest_likes ON contests.id = contest_likes.contest_id
                LEFT JOIN contest_pictures ON contests.id = contest_pictures.contest_id
                WHERE contests.id = :id
            GROUP BY contests.id;
        ');
        $stmt->bindValue(':id', $id);

        $stmt->execute();

        return $stmt->fetchObject(Contest::class);
    }

    public function update(array $data, int $id)
    {
        // TODO: Implement update() method.
    }

    protected function doCreateObject(array $data): Model
    {
        // TODO: Implement doCreateObject() method.
    }

    protected function doInsert(Model $model)
    {
        // TODO: Implement doInsert() method.
    }

    protected function selectStmt(): \PDOStatement
    {
        // TODO: Implement selectStmt() method.
    }

    protected function targetClass(): string
    {
        // TODO: Implement targetClass() method.
    }
}