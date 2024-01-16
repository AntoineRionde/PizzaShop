<?php

namespace pizzashop\gateway\app\action;

use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Psr\Container\ContainerInterface;
use Slim\Psr7\Request;
use Slim\Psr7\Response;

abstract class AbstractAction
{
    protected Client $httpClient;

    public function __construct(ContainerInterface $container)
    {
        $this->httpClient = new Client();
    }

    abstract public function __invoke(Request $request, Response $response, array $args);

    protected function addCorsHeaders(Response $response): Response
    {
        return $response->withHeader('Access-Control-Allow-Origin', '*')
            ->withHeader('Access-Control-Allow-Methods', 'GET, POST, OPTIONS, PUT, DELETE')
            ->withHeader('Access-Control-Allow-Headers', 'Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With')
            ->withHeader('Access-Control-Allow-Credentials', 'true')
            ->withHeader('Access-Control-Max-Age', '3600');
    }

    /**
     * @throws GuzzleException
     */
    protected function sendGetRequest(string $url): array
    {
        $response = $this->httpClient->get($url);
        return json_decode($response->getBody(), true);
    }


}
