<?php

namespace App\Enterprise_Layer;

class WarehouseSalesEntity
{
    private ?int $id;
    private string $movementId;
    private int $clientId;
    private int $invoiceSap;
    private \DateTime $createdAt;
    private \DateTime $updatedAt;

    public function __construct(
        string $movementId,
        int $clientId,
        int $invoiceSap
    ) {
        $this->movementId = $movementId;
        $this->clientId = $clientId;
        $this->invoiceSap = $invoiceSap;
        $this->id = null;
    }

    // Getters
    public function getId(): ?string
    {
        return $this->id;
    }

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


    public function getCreatedAt(): \DateTime
    {
        return $this->createdAt;
    }

    public function getUpdatedAt(): \DateTime
    {
        return $this->updatedAt;
    }

    // Setters
    public function setId(int $id): void
    {
        $this->id = $id;
    }

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



    public function setCreatedAt(\DateTime $createdAt): void
    {
        $this->createdAt = $createdAt;
    }

    public function setUpdatedAt(\DateTime $updatedAt): void
    {
        $this->updatedAt = $updatedAt;
    }
}
