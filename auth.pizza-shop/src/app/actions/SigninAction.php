<?php

namespace pizzashop\auth\api\app\actions;


use pizzashop\auth\api\domain\service\classes\AuthService;
use pizzashop\auth\api\domain\service\classes\JWTAuthService;
use pizzashop\auth\api\domain\service\classes\JWTManager;

class SigninAction extends AbstractAction
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