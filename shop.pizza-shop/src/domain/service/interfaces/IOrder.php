<?php
namespace pizzashop\shop\domain\service\interfaces;

use pizzashop\shop\domain\dto\order\OrderDTO;

interface IOrder
{
    public function createOrder(OrderDTO $orderDTO): OrderDTO;
    public function readOrder(String $id): OrderDTO;
    public function validateOrder(String $id): OrderDTO;
}