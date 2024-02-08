<?php

namespace pizzashop\shop\app\actions;

use Exception;
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;
use pizzashop\shop\domain\exception\OrderNotFoundException;
use pizzashop\shop\domain\exception\OrderRequestInvalidException;
use pizzashop\shop\domain\service\classes\OrderService;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;
use Slim\Psr7\Message;
use Slim\Psr7\Response;

class ValidateOrderAction extends AbstractAction
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
            $orderDTO = $this->os->validateOrder($args['id_order']);
            $links = array(
                "self" => array(
                    "href" => "/commandes/" . $args['id_order'] . "/"
                ),
                "valider" => array(
                    "href" => "/commandes/" . $args['id_order']
                )
            );
            $connection = new AMQPStreamConnection('rabbitmq', 5672, 'admin', '@admin1#!');
            $channel = $connection->channel();

            $channel->queue_declare('nouvelles_commandes', false, false, false, false);
            $jsonOrder = json_encode($orderDTO->toArray());
            $message = new AMQPMessage($jsonOrder);
            $channel->basic_publish($message, '', 'nouvelles_commandes');

            $orderReturn = $orderDTO->toArray() + $links;

            $response->getBody()->write(json_encode($orderReturn));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(200);
        } catch (OrderNotFoundException $e) {
            $response->getBody()->write(json_encode(['error' => $e->getMessage()]));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(404);
        } catch (OrderRequestInvalidException $e) {
            $response->getBody()->write(json_encode(['error' => $e->getMessage()]));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
        } catch (Exception $e) {
            $response->getBody()->write(json_encode(['error' => $e->getMessage()]));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(500);
        }
    }
}