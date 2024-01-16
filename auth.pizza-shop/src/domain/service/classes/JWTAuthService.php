<?php

namespace pizzashop\auth\api\domain\service\classes;

use pizzashop\auth\api\domain\dto\auth\UserDTO;
use pizzashop\auth\api\domain\entities\auth\User;
use pizzashop\auth\api\domain\exceptions\CredentialsException;
use pizzashop\auth\api\domain\exceptions\TokenException;
use pizzashop\auth\api\domain\exceptions\UserException;
use pizzashop\auth\api\domain\service\interfaces\IJWTAuthService;

class JWTAuthService implements IJWTAuthService
{
    private AuthService $authProvider;
    private JWTManager $jwtManager;

    public function __construct($authProvider, $jwtManager) {
        $this->authProvider = $authProvider;
        $this->jwtManager = $jwtManager;
    }

    /**
     * @throws CredentialsException
     */
    public function signIn($email, $password): ?array
    {
        try {
            $user = $this->authProvider->verifyCredentials($email, $password);
            if ($user) {
                $tokenData = ['username' => $user->username, 'email' => $user->email];
                $accessToken = $this->jwtManager->createToken($tokenData);
                $refreshToken = $this->jwtManager->createToken(['refresh_token' => $user->refresh_token]);
                return ['access_token' => $accessToken, 'refresh_token' => $refreshToken];
            }
            return null;
        } catch (CredentialsException) {
            throw new CredentialsException();
        }
    }

    public function validate($accessToken) {
        $decodedToken = $this->jwtManager->validateToken($accessToken);
        if ($decodedToken && isset($decodedToken['upr'])) {
            return $decodedToken['upr'];
        }
        return new TokenException('Invalid access token');
    }

    /**
     * @throws UserException
     */
    public function signup($username, $email, $password): User
    {

        try {
            $user = $this->authProvider->createUser($username, $email, $password);
            if ($user) {
                $tokenData = ['username' => $user->username, 'email' => $user->email];
                $accessToken = $this->jwtManager->createToken($tokenData);
                $refreshToken = $this->jwtManager->createToken(['refresh_token' => $user->refresh_token]);
                $user->access_token = $accessToken;
                $user->refresh_token = $refreshToken;
                $user->save();
                return $this->authProvider->getAuthenticatedUserProfile($user->email);
            }
            throw new UserException('Error during user creation');
        } catch (UserException) {
            throw new UserException();
        }
    }

    /**
     * @throws UserException
     */
    public function activate($activationToken) : bool {

        $user = $this->authProvider->verifyActivationToken($activationToken);

        if ($user) {
            return $this->authProvider->activateUserAccount($user->id);
        }
        return false;
    }

    /**
     * @throws TokenException
     */
    public function refresh($refreshToken): ?array
    {
        try {
            $user = $this->authProvider->verifyRefreshToken($refreshToken);
            if ($user) {
                $tokenData = ['username' => $user->username, 'email' => $user->email];
                $accessToken = $this->jwtManager->createToken($tokenData);
                $refreshToken = $this->jwtManager->createToken(['refresh_token' => $user->refresh_token]);
                return ['access_token' => $accessToken, 'refresh_token' => $refreshToken];
            }
            return null;
        } catch (TokenException) {
            throw new TokenException();
        }
    }
}