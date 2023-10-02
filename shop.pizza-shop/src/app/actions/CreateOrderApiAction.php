<?php

namespace pizzashop\shop\app\actions;

use pizzashop\shop\domain\service\classes\OrderService;
use Slim\Psr7\Request;
use Slim\Psr7\Response;

class CreateOrderApiAction
{
    public function __invoke(Request $request, Response $response, array $args): Response
    {
        //TODO: Implement __invoke() method.
        return $response;
    }
}