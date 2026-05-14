<?php

namespace App\Mappers\DTO\Requests;

class WarehouseInventoryRequestDTO
{
    private string $productId;
    private int $warehouseId;
    private ?int $rack;
    private ?int $level;
    private int $module;
    private int $bay;
    private int $platform;
    private int $quantity;
    private \DateTime $expirationDate;
    private string $reason;
    private string $loteNumber;
    private ?int $transferFolio;
    //manufacturing_date
    private ?\DateTime $manufacturingDate;
    
    public function __construct(
        string $productId,
        int $warehouseId,
        ?int $rack,
        ?int $level,
        int $quantity,
        \DateTime $expirationDate,
        string $reason,
        string $loteNumber,
        ?int $transferFolio
    ) {
        $this->productId = $productId;
        $this->warehouseId = $warehouseId;
        $this->rack = $rack;
        $this->level = $level;
        $this->quantity = $quantity;
        $this->expirationDate = $expirationDate;
        $this->reason = $reason;
        $this->loteNumber = $loteNumber;
        $this->transferFolio = $transferFolio;
    }

    public function getTransferFolio(): ?int
    {
        return $this->transferFolio;
    }
    /**
     * Get the value of module
     */
    public function getModule(): int
    {
        return $this->module;
    }

    /**
     * Set the value of module
     */
    public function setModule(int $module): void
    {
        $this->module = $module;
    }

    /**
     * Get the value of bay
     */
    public function getBay(): int
    {
        return $this->bay;
    }

    /**
     * Set the value of bay
     */
    public function setBay(int $bay): void
    {
        $this->bay = $bay;
    }

    /**
     * Get the value of platform
     */
    public function getPlatform(): int
    {
        return $this->platform;
    }

    /**
     * Set the value of platform
     */
    public function setPlatform(int $platform): void
    {
        $this->platform = $platform;
    }

    public function getProductId(): string
    {
        return $this->productId;
    }

    public function setProductId(string $productId): void
    {
        $this->productId = $productId;
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

    public function setRack(string $rack): void
    {
        $this->rack = $rack;
    }

    public function getLevel(): ?int
    {
        return $this->level;
    }

    public function setLevel(int $level): void
    {
        $this->level = $level;
    }

    public function getQuantity(): int
    {
        return $this->quantity;
    }

    public function setQuantity(int $quantity): void
    {
        $this->quantity = $quantity;
    }

    public function getExpirationDate(): \DateTime
    {
        return $this->expirationDate;
    }

    public function setExpirationDate(\DateTime $expirationDate): void
    {
        $this->expirationDate = $expirationDate;
    }

    public function getReason(): string
    {
        return $this->reason;
    }

    public function setReason(string $reason): void
    {
        $this->reason = $reason;
    }

    //$LoteNumber
    public function setLoteNumber(string $loteNumber): void
    {
        $this->loteNumber = $loteNumber;
    }

    public function getLoteNumber(): string
    {
        return $this->loteNumber;
    }
}
