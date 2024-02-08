<?php

namespace pizzashop\shop\domain\service\interfaces;

use pizzashop\shop\domain\dto\catalog\ProductDTO;

interface ICatalog
{
    public function getProduct(int $id): ProductDTO;

    public function getProducts(): array;

    public function getProductByNumber(int $number): ProductDTO;

    public function getProductsByCategory(int $id_category): array;
}