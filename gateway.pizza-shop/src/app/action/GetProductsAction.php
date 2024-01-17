<?php

namespace pizzashop\gateway\app\action;

use Exception;
use Psr\Container\ContainerInterface;
use Slim\Psr7\Request;
use Slim\Psr7\Response;

class GetProductsAction extends AbstractAction
{

    public function __construct(ContainerInterface $container)
    {
        parent::__construct($container);
    }

    public function __invoke(Request $request, Response $response, array $args): Response
    {
        $response = $this->addCorsHeaders($response);

        try {
            $productData = $this->sendGetRequest('http://api.pizza-shop:80/product');

            $products_json = json_encode($productData);
            $response->getBody()->write($products_json);
            return $response->withHeader('Content-Type', 'application/json')->withStatus(200);

        } catch (Exception $e) {
            $response->getBody()->write(json_encode(['error' => $e->getMessage()]));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(404);
        }
    }
}
