<?php

namespace pizzashop\shop\domain\service\classes;

use pizzashop\shop\domain\dto\catalog\ProductDTO;
use pizzashop\shop\domain\entities\catalog\Product;
use pizzashop\shop\domain\service\interfaces\ICatalog;

class CatalogService implements ICatalog
{

    public function getProduct(int $id): ProductDTO
    {
        $product = Product::findOrFail($id);
        return $product->toDTO();
    }

    public function getProducts(): array
    {
        $products = Product::all();
        $productsDTO = array();
        foreach ($products as $product) {
            $productsDTO[] = $product->toDTO();
        }
        return $productsDTO;
    }

    public function getProductByNumber(int $number): ProductDTO
    {
        $product = Product::where('numero', $number)->first();
        return $product->toDTO();
    }

    public function getProductsByCategory(int $id_category): array
    {
        $products = Product::where('categorie_id', $id_category)->get();
        $productsDTO = array();
        foreach ($products as $product) {
            $productsDTO[] = $product->toDTO();
        }
        return $productsDTO;
    }
}