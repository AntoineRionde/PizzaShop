<?php

namespace pizzashop\auth\api\app\actions;


use pizzashop\auth\api\domain\service\classes\AuthService;
use pizzashop\auth\api\domain\service\classes\JWTAuthService;
use pizzashop\auth\api\domain\service\classes\JWTManager;

class ValidateTokenAction extends AbstractAction
{
    public function __invoke($request, $response, $args)
    {
        $authHeader = $request->getHeaderLine('Authorization');
        $token = substr($authHeader, 7);

        $jwtAuthService = new JWTAuthService(new AuthService($this->db), new JWTManager(getenv('JWT_SECRET'), 3600));
        $userProfile = $jwtAuthService->validate($token);

        if ($userProfile) {
            return $response->withJson($userProfile, 200);
        }

        return $response->withJson(['error' => 'Invalid or expired token'], 401);
    }
}