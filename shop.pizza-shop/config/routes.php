<?php
declare(strict_types=1);

use pizzashop\shop\app\actions\AccessOrderAction;
use pizzashop\shop\app\actions\CreateOrderAction;
use pizzashop\shop\app\actions\HomeAction;
use pizzashop\shop\app\actions\ValidateOrderAction;
use Slim\App;

return function( App $app):void {

    $app->get('/', HomeAction::class)
        ->setName('home');

    $app->post  ('/order[/]', CreateOrderAction::class)
        ->setName('create_order');

    $app->get('/orders/{id_order}[/]', AccessOrderAction::class)
        ->setName('access_order');

    $app->patch('/orders/{id_order}[/]', ValidateOrderAction::class)
        ->setName('validate_order');
};