<?php


namespace Quetzal\User\Controllers;


use Quetzal\Core\Controller;
use Quetzal\Core\Http\Request;

class BlogController extends Controller
{

    public function index(Request $request): int
    {
        echo "BlogController";

        $a1='a2';
        return $this->render('test4.php', ['apple_key' => "apple_value", 'a1_key' => '$a1_value',
            "tablica" => ["Aleks1_key" => "Ola1_value", "Aleks2_key" => "Ola2_value"]]);
    }

    public function index2(): int
    {
        echo "BlogController";

        $a1='a2';
        return $this->render('test4.php', ['apple_key' => "apple_value", 'a1_key' => '$a1_value',
            "tablica" => ["Aleks1_key" => "Ola1_value", "Aleks2_key" => "Ola2_value"]]);
    }
}