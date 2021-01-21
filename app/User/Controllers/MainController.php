<?php

namespace Quetzal\User\Controllers;

use Quetzal\Core\Controller;
use Quetzal\Core\Database\Models\Repository;
use Quetzal\Core\Http\HttpResponse;
use Quetzal\Core\Security\Guard;
use Quetzal\User\Models\Picture\PictureRepository;
use Quetzal\User\Models\User\UserRepository;

class MainController extends Controller
{
    private Repository $pictureRepository;
    private Repository $userRepository;

    protected function dependencies()
    {
        $this->pictureRepository = new PictureRepository();
        $this->userRepository = new UserRepository();
    }

    public function index() {
        return $this->render('main_view.php');
    }

    public function pictures() {
        $pictures = $this->pictureRepository->findAll();
        return new HttpResponse(['pictures' => $pictures]);
    }
}