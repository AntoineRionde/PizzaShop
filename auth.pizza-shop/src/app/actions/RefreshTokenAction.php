<?php

namespace pizzashop\auth\api\app\actions;

use Exception;
use pizzashop\auth\api\domain\exceptions\TokenException;
use pizzashop\auth\api\domain\service\classes\JWTAuthService;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;
use Slim\Psr7\Message;
use Slim\Psr7\Response;

class RefreshTokenAction extends AbstractAction
{
    private JWTAuthService $jwtAuthService;

    /**
     * @throws Exception
     */
    public function __construct(ContainerInterface $container)
    {
        try {
            $this->jwtAuthService = $container->get('jwtauth.service');
        } catch (Exception|NotFoundExceptionInterface|ContainerExceptionInterface) {
            throw new Exception('Internal server error, please try again later.');
        }
    }

    public function __invoke($request, $response, $args): Response|Message
    {
        try {
            $this->addCorsHeaders($response);
            $h = $request->getHeader('Authorization')[0];
            $refreshToken = sscanf($h, "Bearer %s")[0];
            $newTokens = $this->jwtAuthService->refresh($refreshToken);

            $response->getBody()->write(json_encode(['access_token' => $newTokens['access_token'], 'refresh_token' => $newTokens['refresh_token']]));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(200);
        } catch (TokenException $e) {
            $response->getBody()->write(json_encode(['error' => $e->getMessage()]));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(401);
        }
    }
}