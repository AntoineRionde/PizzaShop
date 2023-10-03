<?php
declare(strict_types=1);

use pizzashop\shop\app\actions\AccessOrderAction;
use pizzashop\shop\app\actions\AccessOrderApiAction;
use pizzashop\shop\app\actions\CreateOrderAction;
use pizzashop\shop\app\actions\CreateOrderApiAction;
use pizzashop\shop\app\actions\ValidateOrderApiAction;
use Slim\App;

return function( App $app):void {

    $app->get('/', function ($request, $response, $args) {
        $response->getBody()->write("Hello world!");
        return $response;
    });

    $app->post('/orders[/]', CreateOrderAction::class)
        ->setName('create_orders');

    $app->get('/orders/{id_order}[/]', AccessOrderAction::class)
        ->setName('order');

    $app->get('/api/create-order[/]', CreateOrderApiAction::class)
        ->setName('create_order_api');

    $app->get('/api/orders/{id_order}[/]', AccessOrderApiAction::class)
        ->setName('access_order_api');

    $app->patch('/api/orders/{id_order}[/]', ValidateOrderApiAction::class)
        ->setName('validate_order_api');
};