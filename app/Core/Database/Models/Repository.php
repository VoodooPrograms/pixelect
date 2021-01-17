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

    abstract public function update(array $data, int $id);
    abstract protected function doCreateObject(array $data): Model;
    abstract protected function doInsert(Model $model);
    abstract protected function selectStmt(): \PDOStatement;
    abstract protected function targetClass(): string;


    public function find(int $id): ?Model {
        $this->selectStmt()->execute([$id]);
        $row = $this->selectStmt()->fetch();
        $this->selectStmt()->closeCursor();
        if (!is_array($row)) {
            return null;
        }
        if (!isset($row['id'])) {
            return null;
        }
        $object = $this->doCreateObject($row);
        return $object;
    }

    public function createObject(array $raw) {
        return $this->doCreateObject($raw);
    }

    public function insert(Model $obj) {
        $this->doInsert($obj);
    }
}