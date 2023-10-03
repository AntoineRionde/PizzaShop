<?php

namespace pizzashop\shop\app\actions;

use Slim\Routing\RouteContext;

class HomeAction
{
    public function __invoke($request, $response, $args)
    {
        $response->getBody()->write("Welcome to the Pizza Shop API !");
        return $response;
    }
}