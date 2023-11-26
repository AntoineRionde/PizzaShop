<?php

namespace pizzashop\auth\api\domain\service\classes;

use Carbon\Carbon;
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

    public function getUserByEmail($email)
    {
        $user = $this->db->table('users')->where('email', $email)->first();
        return $user ? $user : null;
    }

    public function createUser($username, $email, $password)
    {
        $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
        $activationToken = bin2hex(random_bytes(16));

        $user = [
            'username' => $username,
            'email' => $email,
            'password' => $hashedPassword,
            'activation_token' => $activationToken,
            'activation_token_expiration_date' => Carbon::now()->addMinutes(5)->toDateTimeString(),
        ];

        $userId = $this->db->table('users')->insertGetId($user);
        return $userId ? $this->db->table('users')->find($userId) : null;
    }

    public function verifyActivationToken($activationToken)
    {
        $user = $this->db->table('users')
            ->where('activation_token', $activationToken)
            ->where('activation_token_expiration_date', '>', Carbon::now())
            ->first();
        return $user ? $user : null;
    }

    public function activateUserAccount($id)
    {
        return $this->db->table('users')->where('id', $id)->update([
            'active' => 1,
            'activation_token' => null,
            'activation_token_expiration_date' => null,
        ]);
    }
}