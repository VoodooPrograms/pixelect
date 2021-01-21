<?php

namespace Quetzal\User\Controllers;

use Quetzal\Core\Controller;
use Quetzal\Core\Database\Models\Repository;
use Quetzal\Core\Http\HttpResponse;
use Quetzal\Core\Security\Guard;
use Quetzal\User\Models\User\UserRepository;

class UserController extends Controller
{
    private Repository $userRepository;

    protected function dependencies()
    {
        $this->userRepository = new UserRepository();
    }

    public function getUserLikes() {
        $user = $this->userRepository->findUserById(Guard::id());
        return new HttpResponse($user);
    }
}
