<?php

namespace pizzashop\auth\api\domain\service\classes;

use pizzashop\shop\domain\service\interfaces\IAuth;

class AuthService implements IAuth
{
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    public function verifyCredentials($username, $password)
    {
        $user = $this->db->table('users')->where('username', $username)->first();
        if ($user && password_verify($password, $user->password)) {
            return $user;
        }
        return null;
    }

    public function verifyRefreshToken($refreshToken)
    {
        $user = $this->db->table('users')->where('refresh_token', $refreshToken)->first();
        return $user ? $user : null;
    }

    public function getAuthenticatedUserProfile($userId)
    {
        return $this->db->table('users')->find($userId);
    }

    public function activate($refreshToken)
    {
        // TODO: Implement activate() method.
    }
}