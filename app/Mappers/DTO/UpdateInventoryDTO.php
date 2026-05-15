<?php

namespace App\Mappers\DTO;

class UpdateInventoryDTO
{
    private int $id;
    private string $productCode;
    private string $productName;
    private string $rack;
    private int $level;
    private string $lotNumber;
    private int $quantity;
    private string $expirationDate;
    private string $reason;

    public function __construct(
        int $id,
        string $rack,
        int $level,
        string $lotNumber,
        int $quantity,
        string $expirationDate,
        string $reason
    ) {
        $this->id = $id;
        $this->rack = $rack;
        $this->level = $level;
        $this->lotNumber = $lotNumber;
        $this->quantity = $quantity;
        $this->expirationDate = $expirationDate;
        $this->reason = $reason;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getRack(): string
    {
        return $this->rack;
    }

    public function getLevel(): int
    {
        return $this->level;
    }

    public function getLotNumber(): string
    {
        return $this->lotNumber;
    }

    public function getQuantity(): int
    {
        return $this->quantity;
    }

    public function getExpirationDate(): string
    {
        return $this->expirationDate;
    }

    public function getReason(): string
    {
        return $this->reason;
    }
}
