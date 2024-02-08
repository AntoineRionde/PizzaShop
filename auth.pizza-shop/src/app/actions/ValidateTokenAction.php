<?php

namespace pizzashop\auth\api\app\actions;

use Exception;
use Firebase\JWT\BeforeValidException;
use Firebase\JWT\ExpiredException;
use Firebase\JWT\SignatureInvalidException;
use pizzashop\auth\api\domain\service\classes\JWTAuthService;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;
use Slim\Psr7\Message;
use Slim\Psr7\Response;
use UnexpectedValueException;

class ValidateTokenAction extends AbstractAction
{
    private JWTAuthService $jwtAuthService;

    public function __construct(ContainerInterface $container)
    {
        try {
            $this->jwtAuthService = $container->get('jwtauth.service');
        } catch (NotFoundExceptionInterface|ContainerExceptionInterface) {
            throw new Exception('Internal server error, please try again later.');
        }
    }

    public function __invoke($request, $response, $args): Response|Message
    {
        try {
            $h = $request->getHeader('Authorization')[0];
            $tokenstring = sscanf($h, "Bearer %s")[0];

            $userProfile = $this->jwtAuthService->validate($tokenstring);
            $response->getBody()->write(json_encode(['username' => $userProfile[0], 'email' => $userProfile[1]]));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(200);
        } catch (SignatureInvalidException|BeforeValidException|ExpiredException|UnexpectedValueException $e) {
            $response->getBody()->write(json_encode(['error' => $e]));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(401);
        }
    }
}