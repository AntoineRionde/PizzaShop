<?php

namespace pizzashop\shop\domain\service\interfaces;

interface IAuth
{
    public function authenticate($userId, $password);
    public function authenticateWithRefreshToken($refreshToken);
    public function getProfile($username, $email, $refreshToken);
    public function register($username, $email, $password);
    public function activate($username, $email, $password);
}