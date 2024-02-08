<?php

use DI\ContainerBuilder;
use Illuminate\Database\Capsule\Manager as Eloquent;
use Slim\Factory\AppFactory;

$settings = require_once __DIR__ . '/settings.php';
$services = require_once __DIR__ . '/services_dependencies.php';
$actions = require_once __DIR__ . '/actions_dependencies.php';

$eloquent = new Eloquent();
$eloquent->addConnection(parse_ini_file(__DIR__ . '/catalog.db.ini'), 'catalog');
$eloquent->addConnection(parse_ini_file(__DIR__ . '/commande.db.ini'), 'commande');
$eloquent->setAsGlobal();
$eloquent->bootEloquent();

$builder = new ContainerBuilder();
$builder->addDefinitions($settings);
$builder->addDefinitions($services);
$builder->addDefinitions($actions);
try {
    $c = $builder->build();
    $app = AppFactory::createFromContainer($c);
    $app->addRoutingMiddleware();
    $app->addErrorMiddleware(true, false, false);
    return $app;
} catch (Exception $e) {
    echo $e->getMessage();
}
