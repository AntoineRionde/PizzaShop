<?php

namespace pizzashop\shop\domain\service\classes;

use pizzashop\shop\domain\entities\catalog\Product;
use pizzashop\shop\domain\service\interfaces\ICatalog;

class CatalogService implements ICatalog
{

    public function readProduct(int $numero) {
        $product = Product::findOrFail($numero);
        return $product->toDTO();
    }
}