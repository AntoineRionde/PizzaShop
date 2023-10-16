<?php

use Psr\Container\ContainerInterface;
use Monolog\Handler\StreamHandler;

return [

    'commande.logger' => function(ContainerInterface $container ) {
        $logger = new Monolog\Logger('log.commande.name');
        $logger->pushHandler(new StreamHandler($container->get('log.commande.file'), $container->get('log.commande.level')));
    },
];