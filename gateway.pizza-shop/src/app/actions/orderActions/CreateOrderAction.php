<?php

namespace pizzashop\gateway\app\actions\orderActions;

use Exception;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Exception\RequestException;
use pizzashop\gateway\app\actions\AbstractAction;
use Psr\Container\ContainerInterface;
use Slim\Psr7\Message;
use Slim\Psr7\Request;
use Slim\Psr7\Response;

class CreateOrderAction extends AbstractAction
{
    public function __construct(ContainerInterface $container)
    {
        parent::__construct($container);
    }

    public function __invoke(Request $request, Response $response, array $args): Response|Message
    {
        $response = $this->addCorsHeaders($response);

        try {
            if (!isset($request->getHeader('Authorization')[0])) {
                throw new Exception('No token provided');
            }
            $h = $request->getHeader('Authorization')[0];
            $refreshToken = sscanf($h, "Bearer %s")[0];
            $ordersData = $this->sendPostRequest('http://api.pizza-shop:80/order',
                ['Authorization' => 'Bearer ' . $refreshToken],
                $request->getParsedBody());

            $orders_json = json_encode($ordersData);
            $response->getBody()->write($orders_json);
            return $response->withHeader('Content-Type', 'application/json')->withStatus(201);

        } catch (RequestException $e) {
            $response->getBody()->write(json_encode(['error' => $e->getMessage()]));
            return $response->withHeader('Content-Type', 'application/json')->withStatus($e->getResponse()->getStatusCode());
        } catch (Exception|GuzzleException $e) {
            $response->getBody()->write(json_encode(['error' => $e->getMessage()]));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(500);
        }
    }
}
