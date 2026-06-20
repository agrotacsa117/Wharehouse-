<?php

namespace App\Mappers\DTO;

class ExpiredInventoryRankingItemDTO
{
    private int $id;

    private int $warehouseId;

    private string $productId;

    private string $rack;

    private int $level;

    private int $quantity;

    private string $lotNumber;

    private int $remainingDays;

    private int $rank;

    private string $warehouseName;

    public function __construct(
        int $id,
        int $warehouseId,
        string $productId,
        string $rack,
        int $level,
        int $quantity,
        string $lotNumber,
        int $remainingDays,
        int $rank,
        string $warehouseName
    ) {
        $this->id = $id;
        $this->warehouseId = $warehouseId;
        $this->productId = $productId;
        $this->rack = $rack;
        $this->level = $level;
        $this->quantity = $quantity;
        $this->lotNumber = $lotNumber;
        $this->remainingDays = $remainingDays;
        $this->rank = $rank;
        $this->warehouseName = $warehouseName;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getWarehouseId(): int
    {
        return $this->warehouseId;
    }

    public function getProductId(): string
    {
        return $this->productId;
    }

    public function getRack(): string
    {
        return $this->rack;
    }

    public function getLevel(): int
    {
        return $this->level;
    }

    public function getQuantity(): int
    {
        return $this->quantity;
    }

    public function getLotNumber(): string
    {
        return $this->lotNumber;
    }

    public function getRemainingDays(): int
    {
        return $this->remainingDays;
    }

    public function getRank(): int
    {
        return $this->rank;
    }

    public function getWarehouseName(): string
    {
        return $this->warehouseName;
    }

    public function jsonSerialize(): array
    {
        return [
            'id' => $this->id,
            'warehouseId' => $this->warehouseId,
            'productId' => $this->productId,
            'rack' => $this->rack,
            'level' => $this->level,
            'quantity' => $this->quantity,
            'lotNumber' => $this->lotNumber,
            'remainingDays' => $this->remainingDays,
            'rank' => $this->rank,
            'warehouseName' => $this->warehouseName,
        ];
    }
}
