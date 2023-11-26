<?php

namespace pizzashop\auth\api\domain\service\classes;

class JWTAuthService
{
    private AuthService $authProvider;
    private JWTManager $jwtManager;

    public function __construct($authProvider, $jwtManager) {
        $this->authProvider = $authProvider;
        $this->jwtManager = $jwtManager;
    }

    public function signIn($username, $password) {
        $user = $this->authProvider->verifyCredentials($username, $password);
        if ($user) {
            $tokenData = ['username' => $user->username, 'email' => $user->email];
            $accessToken = $this->jwtManager->createToken($tokenData);
            $refreshToken = $this->jwtManager->createToken(['refresh_token' => $user->refresh_token]);
            return ['access_token' => $accessToken, 'refresh_token' => $refreshToken];
        }
        return null;
    }

    public function validate($accessToken) {
        $decodedToken = $this->jwtManager->validateToken($accessToken);
        if ($decodedToken && isset($decodedToken['upr'])) {
            return $decodedToken['upr'];
        }
        return null;
    }

    public function signup($username, $email, $password) {

        $existingUser = $this->authProvider->getUserByEmail($email);
        if ($existingUser) {
            return null;
        }
        $newUser = $this->authProvider->createUser($username, $email, $password);
        return $newUser;
    }

    public function activate($activationToken) {

        $user = $this->authProvider->verifyActivationToken($activationToken);

        if ($user) {
            $succes = $this->authProvider->activateUserAccount($user->id);

            if ($succes) {
                return true;
            } else {
                return false;
            }
        }
        return false;
    }
}