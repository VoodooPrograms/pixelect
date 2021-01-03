<?php


namespace Quetzal\User\Controllers;


use Quetzal\Core\Controller;
use Quetzal\Core\Request;

class BartekController extends Controller
{

    public function index(Request $request)
    {
        echo "Bartek Controller";

        $nazwisko = 'Bieńko';
        $this->render('blade_example', ["imie" => "Bartek", "nazwisko" => $nazwisko]);
    }
}