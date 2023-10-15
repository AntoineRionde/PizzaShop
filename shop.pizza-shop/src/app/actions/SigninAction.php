<?php

namespace pizzashop\shop\app\actions;


use pizzashop\shop\domain\service\classes\AuthService;
use pizzashop\shop\domain\service\classes\JWTAuthService;
use pizzashop\shop\domain\service\classes\JWTManager;
use Slim\Routing\RouteContext;


class SigninAction
{
    public function __invoke($request, $response, $args)
    {
        $authHeader = $request->getHeaderLine('Authorization');
        list($username, $password) = explode(':', base64_decode(substr($authHeader, 6)));

        $jwtAuthService = new JWTAuthService(new AuthService($this->db), new JWTManager(getenv('JWT_SECRET'), 3600));

        $result = $jwtAuthService->signIn($username, $password);
        if ($result) {
            return $response->withJson($result, 200);
        }

        return $response->withJson(['error' => 'Invalid credentials'], 401);
    }

}