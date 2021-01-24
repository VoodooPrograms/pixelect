<?php

namespace Quetzal\User\Models\Picture;

use Quetzal\Core\Database\Models\Model;
use Quetzal\Core\Database\Models\Repository;

class PictureRepository extends Repository
{
    private $conn;

    public function __construct()
    {
        parent::__construct();
        $this->conn = $this->database->connect();
    }

    public function findAll() {
        $stmt = $this->conn->prepare('
            SELECT pictures.*,
                (SELECT count(*) FROM likes WHERE likes.picture_id = pictures.id) as likes
            FROM pictures
                LEFT JOIN likes ON pictures.id = likes.picture_id
                GROUP BY pictures.id;
        ');
        $stmt->execute();

        return $stmt->fetchAll(\PDO::FETCH_CLASS, Picture::class);
    }

    public function findContestPictures(int $contest_id) {
        $stmt = $this->conn->prepare('
            SELECT
                   pictures.*,
                   (SELECT count(*) FROM contest_likes WHERE contest_likes.contest_id = pictures.id) as likes
            FROM contest_likes
                     LEFT JOIN pictures ON contest_likes.id = pictures.id
            WHERE contest_likes.contest_id = :id
        ');
        $stmt->bindValue(':id', $contest_id);

        $stmt->execute();

        return $stmt->fetchAll(\PDO::FETCH_CLASS, Picture::class);
    }

    public function findPicturesOfUser(int $user_id) {
        $stmt = $this->conn->prepare('
            SELECT pictures.*,
                   (SELECT count(*) FROM likes WHERE likes.picture_id = pictures.id) as likes
            FROM pictures
                     LEFT JOIN likes ON pictures.id = likes.picture_id
            WHERE pictures.user_id = :user_id
            GROUP BY pictures.id;
        ');
        $stmt->bindValue(':user_id', $user_id);

        $stmt->execute();

        return $stmt->fetchAll(\PDO::FETCH_CLASS, Picture::class);
    }

}