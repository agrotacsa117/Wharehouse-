<?php

namespace App\Mappers\DTO;

class InventoryStatsByStateDTO implements \JsonSerializable
{
    private int $state;
    private int $totalStock;
    private string $warehouseName;

    public function __construct(
        int $state,
        int $totalStock,
        string $warehouseName = ''
    ) {
        $this->state = $state;
        $this->totalStock = $totalStock;
        $this->warehouseName = $warehouseName;
    }

    public function getWarehouseName(): string
    {
        return $this->warehouseName;
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
            'totalStock' => $this->totalStock,
            'warehouseName' => $this->warehouseName
        ];
    }
}
