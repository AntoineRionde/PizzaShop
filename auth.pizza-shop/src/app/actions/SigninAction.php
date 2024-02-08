<?php

namespace pizzashop\auth\api\app\actions;

use Exception;
use pizzashop\auth\api\domain\exceptions\CredentialsException;
use pizzashop\auth\api\domain\service\classes\JWTAuthService;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;
use Slim\Psr7\Message;
use Slim\Psr7\Response;


class SigninAction extends AbstractAction
{

    private JWTAuthService $JWTAuthService;

    /**
     * @throws Exception
     */
    public function __construct(ContainerInterface $container)
    {
        try {
            $this->JWTAuthService = $container->get('jwtauth.service');
        } catch (NotFoundExceptionInterface|ContainerExceptionInterface) {
            throw new Exception('Internal server error, please try again later.');
        }
    }

    public function __invoke($request, $response, $args): Response|Message
    {
        if (!isset($request->getHeader('Authorization')[0])) {
            $response->getBody()->write(json_encode(['error' => 'Credentials not provided']));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(401);
        }

        try {
            $h = $request->getHeader('Authorization')[0];
            $tokenstring = sscanf($h, "Basic %s")[0];
            $tokenstring = base64_decode($tokenstring);
            $tokenstring = explode(':', $tokenstring);
            $email = $tokenstring[0];
            $password = $tokenstring[1];

            $response->getBody()->write(json_encode($this->JWTAuthService->signIn($email, $password)));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(200);
        } catch (CredentialsException) {
            $response->getBody()->write(json_encode(['error' => 'Invalid credentials']));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(401);
        }
    }
}