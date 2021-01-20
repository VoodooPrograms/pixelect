<?php

namespace Quetzal\User\Controllers;

use Quetzal\Core\Controller;
use Quetzal\Core\Database\Models\Repository;
use Quetzal\Core\Http\HttpResponse;
use Quetzal\Core\Http\Request;
use Quetzal\Core\Security\Guard;
use Quetzal\User\Models\Picture\Picture;
use Quetzal\User\Models\Picture\PictureRepository;

class PixelEditorController extends Controller
{
    private Repository $pictureRepository;

    protected function dependencies()
    {
        $this->pictureRepository = new PictureRepository();
    }

    public function index() {
        return $this->render('editor.php');
    }

    public function save(Request $request) {
        $data = json_decode($request->post()->get('picture'), true);

        $picture = new Picture(['uuid' => uniqid(), 'user_id' => Guard::id(), 'data' => json_encode($data)]);
        $picture->save();

        return new HttpResponse(['message' => $data]);
    }
}