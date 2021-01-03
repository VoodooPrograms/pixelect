<?php


namespace Quetzal\Core\Routing;

use Quetzal\Core\Http\Request;

abstract class Resolver
{
    abstract public function match(Request $request, array $routing): ?array;
}
