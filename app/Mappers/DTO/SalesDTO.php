<?php

namespace App\Mappers\DTO;

class SalesDTO implements \JsonSerializable
{
    private string $movementId;
    private int $clientId;
    private int $invoiceSap;
    private string $createdAt;
    private string $updatedAt;

    public function __construct(
        string $movementId,
        int $clientId,
        int $invoiceSap,
        string $createdAt,
        string $updatedAt
    ) {
        $this->movementId = $movementId;
        $this->clientId   = $clientId;
        $this->invoiceSap = $invoiceSap;
        $this->createdAt  = $createdAt;
        $this->updatedAt  = $updatedAt;
    }

    /**
     * Getters
     */
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

    public function getCreatedAt(): string
    {
        return $this->createdAt;
    }

    public function getUpdatedAt(): string
    {
        return $this->updatedAt;
    }

    /**
     * Implementación de JsonSerializable
     */
    public function jsonSerialize(): array
    {
        return [
            'movementId' => $this->movementId,
            'clientId'   => $this->clientId,
            'invoiceSap' => $this->invoiceSap,
            'createdAt'  => $this->createdAt,
            'updatedAt'  => $this->updatedAt,
        ];
    }
}
