<?php
namespace pizzashop\shop\domain\service\interfaces;

use pizzashop\shop\domain\dto\order\OrderDTO;

interface IOrder
{
    public function readOrder(String $id): OrderDTO;

    public function createOrder(OrderDTO $orderDTO): void;
    
    public function validateOrder(String $id): OrderDTO;
}