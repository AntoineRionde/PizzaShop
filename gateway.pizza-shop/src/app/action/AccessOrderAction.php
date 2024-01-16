<?php

namespace pizzashop\gateway\app\action;


use Exception;
use GuzzleHttp\Exception\RequestException;
use Psr\Container\ContainerInterface;
use Slim\Psr7\Request;
use Slim\Psr7\Response;

class AccessOrderAction extends AbstractAction
{
    public function __construct(ContainerInterface $container)
    {
        parent::__construct($container);
    }

    public function __invoke(Request $request, Response $response, array $args): Response|\Slim\Psr7\Message
    {
        $response = $this->addCorsHeaders($response);

        try {

            $orderApiResponse = $this->sendGetRequest('pizza-shop.commande.db:3307/api/orders/' . $args['id_order']);

            if ($orderApiResponse['status'] == 'success') {
                $orderData = $orderApiResponse['data'];

                $links = array(
                    "self" => array(
                        "href" => "/commandes/" . $args['id_order'] . "/"
                    ),
                    "valider" => array(
                        "href" => "/commandes/" . $args['id_order']
                    )
                );

                $order = $orderData + $links;
                $order_json = json_encode($order);
                $response->getBody()->write($order_json);
                return $response->withHeader('Content-Type', 'application/json')->withStatus(200);
            } else {
                throw new Exception('Failed to fetch order details.');
            }

        } catch (RequestException $e) {
            $response->getBody()->write(json_encode(['error' => $e->getMessage()]));
            return $response->withHeader('Content-Type', 'application/json')->withStatus($e->getResponse()->getStatusCode());
        } catch (Exception $e) {
            $response->getBody()->write(json_encode(['error' => $e->getMessage()]));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(500);
        }
    }
}
