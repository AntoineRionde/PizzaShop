<?php
namespace pizzashop\shop\domain\service\interfaces;

use pizzashop\shop\domain\dto\OrderDTO;
interface IOrder
{

    public function createOrder(OrderDTO $orderDTO): void;
    
    public function validateOrder(String $id): void;

    public function getOrder(String $id): OrderDTO;

}