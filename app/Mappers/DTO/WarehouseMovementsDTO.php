<?php

namespace App\Mappers\DTO;

class WarehouseMovementsDTO
{
    private string $folio;
    private int $warehouseInventoryId;
    private string $movementType;
    private int $quantity;
    private string $reason;
    private int $userId;
    private ?\DateTime $operationDate;
    private ?int $sourceWarehouseId;

    public function __construct(
        string $folio,
        int $warehouseInventoryId,
        string $movementType,
        int $quantity,
        ?string $reason,
        ?int $userId
    ) {
        $this->folio = $folio;
        $this->warehouseInventoryId = $warehouseInventoryId;
        $this->movementType = $movementType;
        $this->quantity = $quantity;
        $this->reason = $reason;
        $this->userId = $userId;
        $this->operationDate = null;
        $this->sourceWarehouseId = null;
    }

    public function getOperationDate(): ?\DateTime
    {
        return $this->operationDate;
    }

    public function setOperationDate(\DateTime $operationDate): void
    {
        $this->operationDate = $operationDate;
    }

    public function getSourceWarehouseId(): ?int
    {
        return $this->sourceWarehouseId;
    }

    public function setSourceWarehouseId(int $sourceWarehouseId): void
    {
        $this->sourceWarehouseId = $sourceWarehouseId;
    }
    // =========================
    // GETTERS
    // =========================

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
}
