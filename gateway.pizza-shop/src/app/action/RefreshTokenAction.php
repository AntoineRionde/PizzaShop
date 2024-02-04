<?php

namespace pizzashop\gateway\app\action;

use pizzashop\auth\api\domain\service\classes\JWTAuthService;
use Psr\Container\ContainerInterface;
use Slim\Psr7\Request;
use Slim\Psr7\Response;

class RefreshTokenAction extends AbstractAction
{
    private JWTAuthService $jwtAuthService;

    public function __construct(ContainerInterface $container)
    {
        $this->jwtAuthService = $container->get('jwtauth.service');
    }

    public function __invoke(Request $request, Response $response, array $args)
    {
        $response = $this->addCorsHeaders($response);

        try {

            $h = $request->getHeader('Authorization')[0];
            $refreshToken = sscanf($h, "Bearer %s")[0];

            $newTokens = $this->jwtAuthService->refresh($refreshToken);

            $credentialsData = $this->sendPostRequest('http://api.pizza-auth:82/api/users/refresh', ['access_token' => $newTokens['access_token'], 'refresh_token' => $newTokens['refresh_token']]);

            $credentials_json = json_encode($credentialsData);
            $response->getBody()->write($credentials_json);
            return $response->withHeader('Content-Type', 'application/json')->withStatus(200);

        } catch (Exception $e) {
            $response->getBody()->write(json_encode(['error' => $e->getMessage()]));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(404);
        }
    }
}