<?php

declare(strict_types=1);


use pizzashop\gateway\app\actions\AccessOrderAction;
use Slim\App;

return function (App $app): void {

    $app->get('/orders[/]', AccessOrderAction::class)
        ->setName('access_order');
};