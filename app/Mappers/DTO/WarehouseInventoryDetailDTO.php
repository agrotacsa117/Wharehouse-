<?php

namespace App\Mappers\DTO;

class WarehouseInventoryDetailDTO implements \JsonSerializable
{
    private string $productName;
    private string $productCode;
    private int $warehouseId;
    private string $warehouseName;
    private int $level;
    private string $rack;
    private int $stock;
    private string $expirationDate;

    public function __construct(
        string $productName,
        string $productCode,
        int $warehouseId,
        string $warehouseName,
        int $level,
        string $rack,
        int $stock,
        string $expirationDate
    ) {
        $this->productName = $productName;
        $this->productCode = $productCode;
        $this->warehouseId = $warehouseId;
        $this->warehouseName = $warehouseName;
        $this->level = $level;
        $this->rack = $rack;
        $this->stock = $stock;
        $this->expirationDate = $expirationDate;
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

    public function getLevel(): int
    {
        return $this->level;
    }

    public function setLevel(int $level): void
    {
        $this->level = $level;
    }

    public function getRack(): string
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

    public function jsonSerialize()
    {
         return [
            'productName' => $this->productName,
            'productId' => $this->productCode,
            'warehouseId' => $this->warehouseId,
            'warehouseName' => $this->warehouseName,
            'level' => $this->level,
            'rack' => $this->rack,
            'quantity' => $this->stock,
            'expirationDate' => $this->expirationDate
        ];
    }
    
}
