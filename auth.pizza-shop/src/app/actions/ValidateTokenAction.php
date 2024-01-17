<?php

namespace pizzashop\auth\api\app\actions;

use Firebase\JWT\BeforeValidException;
use Firebase\JWT\ExpiredException;
use Firebase\JWT\SignatureInvalidException;
use pizzashop\auth\api\domain\service\classes\AuthService;
use pizzashop\auth\api\domain\service\classes\JWTAuthService;
use pizzashop\auth\api\domain\service\classes\JWTManager;
use Psr\Container\ContainerInterface;
use UnexpectedValueException;

class ValidateTokenAction extends AbstractAction
{
    private JWTAuthService $jwtAuthService;

    public function __construct(ContainerInterface $container)
    {
        $this->jwtAuthService = $container->get('jwtauth.service');
    }

    public function __invoke($request, $response, $args)
    {
        $h = $request->getHeader('Authorization')[0] ;
        $tokenstring = sscanf($h, "Bearer %s")[0] ;
        try {
            $userProfile = $this->jwtAuthService->validate($tokenstring);
            if ($userProfile) {
                $response->getBody()->write(json_encode(['username' => $userProfile[0], 'email' => $userProfile[1]]));
                return $response->withHeader('Content-Type', 'application/json')->withStatus(200);
            }
        } catch (SignatureInvalidException|BeforeValidException|ExpiredException|UnexpectedValueException $e) {
            $response->getBody()->write(json_encode(['error' => $e]));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(401);
        }
        $response->getBody()->write(json_encode(['error' => 'Invalid or expired token']));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(401);
    }
}