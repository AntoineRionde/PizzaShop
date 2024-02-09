<?php

namespace pizzashop\gateway\app\actions;

use Slim\Psr7\Request;
use Slim\Psr7\Response;

class HomeAction extends AbstractAction
{
    public function __invoke(Request $request, Response $response, array $args): Response
    {
        $response = $this->addCorsHeaders($response);

        $message = 'Welcome to the Pizza Shop API Gateway!';

        $response->getBody()->write($message);
        return $response->withHeader('Content-Type', 'text/plain')->withStatus(200);
    }
}
