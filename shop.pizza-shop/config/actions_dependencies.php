<?php

use pizzashop\shop\domain\service\classes\OrderService;
use Psr\Container\ContainerInterface;

return [
    'order.service' => function (ContainerInterface $c) {
        return new OrderService();
    },
];