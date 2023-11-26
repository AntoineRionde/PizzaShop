<?php

namespace pizzashop\shop\app\actions;

use Slim\Psr7\Request;
use Slim\Psr7\Response;
use GuzzleHttp\Client;

class SigninAction extends AbstractAction
{

    public function __invoke(Request $request, Response $response, array $args)
    {
//        $response = $this->addCorsHeaders($response);

        $client = new Client([
            'base_uri' => 'http://docketu.iutnc.univ-lorraine.fr:16584',
            'timeout' => 2.0,
        ]);

        $response = $client->request('POST', '/api/users/signin', [
            'json' => [
                'username' => $request->getParsedBody()['username'],
                'password' => $request->getParsedBody()['password']
            ]
        ]);

        $response->getBody()->write(json_encode(['token' => json_decode($response->getBody()->getContents())->token]));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(200);

    }
}