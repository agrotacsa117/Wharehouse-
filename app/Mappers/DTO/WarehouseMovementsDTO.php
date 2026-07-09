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

    private ?int $transferFolio = null;

    private bool $isReversed;

    private ?int $reversedBy = null;

    private ?int $reversedOf = null;

    private bool $forceNegativeStock;

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
        $this->isReversed = false;
        $this->forceNegativeStock = false;
    }

    public function getTransferFolio(): ?int
    {
        return $this->transferFolio;
    }

    public function setTransferFolio(?int $transferFolio): void
    {
        $this->transferFolio = $transferFolio;
    }

    public function getOperationDate(): ?\DateTime
    {
        return $this->operationDate;
    }

    public function setOperationDate(?\DateTime $operationDate): void
    {
        $this->operationDate = $operationDate;
    }

    public function getSourceWarehouseId(): ?int
    {
        return $this->sourceWarehouseId;
    }

    public function setSourceWarehouseId(?int $sourceWarehouseId): void
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

    public function isReversed(): bool
    {
        return $this->isReversed;
    }

    public function setIsReversed(bool $isReversed): void
    {
        $this->isReversed = $isReversed;
    }

    public function getReversedBy(): ?int
    {
        return $this->reversedBy;
    }

    public function setReversedBy(?int $reversedBy): void
    {
        $this->reversedBy = $reversedBy;
    }

    public function getReversedOf(): ?int
    {
        return $this->reversedOf;
    }

    public function setReversedOf(?int $reversedOf): void
    {
        $this->reversedOf = $reversedOf;
    }

    /**
     * Obtiene el valor de forceNegativeStock.
     */
    public function getForceNegativeStock(): bool
    {
        return $this->forceNegativeStock;
    }

    /**
     * Establece el valor de forceNegativeStock.
     */
    public function setForceNegativeStock(bool $forceNegativeStock): void
    {
        $this->forceNegativeStock = $forceNegativeStock;
    }
}