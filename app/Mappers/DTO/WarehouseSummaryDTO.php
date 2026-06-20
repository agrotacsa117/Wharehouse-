<?php

namespace App\Mappers\DTO;

class WarehouseSummaryDTO
{
    private int $stockTotal;

    private int $productTotal;

    private int $totalExpiredInventory;

    public function __construct(
        int $stockTotal,
        int $productTotal,
        int $totalExpiredInventory
    ) {
        $this->stockTotal = $stockTotal;
        $this->productTotal = $productTotal;
        $this->totalExpiredInventory = $totalExpiredInventory;
    }

    public function getTotalExpiredInventory(): int
    {
        return $this->totalExpiredInventory;
    }

    // Getter para StockTotal
    public function getStockTotal(): int
    {
        return $this->stockTotal;
    }

    // Getter para ProductTotal
    public function getProductTotal(): int
    {
        return $this->productTotal;
    }
}
