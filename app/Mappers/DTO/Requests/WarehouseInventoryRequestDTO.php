<?php

namespace App\Mappers\DTO\Requests;

class WarehouseInventoryRequestDTO
{
    private int $productId;
    private int $warehouseId;
    private string $rack;
    private int $level;
    private int $quantity;
    private \DateTime $expirationDate;
    private string $reason;
    private string $loteNumber;

    public function __construct(
        int $productId,
        int $warehouseId,
        string $rack,
        int $level,
        int $quantity,
        \DateTime $expirationDate,
        string $reason,
        string $loteNumber
    ) {
        $this->productId = $productId;
        $this->warehouseId = $warehouseId;
        $this->rack = $rack;
        $this->level = $level;
        $this->quantity = $quantity;
        $this->expirationDate = $expirationDate;
        $this->reason = $reason;
        $this->loteNumber = $loteNumber;
    }

    public function getProductId(): int
    {
        return $this->productId;
    }

    public function setProductId(int $productId): void
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

    public function getRack(): string
    {
        return $this->rack;
    }

    public function setRack(string $rack): void
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
