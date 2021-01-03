<?php


namespace Quetzal\Core\Http;

abstract class Response
{

    abstract public function send(): int;
}
