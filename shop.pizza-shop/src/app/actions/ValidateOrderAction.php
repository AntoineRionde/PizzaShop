<?php

namespace pizzashop\shop\app\actions;

use Exception;
use pizzashop\shop\domain\exception\OrderNotFoundException;
use pizzashop\shop\domain\exception\OrderRequestInvalidException;
use pizzashop\shop\domain\service\classes\OrderService;

class ValidateOrderAction
{
    private OrderService $os;

    public function __construct(OrderService $os)
    {
        $this->os = $os;
    }

    public function __invoke($request, $response, $args)
    {
        try {
            if ($request->getParsedBody()['etat'] != 'validate') {
                throw new OrderRequestInvalidException();
            }
            $this->os->validateOrder($args['id_order']);
            $response->getBody()->write(json_encode(['message' => 'Order validated']));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(200);
        } catch (OrderNotFoundException $e) {
            $response->getBody()->write(json_encode(['error' => $e->getMessage()]));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(404);
        } catch (OrderRequestInvalidException $e) {
            $response->getBody()->write(json_encode(['error' => $e->getMessage()]));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
        } catch (Exception $e) {
            $response->getBody()->write(json_encode(['error' => $e->getMessage()]));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(500);
        }
    }
}