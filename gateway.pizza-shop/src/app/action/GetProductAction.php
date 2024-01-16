<?php

namespace pizzashop\gateway\app\action;

use Exception;
use Psr\Container\ContainerInterface;
use Slim\Psr7\Request;
use Slim\Psr7\Response;

class GetProductAction extends AbstractAction
{
    public function __construct(ContainerInterface $container)
    {
        parent::__construct($container);
    }

    public function __invoke(Request $request, Response $response, array $args): Response
    {
        $response = $this->addCorsHeaders($response);

        try {
            $productApiResponse = $this->sendGetRequest('http://pizza-shop.catalogue.db:3308/api/products/' . $args['id']);


            if ($productApiResponse['status'] === 'success') {
                $product = $productApiResponse['data'];

                $product_json = json_encode($product);
                $response->getBody()->write($product_json);
                return $response->withHeader('Content-Type', 'application/json')->withStatus(200);
            } else {
                throw new Exception('Product not found');
            }
        } catch (Exception $e) {
            $response->getBody()->write(json_encode(['error' => $e->getMessage()]));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(404);
        }
    }
}
