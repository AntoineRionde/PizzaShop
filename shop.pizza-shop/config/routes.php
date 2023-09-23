<?php
declare(strict_types=1);

use pizzashop\shop\app\actions\AccessOrderAction;
use pizzashop\shop\app\actions\CreateOrderAction;
use pizzashop\shop\app\actions\CreateOrderApiAction;

return function( \Slim\App $app):void {

    $app->post('/orders[/]', CreateOrderAction::class)
        ->setName('create_orders');

    $app->get('/orders/{id_order}[/]', AccessOrderAction::class)
        ->setName('order');

    $app->get('/api/create-order[/]', CreateOrderApiAction::class)
        ->setName('create_order_api');
};