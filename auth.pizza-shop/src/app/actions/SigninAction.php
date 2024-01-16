<?php

namespace pizzashop\auth\api\app\actions;


use pizzashop\auth\api\domain\service\classes\AuthService;
use pizzashop\auth\api\domain\service\classes\JWTAuthService;
use pizzashop\auth\api\domain\service\classes\JWTManager;
use Slim\Routing\RouteContext;


class SigninAction extends AbstractAction
{

    private JWTManager $jwtManager;
    private AuthService $authProvider;

    public function __construct($jwtManager, $authProvider)
    {
        $this->jwtManager = $jwtManager;
        $this->authProvider = $authProvider;
    }

    public function __invoke($request, $response, $args)
    {
        $h = $request->getHeader('Authorization')[0];
        $tokenstring = sscanf($h, "Basic %s")[0];

        $payload = $this->jwtManager->validateToken($tokenstring);

        if (isset($payload->email)) {
            $user = $this->authProvider->getUserByEmail($payload->email);
            if ($user) {
                $tokenData = ['username' => $user->username, 'email' => $user->email];
                $accessToken = $this->jwtManager->createToken($tokenData);
                $refreshToken = $this->jwtManager->createToken(['refresh_token' => $user->refresh_token]);
                $tokens = ['access_token' => $accessToken, 'refresh_token' => $refreshToken];
                return $response->withJson($tokens, 200);
            }
            return $response->withJson(['error' => 'Invalid or expired token'], 401);
        }

    }

}