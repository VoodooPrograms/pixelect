<?php

namespace Quetzal\User\Controllers;

use Quetzal\Core\Controller;
use Quetzal\Core\Database\Models\Repository;
use Quetzal\Core\Http\HttpRequest;
use Quetzal\Core\Http\Request;
use Quetzal\User\Models\User\User;
use Quetzal\User\Models\User\UserRepository;

class AuthController extends Controller
{
    private Repository $userRepository;

    protected function dependencies()
    {
        $this->userRepository = new UserRepository();
    }

    public function loginView() {
//        $user = new User(
//            ['name' => 'bartosz', 'email' => 'asd512@gmail.com', 'password' => 'test12346']
//        );
//        $user->save();
//        $this->userRepository->insert(new User(['name' => 'bartosz', 'email' => 'asd51@gmail.com', 'password' => 'test12346']));
//        dump($this->userRepository->find(5));
//        $this->userRepository->update(['name' => 'bartosz2', 'email' => 'asd5@gmail.com', 'password' => 'test12346'], 1);

        return $this->render('login_view.php');
    }

    public function loginUser(Request $request) {
        $email = $request->post()->get('email');
        $password = $request->post()->get('password');

        $user = $this->userRepository->findByEmail($email);

        if ($user) {
            if (password_verify($password, $user->getPassword())) {
                $this->registry->getGuard()->auth($user->getId());
                $this->redirect('main');
            }
        }
        return $this->render('login_view.php', ['_error_bad_credentials' => 'You passed bad credentials']);
    }

    public function registerUser(Request $request) {
//        var_dump($request);

        // validate $data
        $data = $request->post()->toArray();
        $data['password'] = password_hash($data['password'], PASSWORD_BCRYPT);

        $user = new User($data);
        $user->save();

        $this->redirect('login');
    }
}