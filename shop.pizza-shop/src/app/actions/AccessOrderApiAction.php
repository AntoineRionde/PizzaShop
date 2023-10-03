<?php

namespace pizzashop\shop\app\actions;

use Exception;
use pizzashop\shop\domain\service\classes\OrderService;

class AccessOrderApiAction extends AbstractAction
{
    private OrderService $os;
    public function __construct(OrderService $os)
    {
        $this->os = $os;
    }

    public function __invoke($request, $response, $args)
    {
        try {
            $order = $this->os->readOrder($args['id_order']);
            $response->getBody()->write(json_encode($order));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(200);
        } catch (Exception $e) {
            $response->getBody()->write(json_encode(['error' => $e->getMessage()]));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(404);
        }
    }

}