<?php

namespace Quetzal\User\Controllers;

use Quetzal\Core\Controller;
use Quetzal\Core\Database\Models\Repository;
use Quetzal\User\Models\Picture\PictureRepository;

class MainController extends Controller
{
    private Repository $pictureRepository;

    protected function dependencies()
    {
        $this->pictureRepository = new PictureRepository();
    }


    public function index() {
//        var_dump(Picture::findOne(['id' => 2]));
        return $this->render('main_view.php');
    }

    public function pictures() {
        $pictures = $this->pictureRepository->findAll();
        header('Content-type: application/json');
        echo json_encode( ['pictures' => $pictures] );
        return 1;
    }
}