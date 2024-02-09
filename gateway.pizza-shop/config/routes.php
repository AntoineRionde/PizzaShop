<?php

declare(strict_types=1);

use pizzashop\gateway\app\actions\authActions\RefreshTokenAction;
use pizzashop\gateway\app\actions\authActions\SignInAction;
use pizzashop\gateway\app\actions\authActions\ValidateTokenAction;
use pizzashop\gateway\app\actions\HomeAction;
use pizzashop\gateway\app\actions\orderActions\AccessOrderAction;
use pizzashop\gateway\app\actions\orderActions\AccessOrdersAction;
use pizzashop\gateway\app\actions\orderActions\CreateOrderAction;
use pizzashop\gateway\app\actions\orderActions\ValidateOrderAction;
use pizzashop\gateway\app\actions\productActions\GetProductAction;
use pizzashop\gateway\app\actions\productActions\GetProductsAction;
use pizzashop\gateway\app\actions\productActions\GetProductsByCategoryAction;
use Slim\App;

return function (App $app): void {

    $app->get('/', HomeAction::class)
        ->setName('home');

    $app->post('/order[/]', CreateOrderAction::class)
        ->setName('create_order');

    $app->get('/order[/]', AccessOrdersAction::class)
        ->setName('access_orders');

    $app->get('/order/{id_order}', AccessOrderAction::class)
        ->setName('access_order');

    $app->patch('/order/{id_order}[/]', ValidateOrderAction::class)
        ->setName('validate_order');

    $app->get('/product/{id}', GetProductAction::class)
        ->setName('get_product');

    $app->get('/product[/]', GetProductsAction::class)
        ->setName('get_products');

    $app->get('/categorie/{id_category}/product', GetProductsByCategoryAction::class)
        ->setName('get_products_by_category');

    $app->post('/api/users/signin', SignInAction::class)
        ->setName('sign_in');

    $app->get('/api/users/validate', ValidateTokenAction::class)
        ->setName('validate_token');

    $app->post('/api/users/refresh', RefreshTokenAction::class)
        ->setName('refresh_token');

};
