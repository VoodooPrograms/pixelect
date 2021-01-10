<?php

namespace Quetzal\User\Controllers;

use Quetzal\Core\Controller;
use Quetzal\Core\Database\Models\Repository;
use Quetzal\User\Models\User\UserRepository;

class AuthController extends Controller
{
    private Repository $userRepository;

    protected function dependencies()
    {
        $this->userRepository = new UserRepository();
    }

    public function loginView() {
        dump($this->userRepository->getUser('stannis@gmail.com'));

        return $this->render('login_view.php');
    }
}