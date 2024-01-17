<?php
declare(strict_types=1);

use pizzashop\shop\app\actions\AccessOrdersAction;
use pizzashop\shop\app\actions\GetProductAction;
use pizzashop\shop\app\actions\GetProductsAction;
use pizzashop\shop\app\actions\GetProductsByCategoryAction;
use pizzashop\shop\app\actions\SigninAction;
use pizzashop\shop\app\actions\AccessOrderAction;
use pizzashop\shop\app\actions\CreateOrderAction;
use pizzashop\shop\app\actions\HomeAction;
use pizzashop\shop\app\actions\ValidateOrderAction;
use Slim\App;

return function( App $app):void {

    $app->get('/', HomeAction::class)
        ->setName('home');

    $app->post('/order[/]', CreateOrderAction::class)
        ->setName('create_order');

    $app->get('/order[/]', AccessOrdersAction::class)
        ->setName('access_orders');

    $app->get('/order/{id_order}[/]', AccessOrderAction::class)
        ->setName('access_order');

    $app->patch('/order/{id_order}[/]', ValidateOrderAction::class)
        ->setName('validate_order');

    $app->get('/product[/]', GetProductsAction::class)
        ->setName('get_products');

    $app->get('/product/{id}[/]', GetProductAction::class)
        ->setName('get_product');

    $app->get('/categorie/{id_category}/product[/]', GetProductsByCategoryAction::class)
        ->setName('get_products_by_categories');

    $app->get("/signin[/]", SigninAction::class)
        ->setName("signin");
};