<?php

namespace pizzashop\shop\app\actions;

use Exception;
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

    public function __invoke($request, $response, $args)
    {
        $response = $this->addCorsHeaders($response);

        try {
            $this->os->validateOrder($args['id_order']);
            // Connexion à RabbitMQ
            $connection = new AMQPStreamConnection('rabbitmq', 5672, 'admin', '@admin1#!');
            $channel = $connection->channel();

            // Déclaration de la queue
            $channel->queue_declare('nouvelles_commandes', false, false, false, false);
            // Valeur a modifier pour $jsonOrder (recuperer la commande et l'encoder en json)
            $jsonOrder = json_encode(['id_order' => $args['id_order']]);
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