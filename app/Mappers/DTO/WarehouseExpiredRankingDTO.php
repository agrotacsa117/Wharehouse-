<?php

namespace App\Mappers\DTO;

class WarehouseExpiredRankingDTO implements \JsonSerializable
{
    private int $warehouseId;
    private string $warehouseName;
    private array $expiredItems;

    public function __construct(
        int $warehouseId,
        string $warehouseName,
        array $expiredItems
    ) {
        $this->warehouseId = $warehouseId;
        $this->warehouseName = $warehouseName;
        $this->expiredItems = $expiredItems;
    }

    public function getWarehouseId(): int
    {
        return $this->warehouseId;
    }

    public function getWarehouseName(): string
    {
        return $this->warehouseName;
    }

    public function getExpiredItems(): array
    {
        return $this->expiredItems;
    }

    public function jsonSerialize(): array
    {
        return [
            'warehouseId' => $this->warehouseId,
            'warehouseName' => $this->warehouseName,
            'expiredItems' => $this->expiredItems
        ];
    }
}
