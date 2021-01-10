<?php

namespace Quetzal\Core\Database\Models;

use Quetzal\Core\Database\Utils\Collection;

abstract class Model
{
    private int $id;

    public function __construct(int $id) {
        $this->id = $id;
    }

    public function getId(): int {
        return $this->id;
    }

    public static function collection(string $type) : ?Collection {
        return Collection::getCollection($type);
    }

    public function markDirty() {

    }
}