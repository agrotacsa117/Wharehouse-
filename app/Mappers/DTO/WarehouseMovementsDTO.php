<?php

namespace App\Mappers\DTO;

class WarehouseMovementsDTO
{
    private string $folio;
    private int $warehouseInventoryId;
    private string $movementType;
    private int $quantity;
    private ?string $reason;
    private ?int $userId;
    private ?int $clientId;
    private ?string $invoiceSap;
    private ?string $operationDate;
    private ?int $sourceWarehouseId;
    private ?int $destinationWarehouseId;
    private ?string $newRack;
    private ?int $newLevel;

    public function __construct(
        string $folio,
        int $warehouseInventoryId,
        string $movementType,
        int $quantity,
        ?string $reason,
        ?int $userId,
        ?int $clientId = null,
        ?string $invoiceSap = null,
        ?string $operationDate = null,
        ?int $sourceWarehouseId = null,
        ?int $destinationWarehouseId = null,
        ?string $newRack = null,
        ?int $newLevel = null
    ) {
        $this->folio = $folio;
        $this->warehouseInventoryId = $warehouseInventoryId;
        $this->movementType = $movementType;
        $this->quantity = $quantity;
        $this->reason = $reason;
        $this->userId = $userId;
        $this->clientId = $clientId;
        $this->invoiceSap = $invoiceSap;
        $this->operationDate = $operationDate;
        $this->sourceWarehouseId = $sourceWarehouseId;
        $this->destinationWarehouseId = $destinationWarehouseId;
        $this->newRack = $newRack;
        $this->newLevel = $newLevel;
    }

    public function getFolio(): string
    {
        return $this->folio;
    }

    public function getWarehouseInventoryId(): int
    {
        return $this->warehouseInventoryId;
    }

    public function getMovementType(): string
    {
        return $this->movementType;
    }

    public function getQuantity(): int
    {
        return $this->quantity;
    }

    public function getReason(): ?string
    {
        return $this->reason;
    }

    public function getUserId(): ?int
    {
        return $this->userId;
    }

    public function getClientId(): ?int
    {
        return $this->clientId;
    }

    public function getInvoiceSap(): ?string
    {
        return $this->invoiceSap;
    }

    public function getOperationDate(): ?string
    {
        return $this->operationDate;
    }

    public function getSourceWarehouseId(): ?int
    {
        return $this->sourceWarehouseId;
    }

    public function getDestinationWarehouseId(): ?int
    {
        return $this->destinationWarehouseId;
    }

    public function getNewRack(): ?string
    {
        return $this->newRack;
    }

    public function getNewLevel(): ?int
    {
        return $this->newLevel;
    }

    public function isSale(): bool
    {
        return $this->movementType === 'SALE';
    }

    public function isTransfer(): bool
    {
        return $this->movementType === 'TRANSFER';
    }

    public function isRelocation(): bool
    {
        return $this->movementType === 'RELOCATION';
    }
}
