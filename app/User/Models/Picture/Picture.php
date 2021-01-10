<?php

namespace Quetzal\User\Models\Picture;

use Quetzal\Core\Database\Models\Model;

class Picture extends Model
{
    private string $uuid;
    private $owner; // User
    private array $data; // json with picture data


}