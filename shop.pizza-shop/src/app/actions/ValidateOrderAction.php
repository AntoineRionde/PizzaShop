<?php

namespace pizzashop\shop\app\actions;

use Exception;
use PhpAmqpLib\Channel\AbstractChannel;
use PhpAmqpLib\Channel\AMQPChannel;
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
    private AbstractChannel|AMQPChannel $amqp;

    /**
     * @throws Exception
     */
    public function __construct(ContainerInterface $container)
    {
        try {
            $this->os = $container->get('order.service');
            $this->amqp = $container->get('amqp');
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

            $jsonOrder = json_encode($orderDTO->toArray());

            $message_queue = 'suivi_commandes';
            $message = new AMQPMessage($jsonOrder);

            $this->amqp->queue_declare($message_queue, false, true, false, false);
            $this->amqp->basic_publish($message, '', $message_queue);

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