<?php

namespace pizzashop\shop\app\actions;

use Exception;
use pizzashop\shop\domain\service\classes\OrderService;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;
use Slim\Psr7\Message;
use Slim\Psr7\Response;

class AccessOrderAction extends AbstractAction
{
    private OrderService $os;


    /**
     * @throws Exception
     */
    public function __construct(ContainerInterface $container)
    {
        try {
            $this->os = $container->get('order.service');
        } catch (Exception|NotFoundExceptionInterface|ContainerExceptionInterface) {
            throw new Exception('Internal server error, please try again later.');
        }
    }

    public function __invoke($request, $response, $args): Response|Message
    {
        $response = $this->addCorsHeaders($response);

        try {
            $order = $this->os->readOrder($args['id_order']);

            $links = array(
                "self" => array(
                    "href" => "/commandes/" . $args['id_order'] . "/"
                ),
                "valider" => array(
                    "href" => "/commandes/" . $args['id_order'] . "/"
                )
            );

            $order = $order->toArray() + $links;
            $order_json = json_encode($order);
            $response->getBody()->write($order_json);
            return $response->withHeader('Content-Type', 'application/json')->withStatus(200);

        } catch (Exception $e) {
            $response->getBody()->write(json_encode(['error' => $e->getMessage()]));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(404);
        }
    }
}
