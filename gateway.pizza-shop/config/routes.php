<?php

declare(strict_types=1);

use pizzashop\gateway\app\action\HomeAction;
use pizzashop\gateway\app\action\AccessOrderAction;
use pizzashop\gateway\app\action\ValidateOrderAction;
use pizzashop\gateway\app\action\GetProductAction;
use pizzashop\gateway\app\action\GetProductsAction;
use pizzashop\gateway\app\action\GetProductsByCategoryAction;
use Slim\App;

return function (App $app): void {

    $app->get('/', HomeAction::class)
        ->setName('home');

    $app->get('/api/orders/{id_order}', AccessOrderAction::class)
        ->setName('access_order');

    $app->post('/api/orders/{id_order}/validate', ValidateOrderAction::class)
        ->setName('validate_order');

    $app->get('/api/products/{id}', GetProductAction::class)
        ->setName('get_product');

    $app->get('/api/products', GetProductsAction::class)
        ->setName('get_products');

    $app->get('/api/categories/{id_category}/products', GetProductsByCategoryAction::class)
        ->setName('get_products_by_category');
};
