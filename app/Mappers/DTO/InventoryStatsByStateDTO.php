<?php

namespace App\Mappers\DTO;

class InventoryStatsByStateDTO implements \JsonSerializable
{
    private int $state;
    private int $totalStock;

    public function __construct(
        int $state,
        int $totalStock
    ) {
        $this->state = $state;
        $this->totalStock = $totalStock;
    }

    public function getState(): int
    {
        return $this->state;
    }

    public function getTotalStock(): int
    {
        return $this->totalStock;
    }

    public function jsonSerialize(): array
    {
        return [
            'state' => $this->state,
            'totalStock' => $this->totalStock
        ];
    }
}
