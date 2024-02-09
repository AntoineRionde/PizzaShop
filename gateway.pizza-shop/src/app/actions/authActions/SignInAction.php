<?php

namespace pizzashop\gateway\app\actions\authActions;

use Exception;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Exception\RequestException;
use pizzashop\gateway\app\actions\AbstractAction;
use Psr\Container\ContainerInterface;
use Slim\Psr7\Message;
use Slim\Psr7\Request;
use Slim\Psr7\Response;

class SignInAction extends AbstractAction
{

    /**
     * @throws Exception
     */
    public function __construct(ContainerInterface $container)
    {
        parent::__construct($container);
    }

    public function __invoke(Request $request, Response $response, array $args): Response|Message
    {
        $this->addCorsHeaders($response);

        if (!isset($request->getHeader('Authorization')[0])) {
            $response->getBody()->write(json_encode(['error' => 'Credentials not provided']));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(401);
        }

        try {
            $h = $request->getHeader('Authorization')[0];
            $tokenstring = sscanf($h, "Basic %s")[0];

            $credentialsData = $this->sendPostRequest('http://api.pizza-auth:80/api/users/signin',['Authorization' => 'Basic ' . $tokenstring]);

            $credentials_json = json_encode($credentialsData);
            $response->getBody()->write($credentials_json);
            return $response->withHeader('Content-Type', 'application/json')->withStatus(200);

        } catch (RequestException $e) {
            $response->getBody()->write(json_encode(['error' => $e->getMessage()]));
            return $response->withHeader('Content-Type', 'application/json')->withStatus($e->getResponse()->getStatusCode());
        } catch (Exception | GuzzleException $e) {
            $response->getBody()->write(json_encode(['error' => $e->getMessage()]));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(500);
        }
    }
}