<?php

namespace pizzashop\shop\domain\service\interfaces;

use pizzashop\shop\domain\dto\catalog\ProductDTO;

interface ICatalog
{
    public function getProduct(int $numero) : ProductDTO;

}