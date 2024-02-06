<?php

namespace pizzashop\auth\api\domain\service\classes;


use Carbon\Carbon;
use pizzashop\auth\api\domain\entities\auth\User;
use pizzashop\auth\api\domain\exceptions\CredentialsException;
use pizzashop\auth\api\domain\exceptions\TokenException;
use pizzashop\auth\api\domain\exceptions\UserException;
use pizzashop\auth\api\domain\service\interfaces\IJWTAuthService;
use Random\RandomException;

class JWTAuthService implements IJWTAuthService
{
    private AuthService $authProvider;
    private JWTManager $jwtManager;
    private string|array|false $tokenLifetime;

    public function __construct($authProvider, $jwtManager)
    {
        $this->authProvider = $authProvider;
        $this->jwtManager = $jwtManager;
        $this->tokenLifetime = getenv('JWT_LIFETIME');

    }

    /**
     * @throws CredentialsException|TokenException
     */
    public function signIn($email, $password): ?array
    {
        $user = $this->authProvider->verifyCredentials($email, $password);
        return $this->createTokenPair($user);
    }

    /**
     * @throws TokenException
     */
    private function createTokenPair(User $user): array
    {
        try {
            $tokenData = ['username' => $user->username, 'email' => $user->email];
            $accessToken = $this->jwtManager->createToken($tokenData);
            $refreshToken = $this->jwtManager->createToken(bin2hex(random_bytes(10)));
            $user->refresh_token = $refreshToken;
            $user->refresh_token_expiration_date = Carbon::now()->addMinutes($this->tokenLifetime)->toDateTimeString();
            $user->save();
            return ['access_token' => $accessToken, 'refresh_token' => $refreshToken];
        } catch (RandomException) {
            throw new TokenException('Error during token creation');
        }
    }

    public function validate($accessToken): ?array
    {
        return $this->jwtManager->validateToken($accessToken);
    }

    /**
     * @throws UserException|TokenException
     */
    public function signUp($username, $email, $password): User
    {
        $user = $this->authProvider->register($username, $email, $password);
        $this->createTokenPair($user);
        return $this->authProvider->getAuthenticatedUserProfile($user->email);
    }

    /**
     * @throws UserException
     */
    public function activate($activationToken): User
    {
        try {
        $email = $activationToken->upr->email;
        $user = $this->authProvider->getAuthenticatedUserProfile($email);
        $user->activation_token = null;
        $user->activation_token_expiration_date = null;
        $user->active = 1;
        $user->save();
        return $user;
        } catch (UserException) {
            throw new UserException('Error during user activation');
        }
    }

    /**
     * @throws TokenException
     */
    public function refresh($refreshToken): ?array
    {
        $user = $this->authProvider->verifyRefreshToken($refreshToken);
        return $this->createTokenPair($user);
    }
}