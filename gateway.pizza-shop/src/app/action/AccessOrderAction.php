<?php


namespace pizzashop\gateway\app\actions;

use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Psr\Container\ContainerInterface;

class AccessOrderAction extends \pizzashop\shop\app\actions\AbstractAction
{
    private Client $httpClient;

    public function __construct(ContainerInterface $container)
    {
        $this->httpClient = new Client();
    }

    public function __invoke($request, $response, $args)
    {
        $response = $this->addCorsHeaders($response);

        var_dump("lmklkml");

        try {

            $orderServiceUrl = 'http://api.pizza-shop';
            $orderResponse = $this->httpClient->get($orderServiceUrl . '/orders/' . $args['id_order']);
            $order = json_decode($orderResponse->getBody()->getContents(), true);
            $links = array(
                "self" => array(
                    "href" => "/commandes/" . $args['id_order'] . "/"
                ),
                "valider" => array(
                    "href" => "/commandes/" . $args['id_order']
                )
            );

            $order = $order + $links;
            $order_json = json_encode($order);
            $response->getBody()->write($order_json);
            return $response->withHeader('Content-Type', 'application/json')->withStatus(200);

        } catch (Exception $e) {
            $response->getBody()->write(json_encode(['error' => $e->getMessage()]));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(404);
        } catch (GuzzleException $e) {
        }
    }
}