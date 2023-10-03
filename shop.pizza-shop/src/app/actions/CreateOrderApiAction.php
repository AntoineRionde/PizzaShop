<?php

namespace pizzashop\shop\app\actions;

use Exception;
use pizzashop\shop\domain\service\classes\OrderService;
use Slim\Psr7\Request;
use Slim\Psr7\Response;

class CreateOrderApiAction
{

    private OrderService $os;

    public function __construct(OrderService $os)
    {
        $this->os = $os;
    }

    public function __invoke(Request $request, Response $response, array $args): Response
    {
        try {
            $order = $this->os->createOrder($request->getParsedBody());
            $response->getBody()->write(json_encode($order));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(201);
        } catch (Exception $e) {
            $response->getBody()->write(json_encode(['error' => $e->getMessage()]));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
        }
    }
}