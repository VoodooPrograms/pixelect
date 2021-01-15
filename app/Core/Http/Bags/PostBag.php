<?php

namespace Quetzal\Core\Http\Bags;

class PostBag extends Bag
{
    public function __construct(array $data) {
        $this->bag = $data;
    }
}