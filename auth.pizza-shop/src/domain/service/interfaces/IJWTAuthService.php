<?php

namespace pizzashop\auth\api\domain\service\interfaces;

use pizzashop\auth\api\domain\entities\auth\User;

interface IJWTAuthService
{
    public function signIn($email, $password): ?array;

    public function validate($accessToken): ?array;

    public function refresh($refreshToken): ?array;

    public function signUp($username, $email, $password): User;

    public function activate($activationToken): User;
}