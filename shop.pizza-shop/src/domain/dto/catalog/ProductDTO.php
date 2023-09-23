<?php

namespace pizzashop\shop\domain\dto\catalog;

use pizzashop\shop\domain\dto\DTO;

class ProductDTO extends DTO
{

    public int $product_number;
    public string $product_label;
    public string $category_label;
    public string $label_size;
    public $price;

    public function __construct(int $product_number, string $product_label, string $category_label, string $label_size, $price)
    {
        $this->product_number = $product_number;
        $this->product_label = $product_label;
        $this->category_label = $category_label;
        $this->label_size = $label_size;
        $this->price = $price;
    }

}