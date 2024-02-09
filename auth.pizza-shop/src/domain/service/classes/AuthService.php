<?php

namespace pizzashop\auth\api\domain\service\classes;

use Carbon\Carbon;
use Exception;
use pizzashop\auth\api\domain\entities\auth\User;
use pizzashop\auth\api\domain\exceptions\CredentialsException;
use pizzashop\auth\api\domain\exceptions\TokenException;
use pizzashop\auth\api\domain\exceptions\UserException;
use pizzashop\auth\api\domain\service\interfaces\IAuth;

class AuthService implements IAuth
{
    /**
     * @throws CredentialsException
     */
    public function verifyCredentials($email, $password): User
    {
        try {
            $user = User::where('email', $email)->first() ?? throw new CredentialsException();
            if (password_verify($password, $user->password)) {
                return $user ?? throw new CredentialsException();
            }
            throw new CredentialsException();
        } catch (Exception) {
            throw new CredentialsException();
        }
    }

    /**
     * @throws TokenException
     */
    public function verifyRefreshToken($refreshToken): User
    {
        try {
            return User::where('refresh_token', $refreshToken)
                ->where('refresh_token_expiration_date', '>', Carbon::now())
                ->first() ?? throw new TokenException('Invalid refresh token');
        } catch (Exception) {
            throw new TokenException('Invalid refresh token');
        }
    }

    /**
     * @throws UserException
     */
    public function register($username, $email, $password): User
    {
        try {
            $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
            $activationToken = bin2hex(random_bytes(16));

            $user = [
                'username' => $username,
                'email' => $email,
                'password' => $hashedPassword,
                'activation_token' => $activationToken,
                'activation_token_expiration_date' => Carbon::now()->addMinutes(5)->toDateTimeString(),
            ];
            return User::create($user) ?? throw new UserException("Error during user creation");
        } catch (Exception) {
            throw new UserException("Error during user creation");
        }
    }

    /**
     * @throws UserException
     */
    public function getAuthenticatedUserProfile($email): User
    {
        try {
            return User::where('email', $email)->first() ?? throw new UserException("Error during user profile retrieval");
        } catch (Exception) {
            throw new UserException("Error during user profile retrieval");
        }
    }
}