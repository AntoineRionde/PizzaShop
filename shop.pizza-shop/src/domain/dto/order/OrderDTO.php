<?php

namespace pizzashop\shop\domain\dto\order;

use InvalidArgumentException;

class OrderDTO
{
    public string $id;
    public string $date;
    public string $livraisonType;
    public float $totalAmount;
    public string $clientMail;
    public int $delay;
    public array $items;

    public function __construct(string $id, string $date, string $livraisonType, float $totalAmount, string $clientMail, int $delay, array $items)
    {
        $this->id = $id;
        $this->date = $date;
        $this->livraisonType = $livraisonType;
        $this->totalAmount = $totalAmount;
        $this->clientMail = $clientMail;
        $this->delay = $delay;
        $this->items = $items;
    }

    public static function fromArray(array $array): OrderDTO
    {
        $keys = ['date', 'livraisonType', 'clientMail', 'delay'];
        foreach ($keys as $key) {
            if (!array_key_exists($key, $array)) {
                throw new InvalidArgumentException("Key $key is missing");
            }
        }
        return new OrderDTO(
            -1,
            $array['date'],
            $array['livraisonType'],
            0,
            $array['clientMail'],
            $array['delay'],
            $array['items'] ?? [
            (object)[
                'number' => 1,
                'size' => 1,
                'quantity' => 2
            ]
        ]
        );
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'date' => $this->date,
            'livraisonType' => $this->livraisonType,
            'totalAmount' => $this->totalAmount,
            'clientMail' => $this->clientMail,
            'delay' => $this->delay,
            'items' => $this->items
        ];
    }


}