<?php

namespace pizzashop\shop\app\actions;

use Exception;
use pizzashop\shop\domain\service\classes\OrderService;
use Psr\Container\ContainerInterface;

class AccessOrdersAction extends AbstractAction
{
    private OrderService $os;

    public function __construct(ContainerInterface $container)
    {
        $this->os = $container->get('order.service');
    }

    public function __invoke($request, $response, $args)
    {
        $response = $this->addCorsHeaders($response);

        try {
            $orders = $this->os->readAllOrders();
            $order_json = json_encode($orders);
            $response->getBody()->write($order_json);
            return $response->withHeader('Content-Type', 'application/json')->withStatus(200);

        } catch (Exception $e) {
            $response->getBody()->write(json_encode(['error' => $e->getMessage()]));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(404);
        }
    }
}
