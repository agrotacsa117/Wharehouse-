<?php

namespace App\Mappers\DTO;

class RelocationRequestDTO
{
    private int $inventoryId;
    private int $warehouseId;
    private int $destinationWarehouseId;
    private int $quantity;
    private string $newRack;
    private int $newLevel;
    private ?string $reason;
    private ?string $operationDate;

    public function __construct(
        int $inventoryId,
        int $warehouseId,
        int $destinationWarehouseId,
        int $quantity,
        string $newRack,
        int $newLevel,
        ?string $reason = null,
        ?string $operationDate = null
    ) {
        $this->inventoryId = $inventoryId;
        $this->warehouseId = $warehouseId;
        $this->destinationWarehouseId = $destinationWarehouseId;
        $this->quantity = $quantity;
        $this->newRack = $newRack;
        $this->newLevel = $newLevel;
        $this->reason = $reason;
        $this->operationDate = $operationDate;
    }

    public function getInventoryId(): int
    {
        return $this->inventoryId;
    }

    public function getWarehouseId(): int
    {
        return $this->warehouseId;
    }

    public function getDestinationWarehouseId(): int
    {
        return $this->destinationWarehouseId;
    }

    public function getQuantity(): int
    {
        return $this->quantity;
    }

    public function getNewRack(): string
    {
        return $this->newRack;
    }

    public function getNewLevel(): int
    {
        return $this->newLevel;
    }

    public function getReason(): ?string
    {
        return $this->reason;
    }

    public function getOperationDate(): ?string
    {
        return $this->operationDate;
    }
}
