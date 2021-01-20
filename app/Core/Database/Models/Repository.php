<?php

namespace Quetzal\Core\Database\Models;

use Quetzal\Core\Database\Database;
use Quetzal\Core\Register;

abstract class Repository
{
    protected Database $database;
    private $reg;

    public function __construct() {
        $this->reg = Register::instance();
        $this->database = $this->reg->getDatabase();
    }
}