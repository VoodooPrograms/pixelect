<?php

namespace Quetzal\User\Controllers;

use Quetzal\Core\Controller;

class PixelEditorController extends Controller
{

    public function index() {
        return $this->render('editor.php');
    }
}