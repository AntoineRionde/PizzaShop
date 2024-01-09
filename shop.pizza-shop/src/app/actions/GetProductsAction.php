<?php

namespace pizzashop\shop\app\actions;

use Exception;
use pizzashop\shop\domain\service\classes\CatalogService;
use Psr\Container\ContainerInterface;
use Slim\Psr7\Request;
use Slim\Psr7\Response;

class GetProductsAction extends AbstractAction
{

    private CatalogService $cs;
    private string $baseUrl;

    public function __construct(ContainerInterface $container)
    {
        $this->cs = $container->get('catalog.service');
        $this->baseUrl = $container->get('baseUrl');
    }


    public function __invoke(Request $request, Response $response, array $args)
    {
        $response = $this->addCorsHeaders($response);

        try {
            $products = $this->cs->getProducts();
            foreach ($products as $product) {
                $product->href = $this->baseUrl . '/product/' . $product->number;
                unset($product->price);
                unset($product->label_size);
                unset($product->category_label);

            }

            $products_json = json_encode($products);
            $response->getBody()->write($products_json);
            return $response->withHeader('Content-Type', 'application/json')->withStatus(200);
        } catch (Exception $e) {
            $response->getBody()->write(json_encode(['error' => $e->getMessage()]));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(404);
        }
    }
}