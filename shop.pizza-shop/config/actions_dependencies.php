<?php

use pizzashop\shop\domain\dto\order\OrderDTO;
use pizzashop\shop\domain\service\classes\OrderService;
use Psr\Container\ContainerInterface;

return [
    'order.dto' => function (ContainerInterface $c) {
        return new OrderDTO();
    },

    'order.service' => function (ContainerInterface $c) {
        return new OrderService($c->get('commande.logger'), $c->get('order.dto'));
    },


];