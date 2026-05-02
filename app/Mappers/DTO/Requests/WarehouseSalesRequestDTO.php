<?php

namespace App\Mappers\DTO\Requests;

class WarehouseSalesRequestDTO
{
    private string $movementId;
    private int $clientId;
    private int $invoiceSap;

    public function __construct(
        string $movementId,
        int $clientId,
        int $invoiceSap
    ) {
        $this->movementId = $movementId;
        $this->clientId = $clientId;
        $this->invoiceSap = $invoiceSap;
    }

    // Getters
    public function getMovementId(): string
    {
        return $this->movementId;
    }

    public function getClientId(): int
    {
        return $this->clientId;
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

    public function setClientId(int $clientId): void
    {
        $this->clientId = $clientId;
    }

    public function setInvoiceSap(int $invoiceSap): void
    {
        $this->invoiceSap = $invoiceSap;
    }
}
