<?php

declare(strict_types=1);

use pizzashop\gateway\app\action\RefreshTokenAction;
use pizzashop\gateway\app\action\AccessOrdersAction;
use pizzashop\gateway\app\action\HomeAction;
use pizzashop\gateway\app\action\AccessOrderAction;
use pizzashop\gateway\app\action\GetProductAction;
use pizzashop\gateway\app\action\GetProductsAction;
use pizzashop\gateway\app\action\GetProductsByCategoryAction;
use Slim\App;

return function (App $app): void {

    $app->get('/', HomeAction::class)
        ->setName('home');

    $app->get('/order[/]', AccessOrdersAction::class)
        ->setName('access_orders');

    $app->get('/order/{id_order}', AccessOrderAction::class)
        ->setName('access_order');

    $app->get('/product/{id}', GetProductAction::class)
        ->setName('get_product');

    $app->get('/product[/]', GetProductsAction::class)
        ->setName('get_products');

    $app->get('/categorie/{id_category}/product', GetProductsByCategoryAction::class)
        ->setName('get_products_by_category');

    $app->post('/api/users/refresh', RefreshTokenAction::class)
        ->setName('refresh_token');
};
