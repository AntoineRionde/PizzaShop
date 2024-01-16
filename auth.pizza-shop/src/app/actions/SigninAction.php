<?php

namespace pizzashop\auth\api\app\actions;

use pizzashop\auth\api\domain\exceptions\CredentialsException;
use pizzashop\auth\api\domain\service\classes\AuthService;
use pizzashop\auth\api\domain\service\classes\JWTManager;
use Psr\Container\ContainerInterface;


class SigninAction extends AbstractAction
{

    private JWTManager $jwtManager;
    private AuthService $authProvider;

    public function __construct(ContainerInterface $container)
    {
        $this->authProvider = $container->get('auth.service');
        $this->jwtManager = $container->get('jwtmanager.service');

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
                $this->authProvider->verifyCredentials($email, $password);
                $user = $this->authProvider->getUserByEmail($email);
                $token = $this->jwtManager->createToken($user);
                $response->getBody()->write(json_encode(['token' => $token]));
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