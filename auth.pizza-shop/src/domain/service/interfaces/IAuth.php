<?php

namespace pizzashop\auth\api\domain\service\interfaces;

use pizzashop\auth\api\domain\entities\auth\User;

interface IAuth
{
    public function verifyCredentials($email, $password): User;

    public function verifyRefreshToken($refreshToken): User;

    public function getAuthenticatedUserProfile($email) : User;

    public function register($username, $email, $password) : User;
}