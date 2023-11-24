<?php

namespace pizzashop\auth\api\domain\service\interfaces;

interface IAuth
{
    public function verifyCredentials($username, $password);
    public function verifyRefreshToken($refreshToken);
    public function getAuthenticatedUserProfile($userId);
    public function register($username, $email, $password);
    public function activate($refreshToken);
}