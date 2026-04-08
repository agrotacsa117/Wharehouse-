<?php

declare(strict_types=1);

namespace App\Enterprise_Layer;

use DateTime;

class WarehouseInventoryMovements
{
    public const TYPE_IN = 'IN';
    public const TYPE_OUT = 'OUT';
    public const TYPE_ADJUSTMENT = 'ADJUSTMENT';
    public const TYPE_TRANSFER = 'TRANSFER';
    public const TYPE_SALE = 'SALE';
    public const TYPE_RELOCATION = 'RELOCATION';

    private ?int $id;
    private string $folio;
    private int $warehouseInventoryId;
    private string $movementType;
    private int $quantity;
    private ?string $reason;
    private ?int $userId;
    private ?int $clientId;
    private ?string $invoiceSap;
    private ?DateTime $operationDate;
    private ?int $sourceWarehouseId;
    private ?DateTime $createdAt;
    private ?DateTime $updatedAt;

    public function __construct(
        string $folio,
        int $warehouseInventoryId,
        string $movementType,
        int $quantity,
        ?string $reason,
        ?int $userId,
        ?int $clientId = null,
        ?string $invoiceSap = null,
        ?DateTime $operationDate = null,
        ?int $sourceWarehouseId = null
    ) {
        $this->validateMovementType($movementType);
        $this->validateQuantity($quantity);
        $this->validateClientIdForSale($clientId, $movementType);

        $this->folio = $folio;
        $this->warehouseInventoryId = $warehouseInventoryId;
        $this->movementType = $movementType;
        $this->quantity = $quantity;
        $this->reason = $reason;
        $this->userId = $userId;
        $this->clientId = $clientId;
        $this->invoiceSap = $invoiceSap;
        $this->operationDate = $operationDate;
        $this->sourceWarehouseId = $sourceWarehouseId;
    }

    private function validateMovementType(string $type): void
    {
        $allowed = [
            self::TYPE_IN,
            self::TYPE_OUT,
            self::TYPE_ADJUSTMENT,
            self::TYPE_TRANSFER,
            self::TYPE_SALE,
            self::TYPE_RELOCATION
        ];

        if (!in_array($type, $allowed, true)) {
            throw new \InvalidArgumentException("Invalid movement type: {$type}");
        }
    }

    private function validateQuantity(int $quantity): void
    {
        if ($quantity <= 0) {
            throw new \InvalidArgumentException("Quantity must be greater than zero.");
        }
    }

    private function validateClientIdForSale(?int $clientId, string $movementType): void
    {
        if ($movementType === self::TYPE_SALE && $clientId === null) {
            throw new \InvalidArgumentException("Client ID is required for SALE movements.");
        }
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFolio(): string
    {
        return $this->folio;
    }

    public function getWarehouseInventoryId(): int
    {
        return $this->warehouseInventoryId;
    }

    public function getMovementType(): string
    {
        return $this->movementType;
    }

    public function getQuantity(): int
    {
        return $this->quantity;
    }

    public function getReason(): ?string
    {
        return $this->reason;
    }

    public function getUserId(): ?int
    {
        return $this->userId;
    }

    public function getClientId(): ?int
    {
        return $this->clientId;
    }

    public function getInvoiceSap(): ?string
    {
        return $this->invoiceSap;
    }

    public function getOperationDate(): ?DateTime
    {
        return $this->operationDate;
    }

    public function getSourceWarehouseId(): ?int
    {
        return $this->sourceWarehouseId;
    }

    public function getCreatedAt(): ?DateTime
    {
        return $this->createdAt;
    }

    public function getUpdatedAt(): ?DateTime
    {
        return $this->updatedAt;
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function setTimestamps(string $createdAt, string $updatedAt): void
    {
        $this->createdAt = new DateTime($createdAt);
        $this->updatedAt = new DateTime($updatedAt);
    }

    public function isSale(): bool
    {
        return $this->movementType === self::TYPE_SALE;
    }

    public function isTransfer(): bool
    {
        return $this->movementType === self::TYPE_TRANSFER;
    }

    public function isRelocation(): bool
    {
        return $this->movementType === self::TYPE_RELOCATION;
    }
}
