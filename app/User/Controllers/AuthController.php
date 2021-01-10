<?php

namespace Quetzal\User\Controllers;

use Quetzal\Core\Controller;

class AuthController extends Controller
{
    public function loginView() {

        return $this->render('login_view.php');
    }
}