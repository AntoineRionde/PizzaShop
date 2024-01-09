<?php

namespace pizzashop\auth\api\domain\service\interfaces;

interface IJWTAuthService
{
    public function signIn($username, $password) : ?array;
    public function validate($accessToken);
    public function refresh($refreshToken) : ?array;
    public function signUp($username, $email, $password);
    public function activate($activationToken) : bool;
}