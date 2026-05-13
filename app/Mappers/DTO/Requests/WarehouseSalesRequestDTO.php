<?php

namespace App\Mappers\DTO\Requests;

class WarehouseSalesRequestDTO
{
    private string $movementId;
    private int $invoiceSap;

    public function __construct(
        string $movementId,
        int $invoiceSap
    ) {
        $this->movementId = $movementId;
        $this->invoiceSap = $invoiceSap;
    }

    // Getters
    public function getMovementId(): string
    {
        return $this->movementId;
    }

    

    public function getInvoiceSap(): int
    {
        return $this->invoiceSap;
    }

    // Setters
    public function setMovementId(string $movementId): void
    {
        $this->movementId = $movementId;
    }

    

    public function setInvoiceSap(int $invoiceSap): void
    {
        $this->invoiceSap = $invoiceSap;
    }
}
