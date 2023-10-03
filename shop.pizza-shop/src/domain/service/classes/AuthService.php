<?php

namespace pizzashop\shop\domain\service\classes;

use pizzashop\shop\domain\service\interfaces\IAuth;

class AuthService implements IAuth
{

    public function authenticate($userId, $password)
    {

    }

    public function authenticateWithRefreshToken($refreshToken)
    {
        // TODO: Implement authenticateWithRefreshToken() method.
    }

    public function getProfile($username, $email, $refreshToken)
    {
        // TODO: Implement getProfile() method.
    }

    public function register($username, $email, $password)
    {
        // TODO: Implement register() method.
    }

    public function activate($username, $email, $password)
    {
        // TODO: Implement activate() method.
    }
}