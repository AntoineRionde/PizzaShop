<?php

namespace pizzashop\shop\domain\dto\catalog;

use pizzashop\shop\domain\dto\DTO;

class ProductDTO extends DTO
{

    public int $number;
    public string $label;
    public string $category_label;
    public string $label_size;
    public $price;

    public string $href = '';

    public function __construct(int $number, string $label, string $category_label, string $label_size, $price)
    {
        $this->number = $number;
        $this->label = $label;
        $this->category_label = $category_label;
        $this->label_size = $label_size;
        $this->price = $price;
    }

    public function __set($href, $value)
    {
        if ($href == 'href') {
            $this->href = $value;
        }
     }
}