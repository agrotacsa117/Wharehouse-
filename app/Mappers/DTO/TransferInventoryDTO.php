<?php

namespace App\Mappers\DTO;

class TransferInventoryDTO
{
    private int $inventoryId;

    private int $fromWarehouseId;

    private string $toWarehouseId;

    private string $rack;

    private int $level;

    private string $lotNumber;

    private int $quantity;

    private string $reason;

    private int $transferFolio;

    public function __construct(
        int $inventoryId,
        int $fromWarehouseId,
        string $toWarehouseId,
        string $rack,
        int $level,
        string $lotNumber,
        int $quantity,
        string $reason,
        int $transferFolio
    ) {
        $this->inventoryId = $inventoryId;
        $this->fromWarehouseId = $fromWarehouseId;
        $this->toWarehouseId = $toWarehouseId;
        $this->rack = $rack;
        $this->level = $level;
        $this->lotNumber = $lotNumber;
        $this->quantity = $quantity;
        $this->reason = $reason;
        $this->transferFolio = $transferFolio;
    }

    public function getTransferFolio(): int
    {
        return $this->transferFolio;
    }

    public function setTransferFolio(int $transferFolio): void
    {
        $this->transferFolio = $transferFolio;
    }

    public function getInventoryId(): int
    {
        return $this->inventoryId;
    }

    public function getFromWarehouseId(): int
    {
        return $this->fromWarehouseId;
    }

    public function getToWarehouseId(): string
    {
        return $this->toWarehouseId;
    }

    public function getRack(): string
    {
        return $this->rack;
    }

    public function getLevel(): int
    {
        return $this->level;
    }

    public function getLotNumber(): string
    {
        return $this->lotNumber;
    }

    public function getQuantity(): int
    {
        return $this->quantity;
    }

    public function getReason(): string
    {
        return $this->reason;
    }

    public function jsonSerialize(): array
    {
        return [
            'inventory_id' => $this->inventoryId,
            'from_warehouse_id' => $this->fromWarehouseId,
            'to_warehouse_id' => $this->toWarehouseId,
            'rack' => $this->rack,
            'level' => $this->level,
            'lot_number' => $this->lotNumber,
            'quantity' => $this->quantity,
            'reason' => $this->reason,
        ];
    }
}
