<?php

namespace Quetzal\User\Models\Contests;

use Quetzal\Core\Database\Models\Model;

class Contest extends Model
{
    protected int $id;
    protected string $title;
    protected string $details;
    protected $starting_date;
    protected $ending_date;
    protected ?int $first_place;
    protected ?int $second_place;
    protected ?int $third_place;

    /* From relations */
    protected $likes = 0;
    protected $pictures = 0;

    public static function tableName(): string
    {
        return 'contests';
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
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * @param string $title
     */
    public function setTitle(string $title): void
    {
        $this->title = $title;
    }

    /**
     * @return string
     */
    public function getDetails(): string
    {
        return $this->details;
    }

    /**
     * @param string $details
     */
    public function setDetails(string $details): void
    {
        $this->details = $details;
    }

    /**
     * @return mixed
     */
    public function getStartingDate()
    {
        return $this->starting_date;
    }

    /**
     * @param mixed $starting_date
     */
    public function setStartingDate($starting_date): void
    {
        $this->starting_date = $starting_date;
    }

    /**
     * @return mixed
     */
    public function getEndingDate()
    {
        return $this->ending_date;
    }

    /**
     * @param mixed $ending_date
     */
    public function setEndingDate($ending_date): void
    {
        $this->ending_date = $ending_date;
    }

    /**
     * @return int|null
     */
    public function getFirstPlace(): ?int
    {
        return $this->first_place;
    }

    /**
     * @param int|null $first_place
     */
    public function setFirstPlace(?int $first_place): void
    {
        $this->first_place = $first_place;
    }

    /**
     * @return int|null
     */
    public function getSecondPlace(): ?int
    {
        return $this->second_place;
    }

    /**
     * @param int|null $second_place
     */
    public function setSecondPlace(?int $second_place): void
    {
        $this->second_place = $second_place;
    }

    /**
     * @return int|null
     */
    public function getThirdPlace(): ?int
    {
        return $this->third_place;
    }

    /**
     * @param int|null $third_place
     */
    public function setThirdPlace(?int $third_place): void
    {
        $this->third_place = $third_place;
    }

    /**
     * @return mixed
     */
    public function getLikes()
    {
        return $this->likes;
    }

    /**
     * @param mixed $likes
     */
    public function setLikes($likes): void
    {
        $this->likes = $likes;
    }

    /**
     * @return int
     */
    public function getPictures(): int
    {
        return $this->pictures;
    }

    /**
     * @param int $pictures
     */
    public function setPictures(int $pictures): void
    {
        $this->pictures = $pictures;
    }
}