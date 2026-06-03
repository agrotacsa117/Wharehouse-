<?php

namespace App\Mappers\DTO;

use Carbon\Carbon;

class WarehouseInventoryDetailDTO implements \JsonSerializable
{
    private ?int $inventoryId;

    private string $productName;

    private string $productCode;

    private int $warehouseId;

    private ?string $warehouseName;

    private ?int $level;

    private ?int $rack;

    private ?int $module;

    private ?int $bay;

    private ?int $platform;

    private int $stock;

    private string $expirationDate;

    private ?string $lotNumber;

    private ?int $remainingDays;

    private ?float $obsolescence;

    private ?string $manufacturingDate;

    private ?int $state;

    public function __construct(
        ?int $inventoryId,
        string $productName,
        string $productCode,
        int $warehouseId,
        ?string $warehouseName,
        ?int $level,
        ?int $rack,
        int $stock,
        string $expirationDate,
        ?string $lotNumber,
        ?int $remainingDays,
        ?float $obsolescence,
        ?int $module,
        ?int $bay,
        ?int $platform,
        ?string $manufacturingDate,
        ?int $state
    ) {
        $this->inventoryId = $inventoryId;
        $this->productName = $productName;
        $this->productCode = $productCode;
        $this->warehouseId = $warehouseId;
        $this->warehouseName = $warehouseName;
        $this->level = $level;
        $this->rack = $rack;
        $this->stock = $stock;
        $this->expirationDate = $expirationDate;
        $this->lotNumber = $lotNumber;
        $this->remainingDays = $remainingDays;
        $this->obsolescence = $obsolescence;
        $this->module = $module;
        $this->bay = $bay;
        $this->platform = $platform;
        $this->manufacturingDate = $manufacturingDate;
        $this->state = $state;
    }

    public function getManufacturingDate(): ?string
    {
        return $this->manufacturingDate;
    }

    public function getModule(): ?int
    {
        return $this->module;
    }

    public function getBay(): ?int
    {
        return $this->bay;
    }

    public function getPlatform(): ?int
    {
        return $this->platform;
    }

    /**
     * Obtiene el valor de obsolescence
     */
    public function getObsolescence(): ?float
    {
        return $this->obsolescence;
    }

    /**
     * Establece el valor de obsolescence
     */
    public function setObsolescence(?float $obsolescence): self
    {
        // El uso de 'self' permite el encadenamiento de métodos (fluent interface)
        $this->obsolescence = $obsolescence;

        return $this;
    }

    public function getInventoryId(): ?int
    {
        return $this->inventoryId;
    }

    public function setInventoryId(?int $inventoryId): void
    {
        $this->inventoryId = $inventoryId;
    }

    public function getExpirationDate(): string
    {
        return $this->expirationDate;
    }

    public function setExpirationDate(string $expirationDate): void
    {
        $this->expirationDate = $expirationDate;
    }

    public function getProductName(): string
    {
        return $this->productName;
    }

    public function setProductName(string $productName): void
    {
        $this->productName = $productName;
    }

    public function getProductCode(): string
    {
        return $this->productCode;
    }

    public function setProductCode(string $productCode): void
    {
        $this->productCode = $productCode;
    }

    public function getWarehouseId(): int
    {
        return $this->warehouseId;
    }

    public function setWarehouseId(int $warehouseId): void
    {
        $this->warehouseId = $warehouseId;
    }

    public function getWarehouseName(): string
    {
        return $this->warehouseName;
    }

    public function setWarehouseName(string $warehouseName): void
    {
        $this->warehouseName = $warehouseName;
    }

    public function getLevel(): ?int
    {
        return $this->level;
    }

    public function setLevel(int $level): void
    {
        $this->level = $level;
    }

    public function getRack(): ?int
    {
        return $this->rack;
    }

    public function setRack(string $rack): void
    {
        $this->rack = $rack;
    }

    public function getStock(): int
    {
        return $this->stock;
    }

    public function setStock(int $stock): void
    {
        $this->stock = $stock;
    }

    public function jsonSerialize(): array
    {
        return [
            'id' => $this->inventoryId,
            'productName' => $this->productName,
            'productId' => $this->productCode,
            'warehouseId' => $this->warehouseId,
            'warehouseName' => $this->warehouseName,
            'level' => $this->level,
            'rack' => $this->rack,
            'quantity' => $this->stock,
            'expirationDate' => Carbon::parse($this->expirationDate)->format('Y-m-d'),
            'lotNumber' => $this->lotNumber,
            'remainingDays' => $this->remainingDays,
            'obsolescence' => $this->obsolescence,
            'module' => $this->module,
            'bay' => $this->bay,
            'platform' => $this->platform,
        ];
    }

    public function getLotNumber(): string
    {
        return $this->lotNumber;
    }

    public function setLotNumber(string $lotNumber): void
    {
        $this->lotNumber = $lotNumber;
    }

    public function getRemainingDays(): int
    {
        return $this->remainingDays;
    }

    public function setRemainingDays(int $remainingDays): void
    {
        $this->remainingDays = $remainingDays;
    }
}
