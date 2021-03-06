<?php

namespace Quetzal\Core\Database\Models;

use JsonSerializable;
use Quetzal\Core\Database\Utils\Collection;
use Quetzal\Core\Register;
use ReflectionProperty;

abstract class Model implements JsonSerializable
{
    public function __construct(array $properties = []) {
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

    public function jsonSerialize() {
        return (object) get_object_vars($this);
    }

    public static function collection(string $type) : ?Collection {
        return Collection::getCollection($type);
    }

    public function save() {
        $tableName = $this->tableName();
        $class = new \ReflectionClass($this);

        foreach ($class->getProperties(ReflectionProperty::IS_PROTECTED) as $reflectionProperty){
            $propertyName = $reflectionProperty->getName();
            $attributes[$reflectionProperty->getName()] = $this->$propertyName ?? null;
        }

        $params = array_map(fn($attr) => ":$attr", array_keys($attributes));

        $reg = Register::instance();
        $statement = $reg->getDatabase()->connect()->prepare("
            INSERT INTO $tableName (" .
            implode(",", array_keys($attributes)) . ") 
            VALUES (" . implode(",", $params) . ")");

        foreach ($attributes as $param => $attribute) {
            if ($attribute instanceof \DateTime) $attribute = $attribute->format('Y-m-d H:i:s');
            $statement->bindValue(":$param", $attribute);
        }
        $statement->execute();
        return true;
    }

    public static function find($where)
    {
        $tableName = static::tableName();
        $attributes = array_keys($where);
        $sql = implode(" AND ", array_map(fn($attr) => "$attr = :$attr", $attributes));

        $reg = Register::instance();
        $statement = $reg->getDatabase()->connect()->prepare("SELECT * FROM $tableName WHERE $sql");
        foreach ($where as $key => $item) {
            $statement->bindValue(":$key", $item);
        }
        $statement->execute();
        return $statement->fetchObject(static::class);
    }
}