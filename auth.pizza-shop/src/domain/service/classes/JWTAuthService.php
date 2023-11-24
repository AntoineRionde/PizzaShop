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

    public function refresh($refreshToken) {
        $user = $this->authProvider->verifyRefreshToken($refreshToken);
        if ($user) {
            return $this->signIn($user->username, $user->password);
        }
        return null;
    }

    public function signup($username, $email, $password) {
        // TODO later
    }

    public function activate($refreshToken) {
        // TODO later
    }
}