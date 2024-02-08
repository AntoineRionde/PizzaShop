<?php

namespace pizzashop\shop\app\actions;

use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use InvalidArgumentException;
use pizzashop\shop\domain\dto\order\OrderDTO;
use pizzashop\shop\domain\exception\CreationFailedException;
use pizzashop\shop\domain\service\classes\OrderService;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;
use Slim\Psr7\Request;
use Slim\Psr7\Response;
use Slim\Routing\RouteContext;

class CreateOrderAction extends AbstractAction
{

    private OrderService $os;
    private Client $authGuzzle;

    /**
     * @throws Exception
     */
    public function __construct(ContainerInterface $container)
    {
        try {
            $this->os = $container->get('order.service');

            $this->authGuzzle = $container->get('auth.guzzle');
        } catch (Exception|NotFoundExceptionInterface|ContainerExceptionInterface) {
            throw new Exception('Internal server error, please try again later.');
        }
    }

    public function __invoke(Request $request, Response $response, array $args): Response
    {

        $response = $this->addCorsHeaders($response);

        try {
            if ($request->getMethod() !== 'POST') {
                return $response->withHeader('Location', '/')->withStatus(302);
            }

            if (!$request->getHeader('Authorization')) {
                $response->getBody()->write(json_encode(['error' => 'Token absent']));
                return $response->withHeader('Content-Type', 'application/json')->withStatus(401);
            }
            $resp = $this->authGuzzle->request('GET', '/api/users/validate', [
                'headers' => [
                    'Authorization' => $request->getHeader('Authorization')[0],
                ]
            ]);

            if ($resp->getStatusCode() !== 200) {
                $response->getBody()->write($response->getBody());
                return $response->withHeader('Content-Type', 'application/json')->withStatus(401);
            }

            $orderDTO = OrderDTO::fromArray($request->getParsedBody());
            $order = $this->os->createOrder($orderDTO);
            $response->getBody()->write(json_encode($order));

            $routeContext = RouteContext::fromRequest($request);
            $url = $routeContext->getRouteParser()->urlFor('access_order', ['id_order' => $order->id]);
            return $response->withHeader('Location', $url)->withHeader('Content-Type', 'application/json')->withStatus(201);

        } catch (GuzzleException $e) {
            $response->getBody()->write(json_encode(['error' => $e->getMessage()]));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(500);
        } catch (CreationFailedException|InvalidArgumentException $e) {
            $response->getBody()->write(json_encode(['error' => $e->getMessage()]));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
        } catch (Exception $e) {
            $response->getBody()->write(json_encode(['error' => $e->getMessage()]));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(500);
        }
    }
}