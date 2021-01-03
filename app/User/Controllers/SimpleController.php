<?php


namespace Quetzal\User\Controllers;


use Quetzal\Core\Controller;
use Quetzal\Core\Http\Request;

class SimpleController extends Controller
{

    public function index(Request $request): int
    {
        echo "SimpleController";

        $a1='a2';
        return $this->render('test3.php', ['apple_key' => "apple_value", 'a1_key' => '$a1_value',
            "tablica" => ["Aleks1_key" => "Ola1_value", "Aleks2_key" => "Ola2_value"]]);
    }
}