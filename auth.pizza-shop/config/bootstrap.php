<?php

use DI\ContainerBuilder;
use Slim\Factory\AppFactory;
use Illuminate\Database\Capsule\Manager as Eloquent;

$settings = require_once __DIR__ . '/settings.php';
$services = require_once __DIR__.'/services_dependencies.php';

$eloquent = new Eloquent();
$eloquent->addConnection(parse_ini_file(__DIR__ . '/auth.db.ini'), 'auth');
$eloquent->setAsGlobal();
$eloquent->bootEloquent();

$builder = new ContainerBuilder();
$builder->addDefinitions($settings);
$builder->addDefinitions($services);
try {
    $c = $builder->build();
    $app = AppFactory::createFromContainer($c);
    $app->addRoutingMiddleware();
    $app->addErrorMiddleware(true, false, false);
    return $app;
} catch (Exception $e) {
    echo $e->getMessage();
}
