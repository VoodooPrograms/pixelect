<?php

namespace Quetzal\User\Controllers;

use Quetzal\Core\Controller;
use Quetzal\Core\Http\HttpResponse;
use Quetzal\Core\Http\Request;

class PixelEditorController extends Controller
{

    public function index() {
        return $this->render('editor.php');
    }

    public function save(Request $request) {
        $data = json_decode($request->post()->get('picture'), true);
        $data['pixels'][3] = "#222888";
        header('Content-type: application/json');
        echo json_encode( ['message' => $data] );
        return 1;

//        return 1;
//        return HttpResponse::createResponse('Hej JS', ['application/json'])->send();
    }
}