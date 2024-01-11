<?php

namespace pizzashop\auth\api\app\actions;

use pizzashop\auth\api\domain\exceptions\TokenException;
use pizzashop\auth\api\domain\service\classes\JWTAuthService;
use Psr\Container\ContainerInterface;

class RefreshTokenAction extends AbstractAction
{
    private JWTAuthService $jwtAuthService;

    public function __construct(ContainerInterface $container)
    {
        $this->jwtAuthService = $container->get('jwtauth.service');
    }

    /**
     * @throws TokenException
     */
    public function __invoke($request, $response, $args)
    {
        $this->addCorsHeaders($response);
        $h = $request->getHeader('Authorization')[0];
        $refreshToken = sscanf($h, "Bearer %s")[0];

        $newTokens = $this->jwtAuthService->refresh($refreshToken);

        $token = "access:".$newTokens['access_token']." refresh:".$newTokens['refresh_token'];
        if ($newTokens) {
            $response = $response->withHeader('Authorization', 'Bearer ' . $token);
            return $response->withHeader('Content-Type', 'application/json')->withStatus(200);
        }

        return $response->withHeader('error', 'Invalid or expired token')->withStatus(401);
    }
}