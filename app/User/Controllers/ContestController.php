<?php

namespace Quetzal\User\Controllers;

use Quetzal\Core\Controller;
use Quetzal\Core\Database\Models\Repository;
use Quetzal\Core\Http\HttpResponse;
use Quetzal\Core\Http\Request;
use Quetzal\User\Models\Contests\Contest;
use Quetzal\User\Models\Contests\ContestRepository;
use Quetzal\User\Models\Picture\PictureRepository;
use Quetzal\User\Rules\ContestRule;

class ContestController extends Controller
{
    private Repository $contestRepository;
    private Repository $pictureRepository;

    public function dependencies()
    {
        $this->contestRepository = new ContestRepository();
        $this->pictureRepository = new PictureRepository();
    }

    public function index() {
        $contests = $this->contestRepository->findAll();

        return $this->render('contests.php', ['contests' => $contests]);
    }

    public function show(Request $request) {
        $id = explode('/', $request->getPath())[2];
        $contest = $this->contestRepository->findById($id);

        if (!$contest) {
            return new HttpResponse(['message' => 'Not found resource'], 404);
        }

        return $this->render('contest.php', ['contest' => $contest]);
    }

    public function indexByContest(Request $request) {

        $id = explode('/', $request->getPath())[3];
        $pictures = $this->pictureRepository->findContestPictures($id);

        return new HttpResponse(['pictures' => $pictures]);
    }

    public function store() {
        $data = $this->registry->getRequest()->post()->toArray();
        $rule = ContestRule::make();
        $valid = $rule->validation($data);

        if ($valid) {
            $contest = new Contest($data);
            $contest->save();

            return new HttpResponse(['message' => 'Contest has been added!']);
        }

        $this->registry->getRequest()->getSession()->setFlash('_error_bad_contest_data', $rule->errors);
        $this->redirect('contests');
    }
}