<?php

namespace pizzashop\gateway\app\action;

use Exception;
use GuzzleHttp\Exception\RequestException;
use Psr\Container\ContainerInterface;
use Slim\Psr7\Request;
use Slim\Psr7\Response;
class AccessOrdersAction extends AbstractAction
{
    public function __construct(ContainerInterface $container)
    {
        parent::__construct($container);
    }

    public function __invoke(Request $request, Response $response, array $args): Response|\Slim\Psr7\Message
    {
        $response = $this->addCorsHeaders($response);

        try {
            $ordersData = $this->sendGetRequest('http://api.pizza-shop:80/order');



            $orders_json = json_encode($ordersData);
            $response->getBody()->write($orders_json);
            return $response->withHeader('Content-Type', 'application/json')->withStatus(200);

        } catch (RequestException $e) {
            $response->getBody()->write(json_encode(['error' => $e->getMessage()]));
            return $response->withHeader('Content-Type', 'application/json')->withStatus($e->getResponse()->getStatusCode());
        } catch (Exception $e) {
            $response->getBody()->write(json_encode(['error' => $e->getMessage()]));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(500);
        }
    }
}
