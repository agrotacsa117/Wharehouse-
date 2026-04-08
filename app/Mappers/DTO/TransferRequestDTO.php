<?php

namespace App\Mappers\DTO;

class TransferRequestDTO
{
    private int $inventoryId;
    private int $originWarehouseId;
    private int $destinationWarehouseId;
    private int $quantity;
    private ?string $reason;
    private ?string $operationDate;

    public function __construct(
        int $inventoryId,
        int $originWarehouseId,
        int $destinationWarehouseId,
        int $quantity,
        ?string $reason = null,
        ?string $operationDate = null
    ) {
        $this->inventoryId = $inventoryId;
        $this->originWarehouseId = $originWarehouseId;
        $this->destinationWarehouseId = $destinationWarehouseId;
        $this->quantity = $quantity;
        $this->reason = $reason;
        $this->operationDate = $operationDate;
    }

    public function getInventoryId(): int
    {
        return $this->inventoryId;
    }

    public function getOriginWarehouseId(): int
    {
        return $this->originWarehouseId;
    }

    public function getDestinationWarehouseId(): int
    {
        return $this->destinationWarehouseId;
    }

    public function getQuantity(): int
    {
        return $this->quantity;
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
