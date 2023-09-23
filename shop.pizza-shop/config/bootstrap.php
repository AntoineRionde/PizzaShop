<?php

use DI\ContainerBuilder;
use Slim\Factory\AppFactory;

$builder = new ContainerBuilder();
$c = $builder->build();
$app = AppFactory::createFromContainer($c);

$container = $app->getContainer();