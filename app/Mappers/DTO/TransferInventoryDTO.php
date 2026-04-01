<?php

namespace App\Mappers\DTO;
use JsonSerializable;

class TransferInventoryDTO implements JsonSerializable
{
    private int $inventoryId;
    private int $fromWarehouseId;
    private int $toWarehouseId;
    private string $rack;
    private int $level;
    private string $lotNumber;
    private int $quantity;
    private string $reason;

    public function __construct(
        int $inventoryId,
        int $fromWarehouseId,
        int $toWarehouseId,
        string $rack,
        int $level,
        string $lotNumber,
        int $quantity,
        string $reason
    ) {
        $this->inventoryId = $inventoryId;
        $this->fromWarehouseId = $fromWarehouseId;
        $this->toWarehouseId = $toWarehouseId;
        $this->rack = $rack;
        $this->level = $level;
        $this->lotNumber = $lotNumber;
        $this->quantity = $quantity;
        $this->reason = $reason;
    }

    public function getInventoryId(): int
    {
        return $this->inventoryId;
    }

    public function getFromWarehouseId(): int
    {
        return $this->fromWarehouseId;
    }

    public function getToWarehouseId(): int
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
            'inventoryId'     => $this->inventoryId,
            'fromWarehouseId' => $this->fromWarehouseId,
            'toWarehouseId'   => $this->toWarehouseId,
            'rack'            => $this->rack,
            'level'           => $this->level,
            'lotNumber'       => $this->lotNumber,
            'quantity'        => $this->quantity,
            'reason'          => $this->reason,
        ];
    }
}
