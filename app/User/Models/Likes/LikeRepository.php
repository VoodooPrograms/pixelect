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
        if (!Like::find(['user_id' => $user_id, 'picture_id' => $picture_id])) {
            $like = new Like(['user_id' => $user_id, 'picture_id' => $picture_id]);
            $like->save();
            return true;
        }
        return false;
    }
}