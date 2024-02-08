<?php

namespace pizzashop\shop\app\actions;


use Slim\Psr7\Response;

class HomeAction extends AbstractAction
{
    public function __invoke($request, $response, $args): Response
    {
        $response->getBody()->write("Welcome to the Pizza Shop API !");
        return $response;
    }
}