<?php

namespace Quetzal\User\Controllers;

use Quetzal\Core\Controller;

class MainController extends Controller
{
    public function index() {
        return $this->render('main_view.php');
    }
}