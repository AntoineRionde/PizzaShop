<?php
declare(strict_types=1);

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

    $app->get('/orders/{id_order}[/]', AccessOrderAction::class)
        ->setName('access_order');

    $app->patch('/orders/{id_order}[/]', ValidateOrderAction::class)
        ->setName('validate_order');

    $app->get('produits[/]', GetProductsAction::class)
        ->setName('get_products');

    $app->get('produits/{id}[/]', GetProductAction::class)
        ->setName('get_product');

    $app->get('categories/{id_categorie}/produits[/]', GetProductsByCategorieAction::class)
        ->setName('get_products_by_categories');

    // routes gérant l'authentification avec l'API

    // mettre /api/ devant ? (sujet)
//    $app->post('/users/signin', SigninAction::class)
//        ->setName('sign_in');
//
//    $app->get('/api/users/validate', ValidateTokenAction::class)
//        ->setName('validate_token');
//
//    $app->post('/api/users/refresh', RefreshTokenAction::class)
//        ->setName('refresh_token');

    $app->get("/signin[/]", SigninAction::class)
        ->setName("signin");
};