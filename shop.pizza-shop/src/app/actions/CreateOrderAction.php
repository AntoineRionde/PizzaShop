<?php

namespace pizzashop\shop\app\actions;

use Exception;
use pizzashop\shop\domain\exception\InternalServerException;
use pizzashop\shop\domain\exception\OrderRequestInvalidException;
use pizzashop\shop\domain\service\classes\OrderService;
use Slim\Psr7\Request;
use Slim\Psr7\Response;
use Slim\Routing\RouteContext;

class CreateOrderAction extends AbstractAction
{

    private OrderService $os;

    public function __construct(OrderService $os)
    {
        $this->os = $os;
    }

    public function __invoke(Request $request, Response $response, array $args): Response
    {

        $response = $this->addCorsHeaders($response);

        try {
            $order = $this->os->createOrder($request->getParsedBody());
            $response->getBody()->write(json_encode($order));

            $routeContext = RouteContext::fromRequest($request);
            $url = $routeContext->getRouteParser()->urlFor('accessOrder', ['id_order' => $order->getId()]);
            return $response->withHeader('Location', $url)->withStatus(201);

        } catch (OrderRequestInvalidException $e) {
            $response->getBody()->write(json_encode(['error' => $e->getMessage()]));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
        } catch (InternalServerException $e) {
            $response->getBody()->write(json_encode(['error' => $e->getMessage()]));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(500);
        }
    }
}