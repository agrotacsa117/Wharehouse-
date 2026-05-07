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
    private int $userId;
    private string $lotNumber;


    public function __construct(
        int $warehouseInventoryId,
        int $quantity,
        string $reason
    ) {
        $this->warehouseInventoryId = $warehouseInventoryId;
        $this->quantity = $quantity;
        $this->reason = $reason;
    }

    function getLotNumber() : string {
        return $this->lotNumber;
    }
    public function getUserId(): int
    {
        return $this->userId;
    }

    public function setUserId(int $userId): void
    {
        $this->userId = $userId;
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

    public function getMovementType(): string
    {
        return $this->movementType;
    }

    public function setMovementType(string $movementType): void
    {
        $this->movementType = $movementType;
    }

    public function getOperationDate(): string
    {
        return $this->operationDate;
    }

    public function setOperationDate(string $operationDate): void
    {
        $this->operationDate = $operationDate;
    }

    public function getWarehouseId(): int
    {
        return $this->warehouseId;
    }

    public function setWarehouseId(int $warehouseId): void
    {
        $this->warehouseId = $warehouseId;
    }

    public function getRack(): string
    {
        return $this->rack;
    }

    public function setRack(string $rack): void
    {
        $this->rack = $rack;
    }

    public function getLevel(): int
    {
        return $this->level;
    }

    public function setLevel(int $level): void
    {
        $this->level = $level;
    }

    public function getClientId(): int
    {
        return $this->clientId;
    }

    public function setClientId(int $clientId): void
    {
        $this->clientId = $clientId;
    }

    public function getInvoiceId(): int
    {
        return $this->invoiceId;
    }

    public function setInvoiceId(int $invoiceId): void
    {
        $this->invoiceId = $invoiceId;
    }
}
