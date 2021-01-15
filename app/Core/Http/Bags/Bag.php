<?php

namespace Quetzal\Core\Http\Bags;

use Quetzal\Core\AppException;

abstract class Bag implements \ArrayAccess, \Countable, \IteratorAggregate
{
    protected array $bag = [];

    public function get(string $key)
    {
        if (!$this->bag[$key]) {
            throw new AppException('Undefined property: ' . $key);
        }
        return $this->bag[$key];
    }

    public function add(string $key, $value)
    {
        if ($this->bag[$key]) {
            throw new AppException("Property ${key} already existing with value: ${$this->bag[$key]}");
        }
        $this->bag[$key] = $value;
    }

    public function isEmpty(): bool
    {
        return $this->count() == 0;
    }

    public function toArray(): array
    {
        return (array)$this->bag;
    }

    public function getIterator()
    {
        return new \ArrayIterator($this->bag);
    }

    public function offsetSet($offset, $value) {
        if (is_null($offset)) {
            $this->bag[] = $value;
        } else {
            $this->bag[$offset] = $value;
        }
    }

    public function offsetExists($offset) {
        return isset($this->container[$offset]);
    }

    public function offsetUnset($offset) {
        unset($this->bag[$offset]);
    }

    public function offsetGet($offset) {
        return isset($this->container[$offset]) ? $this->bag[$offset] : null;
    }

    public function count()
    {
        return count($this->bag);
    }
}