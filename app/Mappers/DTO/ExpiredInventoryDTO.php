<?php

namespace App\Mappers\DTO;

//
class ExpiredInventoryDTO implements \JsonSerializable
{
    private int $warehouseId;
    private string $productCode;
    private string $productName;
    private string $warehouseName;
    private int $quantity;
    private string $lotNumber;
    private string $expirationDate;
    private string $rack;
    private int $level;
    private int $expiredDays;

    public function __construct(
        string $productCode,
        int $warehouseId,
        string $productName,
        string $warehouseName,
        int $quantity,
        string $lotNumber,
        string $expirationDate,
        string $rack,
        int $level,
        int $expiredDays
    ) {
        $this->productCode = $productCode;
        $this->warehouseId = $warehouseId;
        $this->productName = $productName;
        $this->warehouseName = $warehouseName;
        $this->quantity = $quantity;
        $this->lotNumber = $lotNumber;
        $this->expirationDate = $expirationDate;
        $this->rack = $rack;
        $this->level = $level;
        $this->expiredDays = $expiredDays;
    }


    public function jsonSerialize(): array
    {
        return [
            'productCode' => $this->productCode,
            'warehouseId' => $this->warehouseId,
            'productName' => $this->productName,
            'warehouseName' => $this->warehouseName,
            'quantity' => $this->quantity,
            'lotNumber' => $this->lotNumber,
            'expirationDate' => date('Y-m-d', strtotime($this->expirationDate)),
            'expiredDays' => $this->expiredDays,
            'rack' => $this->rack,
            'level' => $this->level
        ];
    }

    // Getters
    public function getWarehouseName(): string
    {
        return $this->warehouseName;
    }

    public function getProductCode(): string
    {
        return $this->productCode;
    }

    public function getProductName(): string
    {
        return $this->productName;
    }

    public function getQuantity(): int
    {
        return $this->quantity;
    }

    public function getLotNumber(): string
    {
        return $this->lotNumber;
    }

    public function getExpirationDate(): string
    {
        return $this->expirationDate;
    }

    public function getExpiredDays(): int
    {
        return $this->expiredDays;
    }

    // Setters

    public function setProductCode(string $productCode): void
    {
        $this->productCode = $productCode;
    }

    public function setProductName(string $productName): void
    {
        $this->productName = $productName;
    }

    public function setQuantity(int $quantity): void
    {
        $this->quantity = $quantity;
    }

    public function setLotNumber(string $lotNumber): void
    {
        $this->lotNumber = $lotNumber;
    }

    public function setExpirationDate(string $expirationDate): void
    {
        $this->expirationDate = $expirationDate;
    }

    public function setExpiredDays(int $expiredDays): void
    {
        $this->expiredDays = $expiredDays;
    }

    public function setWarehouseName(string $warehouseName): void
    {
        $this->warehouseName = $warehouseName;
    }
}
