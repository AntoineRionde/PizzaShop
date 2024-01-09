<?php

namespace pizzashop\shop\app\actions;

use Exception;
use pizzashop\shop\app\actions\AbstractAction;
use pizzashop\shop\domain\service\classes\CatalogService;
use Psr\Container\ContainerInterface;
use Slim\Psr7\Request;
use Slim\Psr7\Response;

class GetProductsByCategoryAction extends AbstractAction
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
            $products = $this->cs->getProductsByCategory((int)$args['id_category']);
            foreach ($products as $product) {
                $product->simplifyDto($this->baseUrl);
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