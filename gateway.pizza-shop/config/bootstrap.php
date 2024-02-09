<?php

use DI\ContainerBuilder;
use Slim\Factory\AppFactory;

$settings = require_once __DIR__ . '/settings.php';


$builder = new ContainerBuilder();
$builder->addDefinitions($settings);

try {
    $c = $builder->build();
    $app = AppFactory::createFromContainer($c);
    $app->addRoutingMiddleware();
    $app->addErrorMiddleware(true, false, false);
    return $app;
} catch (Exception $e) {
    echo $e->getMessage();
}
