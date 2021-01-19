<?php

namespace Quetzal\User\Models\Likes;

use Quetzal\Core\Database\Models\Model;
use Quetzal\Core\Database\Models\Repository;

class LikeRepository extends Repository
{
    private $conn;

    public function __construct()
    {
        parent::__construct();
        $this->conn = $this->database->connect();
    }

    public function findAll() {
        $stmt = $this->conn->prepare('
            SELECT *
            FROM likes
        ');
        $stmt->execute();

        return $stmt->fetchAll(\PDO::FETCH_CLASS, Like::class);
    }

    public function addLike(int $user_id, int $picture_id): bool {
        if (!Like::findOne(['user_id' => $user_id, 'picture_id' => $picture_id])) {
            $like = new Like(['user_id' => $user_id, 'picture_id' => $picture_id]);
            $like->save();
            return true;
        }
        return false;
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