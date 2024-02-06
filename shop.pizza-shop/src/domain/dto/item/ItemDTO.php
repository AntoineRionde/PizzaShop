<?php

namespace pizzashop\shop\domain\dto\item;

class ItemDTO
{

    public int $number;
    public string $label;
    public int $size;
    public string $labelSize;
    public float $price;

    public int $quantity;
    public string $orderId;

    public function __construct(int $number, string $label, int $size, string $labelSize, float $price, int $quantity, string $orderId)
    {
        $this->number = $number;
        $this->label = $label;
        $this->size = $size;
        $this->labelSize = $labelSize;
        $this->price = $price;
        $this->quantity = $quantity;
        $this->orderId = $orderId;
    }

    public function toArray()
    {
        return [
            'number' => $this->number,
            'label' => $this->label,
            'size' => $this->size,
            'labelSize' => $this->labelSize,
            'price' => $this->price,
            'quantity' => $this->quantity,
            'orderId' => $this->orderId
        ];
    }


}