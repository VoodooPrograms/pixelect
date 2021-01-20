<?php

namespace Quetzal\User\Controllers;

use Quetzal\Core\Controller;
use Quetzal\Core\Database\Models\Repository;
use Quetzal\Core\Http\HttpResponse;
use Quetzal\Core\Http\Request;
use Quetzal\Core\Security\Guard;
use Quetzal\User\Models\Likes\LikeRepository;

class LikesController extends Controller
{
    private Repository $likeRepository;

    public function dependencies()
    {
        $this->likeRepository = new LikeRepository();
    }

    public function index() {
        $result = $this->likeRepository->findAll();

        return new HttpResponse(['message' => $result]);
    }

    public function store(Request $request) {
        $like_id = $request->post()->get('like_id');
        $result = $this->likeRepository->addLike(Guard::id(), $like_id);

        return new HttpResponse(['message' => $result ? 'Like added!' : 'You already liked it!']);
    }
}