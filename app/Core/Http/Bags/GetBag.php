<?php

namespace Quetzal\Core\Http\Bags;

class GetBag
{
    public function __construct(array $data) {
        $this->bag = $data;
    }
}