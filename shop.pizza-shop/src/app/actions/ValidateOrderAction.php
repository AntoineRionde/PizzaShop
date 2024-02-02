<?php

namespace pizzashop\shop\app\actions;

use Exception;
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;
use pizzashop\shop\domain\exception\OrderNotFoundException;
use pizzashop\shop\domain\exception\OrderRequestInvalidException;
use pizzashop\shop\domain\service\classes\OrderService;
use Psr\Container\ContainerInterface;

class ValidateOrderAction extends AbstractAction
{
    private OrderService $os;

    public function __construct(ContainerInterface $container)
    {
        $this->os = $container->get('order.service');
    }

    public function __invoke($request, $response, $args): \Slim\Psr7\Response|\Slim\Psr7\Message
    {
        $response = $this->addCorsHeaders($response);

        try {
            $this->os->validateOrder($args['id_order']);

            $connection = new AMQPStreamConnection('rabbitmq', 5672, 'admin', '@admin1#!');
            $channel = $connection->channel();

            $channel->queue_declare('nouvelles_commandes', false, false, false, false);
            $orderJson = $this->os->readOrder($args['id_order']);
            $jsonOrder = json_encode($orderJson->toArray());
            $message = new AMQPMessage($jsonOrder);
            $channel->basic_publish($message, '', 'nouvelles_commandes');

            $response->getBody()->write(json_encode(['etat' => 'validee']));
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