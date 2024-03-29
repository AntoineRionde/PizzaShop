<?php

use DI\ContainerBuilder;
use Illuminate\Database\Capsule\Manager as Eloquent;
use Slim\Factory\AppFactory;

$settings = require_once __DIR__ . '/settings.php';
$actions = require_once __DIR__ . '/actions_dependencies.php';
$services = require_once __DIR__ . '/services_dependencies.php';

try {
    $eloquent = new Eloquent();
    $eloquent->addConnection(parse_ini_file(__DIR__ . '/auth.db.ini'));
    $eloquent->setAsGlobal();
    $eloquent->bootEloquent();
} catch (Exception $e) {
    echo $e->getMessage();
}

$builder = new ContainerBuilder();
$builder->addDefinitions($settings);
$builder->addDefinitions($actions);
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
