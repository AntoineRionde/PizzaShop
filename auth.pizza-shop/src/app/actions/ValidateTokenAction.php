<?php

namespace pizzashop\auth\api\app\actions;

use pizzashop\auth\api\domain\service\classes\AuthService;
use pizzashop\auth\api\domain\service\classes\JWTAuthService;
use pizzashop\auth\api\domain\service\classes\JWTManager;

class ValidateTokenAction
{
    public function __invoke($request, $response, $args)
    {
        $h = $request->getHeader('Authorization')[0];
        $tokenstring = sscanf($h, "Bearer %s")[0];
        $jwtAuthService = new JWTAuthService(new AuthService(), new JWTManager(getenv('JWT_SECRET'), 3600));
        $userProfile = $jwtAuthService->validate($tokenstring);
        if ($userProfile) {
            return $response->withJson($userProfile, 200);
        }
        return $response->withJson(['error' => 'Invalid or expired token'], 401);
    }
}