<?php

namespace pizzashop\shop\domain\service\classes;

use pizzashop\shop\domain\dto\catalog\ProductDTO;
use pizzashop\shop\domain\entities\catalog\Product;
use pizzashop\shop\domain\service\interfaces\ICatalog;

class CatalogService implements ICatalog
{

    public function getProduct(int $numero) : ProductDTO {
        $product = Product::findOrFail($numero);
        return $product->toDTO();
    }
}