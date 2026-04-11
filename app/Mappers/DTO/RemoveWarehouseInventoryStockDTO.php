<?php

namespace App\Mappers\DTO;

class RemoveWarehouseInventoryStockDTO
{
    private int $warehouseInventoryId;
    private int $quantity;
    private string $reason;
    private string $movementType;
    private string $operationDate;
    private int $warehouseId;
    private string $rack;
    private int $level;
    private int $clientId;
    private int $invoiceId;



    public function __construct(
        int $warehouseInventoryId,
        int $quantity,
        string $reason
    ) {
        $this->warehouseInventoryId = $warehouseInventoryId;
        $this->quantity = $quantity;
        $this->reason = $reason;
    }

    public function setReason(string $reason): void
    {
        $this->reason = $reason;
    }

    public function getReason(): string
    {
        return $this->reason;
    }

    public function getWarehouseInventoryId(): int
    {
        return $this->warehouseInventoryId;
    }

    public function setWarehouseInventoryId(int $warehouseInventoryId): void
    {
        $this->warehouseInventoryId = $warehouseInventoryId;
    }

    public function getQuantity(): int
    {
        return $this->quantity;
    }

    public function setQuantity(int $quantity): void
    {
        $this->quantity = $quantity;
    }

}
