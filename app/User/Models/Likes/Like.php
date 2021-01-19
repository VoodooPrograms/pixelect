<?php

namespace Quetzal\User\Models\Likes;

use Quetzal\Core\Database\Models\Model;

class Like extends Model
{
    protected int $id;
    protected int $user_id;
    protected int $picture_id;

    public static function tableName(): string
    {
        return 'likes';
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId(int $id): void
    {
        $this->id = $id;
    }

    /**
     * @return int
     */
    public function getUserId(): int
    {
        return $this->user_id;
    }

    /**
     * @param int $user_id
     */
    public function setUserId(int $user_id): void
    {
        $this->user_id = $user_id;
    }

    /**
     * @return int
     */
    public function getPictureId(): int
    {
        return $this->picture_id;
    }

    /**
     * @param int $picture_id
     */
    public function setPictureId(int $picture_id): void
    {
        $this->picture_id = $picture_id;
    }
}