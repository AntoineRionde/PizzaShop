<?php

namespace pizzashop\auth\api\domain\service\interfaces;

interface IAuth
{
    public function verifyCredentials($email, $password);
    public function verifyRefreshToken($refreshToken);
    public function getAuthenticatedUserProfile($username, $email, $refreshToken);
    public function register($username, $email, $password);
    public function activate($refreshToken);
}