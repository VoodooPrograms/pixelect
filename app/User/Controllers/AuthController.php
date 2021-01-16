<?php

namespace Quetzal\User\Controllers;

use Quetzal\Core\Controller;
use Quetzal\Core\Database\Models\Repository;
use Quetzal\Core\Http\Request;
use Quetzal\User\Models\User\User;
use Quetzal\User\Models\User\UserRepository;
use Quetzal\User\Rules\UserRule;

class AuthController extends Controller
{
    private Repository $userRepository;

    protected function dependencies()
    {
        $this->userRepository = new UserRepository();
    }

    public function loginView() {
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
        $data = $request->post()->toArray();
        $rule = UserRule::make();
        $valid = $rule->validation($data);

        if ($valid) {
            $data['password'] = password_hash($data['password'], PASSWORD_BCRYPT);

            $user = new User($data);
            $user->save();

            $this->redirect('login');
        }

        $this->registry->getRequest()->getSession()->setFlash('_error_bad_registry', $rule->errors);
        $this->redirect('login');
    }

    public function logoutUser() {
        $this->registry->getGuard()->logout();

        $this->registry->getRequest()->getSession()->setFlash('_message_logout', 'You have logged out. Goodbye!');
        $this->redirect('login');
    }
}