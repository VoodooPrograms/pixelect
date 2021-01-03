<?php


namespace Quetzal\Core\Http;

abstract class Request
{

    final public function __construct()
    {
        $this->launch();
    }

    abstract protected function launch();

}
