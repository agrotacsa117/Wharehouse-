<?php

declare(strict_types=1);

namespace App\Enterprise_Layer;

use DateTime;

class WarehouseInventory
{
    private int $id;

    private int $warehouseId;

    private string $productId;

    private ?int $rack;

    private ?int $level;

    private DateTime $createdAt;

    private DateTime $updatedAt;

    private $warehouseName;

    private int $quantity;

    private string $lotNumber;

    private string $reason;

    private DateTime $expirationDate;

    private ?int $module;

    private ?int $bay;

    private ?int $platform;

    private ?int $transferFolio;

    private ?DateTime $manufacturingDate;

    public function __construct(
        int $warehouseId,
        string $productId,
        ?int $rack,
        ?int $level,
        DateTime $createdAt,
        DateTime $updatedAt,
        $warehouseName,
        int $quantity,
        string $lotNumber,
        string $reason,
        DateTime $expirationDate,
        ?int $transferFolio
    ) {
        $this->warehouseId = $warehouseId;
        $this->productId = $productId;
        $this->rack = $rack;
        $this->level = $level;
        $this->createdAt = $createdAt;
        $this->updatedAt = $updatedAt;
        $this->warehouseName = $warehouseName;
        $this->quantity = $quantity;
        $this->lotNumber = $lotNumber;
        $this->reason = $reason;
        $this->expirationDate = $expirationDate;
        $this->transferFolio = $transferFolio;
    }

    public function getManufacturingDate(): ?DateTime
    {
        return $this->manufacturingDate;
    }

    /**
     * Establece la fecha de fabricación.
     */
    public function setManufacturingDate(?DateTime $manufacturingDate): self
    {
        $this->manufacturingDate = $manufacturingDate;

        return $this;
    }

    public function getTransferFolio(): ?int
    {
        return $this->transferFolio;
    }

    /**
     * Get the value of module
     */
    public function getModule(): ?int
    {
        return $this->module;
    }

    /**
     * Set the value of module
     */
    public function setModule(?int $module): void
    {
        $this->module = $module;
    }

    /**
     * Get the value of bay
     */
    public function getBay(): ?int
    {
        return $this->bay;
    }

    /**
     * Set the value of bay
     */
    public function setBay(?int $bay): void
    {
        $this->bay = $bay;
    }

    /**
     * Get the value of platform
     */
    public function getPlatform(): ?int
    {
        return $this->platform;
    }

    /**
     * Set the value of platform
     */
    public function setPlatform(?int $platform): void
    {
        $this->platform = $platform;
    }

    public function getExpirationDate(): DateTime
    {
        return $this->expirationDate;
    }

    public function setExpirationDate(DateTime $expirationDate): void
    {
        $this->expirationDate = $expirationDate;
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
    public function getProductId(): string
    {
        return $this->productId;
    }

    public function setProductId(string $productId): void
    {
        $this->productId = $productId;
    }

    // Rack
    public function getRack(): ?int
    {
        return $this->rack;
    }

    public function setRack(int $rack): void
    {
        $this->rack = $rack;
    }

    // Level
    public function getLevel(): ?int
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
    public function getWarehouseName()
    {
        return $this->warehouseName;
    }

    // Setter para warehouseName
    public function setWarehouseName($warehouseName): void
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
