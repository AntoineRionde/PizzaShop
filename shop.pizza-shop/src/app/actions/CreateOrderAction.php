<?php

namespace pizzashop\shop\app\actions;

use Exception;
use pizzashop\shop\domain\dto\order\OrderDTO;
use pizzashop\shop\domain\exception\InternalServerException;
use pizzashop\shop\domain\exception\OrderRequestInvalidException;
use pizzashop\shop\domain\service\classes\OrderService;
use Slim\Psr7\Request;
use Slim\Psr7\Response;
use Slim\Routing\RouteContext;

class CreateOrderAction extends AbstractAction
{

    private OrderService $os;
    private OrderDTO $orderDTO;

    public function __construct(OrderService $os, OrderDTO $orderDTO)
    {
        $this->os = $os;
        $this->orderDTO = $orderDTO;
    }

    public function __invoke(Request $request, Response $response, array $args): Response
    {

        $response = $this->addCorsHeaders($response);

        try {
            // Etendre l'action de création d'une commande dans l'api commandes pour vérifier la présence d'un
            //token JWT. En cas d'absence, retourner une réponse d'erreur avec un code 401.
            $token = $request->getHeader('Authorization')[0];
            if (empty($token)) {
                $response->getBody()->write(json_encode(['error' => 'Token absent']));
                return $response->withHeader('Content-Type', 'application/json')->withStatus(401);
            }
            else // sinon vérifier la validité du token. En cas d'erreur, retourner une réponse d'erreur avec un code 401. {
            {
                $client = new Client([
                    'base_uri' => 'http://docketu.iutnc.univ-lorraine.fr:16584',
                    'timeout' => 2.0,
                ]);

                try {
                    $response = $client->request('POST', '/api/users/validate', [
                        'json' => [
                            'token' => $token
                        ]
                    ]);
                }
                catch (Exception $e) {
                    $response->getBody()->write(json_encode(['error' => $e->getMessage()]));
                    return $response->withHeader('Content-Type', 'application/json')->withStatus(401);
                }
            }

            $orderDTO = $this->orderDTO->fromArray($request->getParsedBody());
            $order = $this->os->createOrder($orderDTO);
            $response->getBody()->write(json_encode($order));

            $routeContext = RouteContext::fromRequest($request);
            $url = $routeContext->getRouteParser()->urlFor('accessOrder', ['id_order' => $order->id]);


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