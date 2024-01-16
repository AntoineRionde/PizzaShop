<?php

namespace pizzashop\gateway\app\action;

use Exception;
use Psr\Container\ContainerInterface;
use Slim\Psr7\Request;
use Slim\Psr7\Response;

class GetProductsByCategoryAction extends AbstractAction
{
    private string $baseUrl;

    public function __construct(ContainerInterface $container)
    {
        parent::__construct($container);
        $this->baseUrl = $container->get('baseUrl');
    }

    public function __invoke(Request $request, Response $response, array $args): Response
    {
        $response = $this->addCorsHeaders($response);

        try {
            $productsApiResponse = $this->sendGetRequest('http://pizza-shop.catalogue.db:3308/api/products/category/' . $args['id_category']);

            if ($productsApiResponse['status'] === 'success') {
                $products = $productsApiResponse['data'];

                foreach ($products as $product) {
                    $product->simplifyDto($this->baseUrl);
                }

                $products_json = json_encode($products);
                $response->getBody()->write($products_json);
                return $response->withHeader('Content-Type', 'application/json')->withStatus(200);
            } else {
                throw new Exception('Failed to fetch product details by category.');
            }
        } catch (Exception $e) {
            $response->getBody()->write(json_encode(['error' => $e->getMessage()]));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(404);
        }
    }
}
