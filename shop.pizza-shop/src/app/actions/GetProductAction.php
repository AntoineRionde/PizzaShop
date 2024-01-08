<?php

namespace pizzashop\shop\app\actions;

use Exception;
use pizzashop\shop\domain\service\classes\CatalogService;
use Psr\Container\ContainerInterface;
use Slim\Psr7\Request;
use Slim\Psr7\Response;

class GetProductAction extends AbstractAction
{

    private CatalogService $cs;

    public function __construct(ContainerInterface $container)
    {
        $this->cs = $container->get('catalog.service');
    }


    public function __invoke(Request $request, Response $response, array $args)
    {
        $response = $this->addCorsHeaders($response);

        try {
            $product = $this->cs->getProduct($args['id']);
            $product_json = json_encode($product);
            $response->getBody()->write($product_json);
            return $response->withHeader('Content-Type', 'application/json')->withStatus(200);
        } catch (Exception $e) {
            $response->getBody()->write(json_encode(['error' => $e->getMessage()]));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(404);
        }
    }
}