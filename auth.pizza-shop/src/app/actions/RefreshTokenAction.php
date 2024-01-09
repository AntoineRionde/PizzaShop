<?php

namespace pizzashop\auth\api\app\actions;

use pizzashop\auth\api\domain\service\classes\AuthService;
use pizzashop\auth\api\domain\service\classes\JWTAuthService;
use pizzashop\auth\api\domain\service\classes\JWTManager;
use Psr\Container\ContainerInterface;

class RefreshTokenAction
{
    private JWTAuthService $jwtAuthService;

    public function __construct(ContainerInterface $container)
    {
        $this->jwtAuthService = $container->get('jwtAuthService');
    }
    public function __invoke($request, $response, $args)
    {
        $h = $request->getHeader('Authorization')[0];
        $refreshToken = sscanf($h, "Bearer %s")[0];

        $newTokens = $this->jwtAuthService->refresh($refreshToken);

        if ($newTokens) {
            return $response->withJson($newTokens, 200);
        }

        return $response->withJson(['error' => 'Invalid or expired token'], 401);
    }
}