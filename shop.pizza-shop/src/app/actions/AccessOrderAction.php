<?php

namespace pizzashop\shop\app\actions;

use Exception;
use pizzashop\shop\domain\service\classes\OrderService;
use Slim\Routing\RouteContext;

class AccessOrderAction extends AbstractAction
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

            $links = array(
                "self" => array(
                    "href" => "/commandes/" . $args['id_order'] . "/"
                ),
                "valider" => array(
                    "href" => "/commandes/" . $args['id_order']
                )
            );

            $order = $order->toArray() + $links;
            $order_json = json_encode($order);
            $response->getBody()->write($order_json);
            return $response->withHeader('Content-Type', 'application/json')->withStatus(302);

        } catch (Exception $e) {
            $response->getBody()->write(json_encode(['error' => $e->getMessage()]));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(404);
        }
    }
}
