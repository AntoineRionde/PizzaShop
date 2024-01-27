<?php

namespace pizzashop\auth\api\app\actions;

use pizzashop\auth\api\domain\exceptions\CredentialsException;
use pizzashop\auth\api\domain\service\classes\AuthService;
use pizzashop\auth\api\domain\service\classes\JWTAuthService;
use pizzashop\auth\api\domain\service\classes\JWTManager;
use Psr\Container\ContainerInterface;


class SigninAction extends AbstractAction
{

    private JWTAuthService $JWTAuthService;

    public function __construct(ContainerInterface $container)
    {
        $this->JWTAuthService = $container->get('jwtauth.service');

    }

    /**
     * @throws CredentialsException
     */
    public function __invoke($request, $response, $args)
    {
        $h = $request->getHeader('Authorization')[0];
        $tokenstring = sscanf($h, "Basic %s")[0];
        $tokenstring = base64_decode($tokenstring);
        $tokenstring = explode(':', $tokenstring);
        $email = $tokenstring[0];
        $password = $tokenstring[1];

        if (isset ($email)) {
            try {
                $response->getBody()->write(json_encode($this->JWTAuthService->signIn($email, $password)));
                return $response->withHeader('Content-Type', 'application/json')->withStatus(200);
            } catch (CredentialsException) {
                $response->getBody()->write(json_encode(['error' => 'Invalid credentials']));
                return $response->withHeader('Content-Type', 'application/json')->withStatus(401);
            }
        }
        $response->getBody()->write(json_encode(['error' => 'Invalid credentials']));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(401);
    }
}