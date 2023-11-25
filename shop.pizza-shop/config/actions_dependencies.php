<?php

use pizzashop\shop\domain\service\classes\OrderService;
use Psr\Container\ContainerInterface;

return [
    'order.service' => function (ContainerInterface $c) {
        return new OrderService($c->get('catalog.service'), $c->get('order.logger'));
    },
];
