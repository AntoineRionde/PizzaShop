<?php

use GuzzleHttp\Client;
use PhpAmqpLib\Connection\AMQPStreamConnection;
use pizzashop\shop\domain\service\classes\CatalogService;
use pizzashop\shop\domain\service\classes\OrderService;
use Psr\Container\ContainerInterface;

return [
    'order.service' => function (ContainerInterface $c) {
        return new OrderService($c->get('catalog.service'), $c->get('order.logger'));
    },
    'catalog.service' => function () {
        return new CatalogService();
    },
    'auth.guzzle' => function () {
        return new Client([
            'base_uri' => "http://api.pizza-auth",
            'timeout' => 2.0,
        ]);
    },
    'amqp' => function () {
        $connection = new AMQPStreamConnection('rabbitmq', 5672, 'admin', '@admin1#!');
        return $connection->channel();
    },
];
