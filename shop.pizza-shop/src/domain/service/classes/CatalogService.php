<?php

namespace pizzashop\shop\domain\service\classes;

use pizzashop\shop\domain\service\interfaces\ICatalog;

class CatalogService implements ICatalog
{
    private OrderService $orderService;

    public function __construct(OrderService $orderService)
    {
        $this->orderService = $orderService;
    }

    public function getProductInfo($id) {

    }
}