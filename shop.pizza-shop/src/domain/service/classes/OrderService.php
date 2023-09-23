<?php
namespace pizzashop\shop\domain\service\classes;
use pizzashop\shop\domain\dto\OrderDTO;
use pizzashop\shop\domain\exception\OrderServiceNotFoundException;
use pizzashop\shop\domain\service\interfaces\IOrder;

class OrderService implements IOrder
{
    private CatalogService $catalogService;

    public function __construct(CatalogService $catalogService)
    {
        $this->catalogService = $catalogService;
    }

    /**
     * @throws OrderServiceNotFoundException
     */
    public function readCommande(String $UUID): OrderDTO
    {
        if($UUID) {
            return new OrderDTO();
        }else{
            throw new OrderServiceNotFoundException();
        }
    }

    /**
     * @throws OrderServiceNotFoundException
     */
    public function validateCommande(String $UUID): OrderDTO
    {
        if($UUID) {
            return new OrderDTO();
        }else{
            throw new OrderServiceNotFoundException();
        }
    }


    public function createOrder(OrderDTO $orderDTO): void
    {
        // TODO: Implement createOrder() method.
    }

    public function validateOrder(string $id): void
    {
        // TODO: Implement validateOrder() method.
    }

    public function getOrder(string $id): OrderDTO
    {
        // TODO: Implement getOrder() method.
    }
}