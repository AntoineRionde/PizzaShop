<?php

use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use pizzashop\shop\domain\service\classes\CatalogService;
use Psr\Container\ContainerInterface;

return [
    'log.order.name' => 'order.log',
    'log.order.file' => '/var/www/logs/order.log',
    'log.order.level' => Monolog\Logger::DEBUG,

    'catalog.service' => function (ContainerInterface $c) {
        return new CatalogService();
    },

    'order.logger' => function (ContainerInterface $container) {
        $logger = new Logger($container->get('log.order.name'));
        $logger->pushHandler(new StreamHandler($container->get('log.order.file'), $container->get('log.order.level')));
        return $logger;
    },

];
