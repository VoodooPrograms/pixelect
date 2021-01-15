<?php

namespace Quetzal\Core\Database\Utils;

abstract class Collection implements \IteratorAggregate
{
    protected array $collection;

    public function getIterator()
    {
        new \ArrayIterator($this->collection);
    }

    public function add($route) {
        $this->collection[] = $route;
    }
}