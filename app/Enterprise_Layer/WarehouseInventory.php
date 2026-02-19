<?php

declare(strict_types=1);

namespace App\Enterprise_Layer;

use DateTime;

class WarehouseInventory
{
    private int $id;
    private int $warehouseId;
    private int $productId;
    private string $rack;
    private int $level;
    private DateTime $createdAt;
    private DateTime $updatedAt;
    private string $warehouseName;
    private int $quantity;
    private string $lotNumber;
    private string $reason;

    public function __construct(
        int $warehouseId,
        int $productId,
        string $rack,
        int $level,
        DateTime $createdAt,
        DateTime $updatedAt,
        string $warehouseName,
        int $quantity,
        string $lotNumber,
        string $reason
    ) {
        $this->warehouseId = $warehouseId;
        $this->productId = $productId;
        $this->rack = $rack;
        $this->level = $level;
        $this->createdAt = $createdAt;
        $this->updatedAt = $updatedAt;
        $this->warehouseName = $warehouseName;
        $this->quantity = $quantity;
        $this->lotNumber =  $lotNumber;
        $this->reason = $reason;
    }

    // ID
    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }

    // Warehouse ID
    public function getWarehouseId(): int
    {
        return $this->warehouseId;
    }

    public function setWarehouseId(int $warehouseId): void
    {
        $this->warehouseId = $warehouseId;
    }

    // Product ID
    public function getProductId(): int
    {
        return $this->productId;
    }

    public function setProductId(int $productId): void
    {
        $this->productId = $productId;
    }

    // Rack
    public function getRack(): string
    {
        return $this->rack;
    }

    public function setRack(string $rack): void
    {
        $this->rack = $rack;
    }

    // Level
    public function getLevel(): int
    {
        return $this->level;
    }

    public function setLevel(int $level): void
    {
        $this->level = $level;
    }

    // Created At
    public function getCreatedAt(): DateTime
    {
        return $this->createdAt;
    }

    public function setCreatedAt(DateTime $createdAt): void
    {
        $this->createdAt = $createdAt;
    }

    // Updated At
    public function getUpdatedAt(): DateTime
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(DateTime $updatedAt): void
    {
        $this->updatedAt = $updatedAt;
    }

    // Getter para warehouseName
    public function getWarehouseName(): string
    {
        return $this->warehouseName;
    }

    // Setter para warehouseName
    public function setWarehouseName(string $warehouseName): void
    {
        $this->warehouseName = $warehouseName;
    }

    // Getter para quantity
    public function getQuantity(): int
    {
        return $this->quantity;
    }

    // Setter para quantity
    public function setQuantity(int $quantity): void
    {
        $this->quantity = $quantity;
    }

    // Getter para lotNumber
    public function getLotNumber(): string
    {
        return $this->lotNumber;
    }

    // Setter para lotNumber
    public function setLotNumber(string $lotNumber): void
    {
        $this->lotNumber = $lotNumber;
    }

    public function getReason(): string
    {
        return $this->reason;
    }

    public function setReason(string $reason): void
    {
        $this->reason = $reason;
    }
}
