<?php

namespace pizzashop\gateway\app\action;

use Exception;
use Psr\Container\ContainerInterface;
use Slim\Psr7\Request;
use Slim\Psr7\Response;

class ValidateOrderAction extends AbstractAction
{
    public function __construct(ContainerInterface $container)
    {
        parent::__construct($container);
    }

    public function __invoke(Request $request, Response $response, array $args): Response
    {
        $response = $this->addCorsHeaders($response);

        try {
            $orderApiResponse = $this->sendGetRequest('http://pizza-shop.commande.db:3307/api/orders/' . $args['id_order']);

            if ($orderApiResponse['status'] === 'success') {

                $this->os->validateOrder($args['id_order']);

                $response->getBody()->write(json_encode(['etat' => 'validee']));
                return $response->withHeader('Content-Type', 'application/json')->withStatus(200);
            } else {
                throw new Exception('Failed to fetch order details.');
            }
        } catch (Exception $e) {
            $response->getBody()->write(json_encode(['error' => $e->getMessage()]));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(500);
        }
    }
}
