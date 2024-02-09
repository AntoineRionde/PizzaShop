<?php

namespace pizzashop\gateway\app\actions;

use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\RequestOptions;
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
    protected function sendGetRequest(string $url, array $headers = []): array
    {
        $response = $this->httpClient->get($url, [
            RequestOptions::HEADERS => $headers
        ]);
        return json_decode($response->getBody(), true);
    }

    /**
     * @throws GuzzleException
     */
    protected function sendPostRequest(string $url, array $headers = [], array $data = [],): array
    {
        $response = $this->httpClient->post($url, [
            RequestOptions::HEADERS => $headers,
            RequestOptions::FORM_PARAMS => $data
        ]);
        return json_decode($response->getBody(), true);
    }

    /**
     * @throws GuzzleException
     */
    protected function sendPatchRequest(string $url): array
    {
        $response = $this->httpClient->patch($url);
        return json_decode($response->getBody(), true);
    }

}
