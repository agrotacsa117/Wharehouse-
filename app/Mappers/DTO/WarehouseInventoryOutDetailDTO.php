<?php

namespace App\Mappers\DTO;

class WarehouseInventoryOutDetailDTO implements \JsonSerializable{

    private int $inventoryId;
    private int $warehouseId;
    private ?int $rack;
    private ?int $level;
    private string $productCode;
    private string $productName;
    private int $quantity;
    private string $lotNumber;
    private string $expirationDate;


    public function __construct(
        int $inventoryId,
        int $warehouseId,
        ?int $rack,
        ?int $level,
        string $productCode,
        string $productName,
        int $quantity,
        string $lotNumber,
        string $expirationDate
    ) {
        $this->inventoryId = $inventoryId;
        $this->warehouseId = $warehouseId;
        $this->rack = $rack;
        $this->level = $level;
        $this->productCode = $productCode;
        $this->productName = $productName;
        $this->quantity = $quantity;
        $this->lotNumber = $lotNumber;
        $this->expirationDate = $expirationDate;
    }

    public function getInventoryId(): int
    {
        return $this->inventoryId;
    }

    public function setInventoryId(int $inventoryId): void
    {
        $this->inventoryId = $inventoryId;
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

    public function getLevel(): int
    {
        return $this->level;
    }

    public function setLevel(int $level): void
    {
        $this->level = $level;
    }

    public function getProductCode(): string
    {
        return $this->productCode;
    }

    public function setProductCode(string $productCode): void
    {
        $this->productCode = $productCode;
    }

    public function getProductName(): string
    {
        return $this->productName;
    }

    public function setProductName(string $productName): void
    {
        $this->productName = $productName;
    }

    public function getQuantity(): int
    {
        return $this->quantity;
    }

    public function setQuantity(int $quantity): void
    {
        $this->quantity = $quantity;
    }

    public function getLotNumber(): string
    {
        return $this->lotNumber;
    }

    public function setLotNumber(string $lotNumber): void
    {
        $this->lotNumber = $lotNumber;
    }

    public function getExpirationDate(): string
    {
        return $this->expirationDate;
    }

    public function setExpirationDate(string $expirationDate): void
    {
        $this->expirationDate = $expirationDate;
    }

      public function jsonSerialize() : array
    {
        return [
            'inventoryId' => $this->inventoryId,
            'warehouseId' => $this->warehouseId,
            'rack' => $this->rack,
            'level' => $this->level,
            'productCode' => $this->productCode,
            'productName' => $this->productName,
            'quantity' => $this->quantity,
            'lotNumber' => $this->lotNumber,
            'expirationDate' => $this->expirationDate,
        ];
    }
}