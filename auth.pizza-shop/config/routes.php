<?php

declare(strict_types=1);

use pizzashop\auth\api\app\actions\RefreshTokenAction;
use pizzashop\auth\api\app\actions\SignInAction;
use pizzashop\auth\api\app\actions\ValidateTokenAction;
use Slim\App;

return function (App $app): void {

    $app->get('/', function ($response) {
        $response->getBody()->write("Welcome to the auth.pizza-shop API!");
        return $response;
    });

    $app->post('/api/users/signin', SignInAction::class)
        ->setName('sign_in');

    $app->get('/api/users/validate', ValidateTokenAction::class)
        ->setName('validate_token');

    $app->post('/api/users/refresh', RefreshTokenAction::class)
        ->setName('refresh_token');
};