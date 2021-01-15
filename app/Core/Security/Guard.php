<?php

namespace Quetzal\Core\Security;

use Quetzal\Core\Register;

class Guard
{
    private $reg;
    protected string $base_redirect = '';
    const USER_KEY = '__user';

    public function __construct() {
        $this->reg = Register::instance();
    }

    public function isRouteGuarded($route) {
        if ($route == 'auth') {
            return true;
        }
        return false;
    }

    public function onAuthFailure() {
        $location = $_SERVER['REQUEST_SCHEME'] . '://' . $_SERVER['HTTP_HOST'] . '/' . $this->base_redirect;
        header("Location: " . $location);
    }

    public function auth(int $user_id) {
        $this->reg->getRequest()->getSession()->set(self::USER_KEY, $user_id);
    }

    public function getBaseRedirect(): string
    {
        return $this->base_redirect;
    }

    public function setBaseRedirect(string $base_redirect): void
    {
        $this->base_redirect = $base_redirect;
    }


}