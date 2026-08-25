<?php

namespace App\Mappers\DTO;

class RemoveWarehouseInventoryStockDTO
{
    private int $warehouseInventoryId;

    private int $quantity;

    private string $reason;

    private string $movementType;

    private string $operationDate = '';

    private int $warehouseId = 0;

    private ?int $rack = null;

    private ?int $level = null;

    private int $invoiceId = 0;

    private int $userId;

    private ?int $module = null;

    private ?int $bay = null;

    private ?int $platform = null;

    private bool $forceNegativeStock;

    private ?string $manufacturingDate;

    public function __construct(
        int $warehouseInventoryId,
        int $quantity,
        string $reason
    ) {
        $this->warehouseInventoryId = $warehouseInventoryId;
        $this->quantity = $quantity;
        $this->reason = $reason;
        $this->forceNegativeStock = false;
        $this->manufacturingDate = null;
    }

    // Getter y Setter para $module
    public function getModule(): ?int
    {
        return $this->module;
    }

    public function setModule(?int $module): void
    {
        $this->module = $module;
    }

    // Getter y Setter para $bay
    public function getBay(): ?int
    {
        return $this->bay;
    }

    public function setBay(?int $bay): void
    {
        $this->bay = $bay;
    }

    // Getter y Setter para $platform
    public function getPlatform(): ?int
    {
        return $this->platform;
    }

    public function setPlatform(?int $platform): void
    {
        $this->platform = $platform;
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

    public function getRack(): ?int
    {
        return $this->rack;
    }

    public function setRack(?int $rack): void
    {
        $this->rack = $rack;
    }

    public function getLevel(): ?int
    {
        return $this->level;
    }

    public function setLevel(?int $level): void
    {
        $this->level = $level;
    }

    public function getInvoiceId(): int
    {
        return $this->invoiceId;
    }

    public function setInvoiceId(int $invoiceId): void
    {
        $this->invoiceId = $invoiceId;
    }

    /**
     * Obtiene el valor de forceNegativeStock.
     */
    public function isForceNegativeStock(): bool
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

    /**
     * Obtiene la fecha de fabricación.
     */
    public function getManufacturingDate(): ?string
    {
        return $this->manufacturingDate;
    }

    /**
     * Establece la fecha de fabricación.
     *
     * @return self
     */
    public function setManufacturingDate(?string $manufacturingDate): void
    {
        $this->manufacturingDate = $manufacturingDate;
    }
}
