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

    //enum('IN','OUT','ADJUSTMENT','TRANSFER','SALE','RELOCATION')
    private $id;
    private $folio;
    private $warehouseInventoryId;
    private $movementType;
    private $quantity;
    private $reason;
    private $userId;
    private $createdAt;
    private $updatedAt;

    public function __construct(
        string $folio,
        int $warehouseInventoryId,
        string $movementType,
        int $quantity,
        ?string $reason,
        ?int $userId
    ) {
        $this->validateMovementType($movementType);
        $this->validateQuantity(
            $quantity,
            $movementType
        );

        $this->folio = $folio;
        $this->warehouseInventoryId = $warehouseInventoryId;
        $this->movementType = $movementType;
        $this->quantity = $quantity;
        $this->reason = $reason;
        $this->userId = $userId;
    }

    // =========================
    // VALIDATIONS (Domain Rules)
    // =========================

    private function validateMovementType(string $type): void
    {
        $allowed = [
            self::TYPE_IN,
            self::TYPE_OUT,
            self::TYPE_ADJUSTMENT,
            self::TYPE_TRANSFER,
            self::TYPE_RELOCATION,
            self::TYPE_SALE
        ];

        if (!in_array($type, $allowed, true)) {
            throw new \InvalidArgumentException("Invalid movement type.");
        }
    }

    private function validateQuantity(
        int $quantity,
        string $movementType
    ): void {
        if ($quantity <= 0 && $movementType !== self::TYPE_RELOCATION) {
            throw new \InvalidArgumentException("Quantity must be greater than zero.");
        }
    }

    // =========================
    // GETTERS
    // =========================

    public function getId()
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

    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    // =========================
    // Setters controlados
    // =========================

    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function setTimestamps(string $createdAt, string $updatedAt): void
    {
        $this->createdAt = $createdAt;
        $this->updatedAt = $updatedAt;
    }
}
