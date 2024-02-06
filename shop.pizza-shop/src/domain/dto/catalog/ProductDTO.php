<?php

namespace pizzashop\shop\domain\dto\catalog;

use pizzashop\shop\domain\dto\DTO;

class ProductDTO extends DTO
{

    public int $number;
    public string $label;
    public string $category_label;
    public string $label_size;
    public object $prices;

    public string $href = '';

    public function __construct(int $number, string $label, string $category_label, string $label_size, object $prices)
    {
        $this->number = $number;
        $this->label = $label;
        $this->category_label = $category_label;
        $this->label_size = $label_size;
        $this->prices = $prices;
    }

    public function simplifyDto($baseUrl)
    {
        unset($this->prices);
        unset($this->label_size);
        unset($this->category_label);
        $this->href = $baseUrl . '/product/' . $this->number;
    }
}