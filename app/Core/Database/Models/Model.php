<?php

namespace Quetzal\Core\Database\Models;

use Quetzal\Core\Database\Utils\Collection;
use Quetzal\Core\Register;

abstract class Model
{
    public function __construct(array $properties) {
        foreach ($properties as $property => $value) {
            if (property_exists(get_class($this), $property)) {
                $this->$property = $value;
            }
        }
    }

    abstract public static function tableName(): string;


    public function toArray() {
        return (array) $this;
    }

    public static function collection(string $type) : ?Collection {
        return Collection::getCollection($type);
    }

    public function save() {
        $tableName = $this->tableName();
        $attributes = get_object_vars($this);
        $params = array_map(fn($attr) => ":$attr", array_keys($attributes));

        $reg = Register::instance();
        $statement = $reg->getDatabase()->connect()->prepare("
            INSERT INTO $tableName (" .
            implode(",", array_keys($attributes)) . ") 
            VALUES (" . implode(",", $params) . ")");

        foreach ($attributes as $param => $attribute) {
            $statement->bindValue(":$param", $attribute);
        }
        $statement->execute();
        return true;
    }

    public function markDirty() {

    }
}