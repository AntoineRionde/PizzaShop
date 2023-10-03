<?php

namespace pizzashop\shop\app\actions;

use Exception;
use pizzashop\shop\domain\service\classes\OrderService;

class ValidateOrderApiAction
{
    private OrderService $os;

    public function __construct(OrderService $os)
    {
        $this->os = $os;
    }

    public function __invoke($request, $response, $args)
    {
        try {
            $this->os->validateOrder($args['id_order']);
            $response->getBody()->write(json_encode(['message' => 'Order validated']));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(200);
        } catch (Exception $e) {
            $response->getBody()->write(json_encode(['error' => $e->getMessage()]));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(404);
        }
    }
}