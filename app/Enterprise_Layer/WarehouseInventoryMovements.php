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

    // enum('IN','OUT','ADJUSTMENT','TRANSFER','SALE','RELOCATION')
    private $id;

    private $folio;

    private $warehouseInventoryId;

    private $movementType;

    private $quantity;

    private $reason;

    private $userId;

    private $createdAt;

    private $updatedAt;

    private ?int $sourceWarehouseId = null;

    private ?DateTime $operationDate = null;

    private ?int $transferFolio = null;

    private bool $iSReversed;

    private ?int $reversedBy;

    private ?int $reversalOf;

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
        $this->iSReversed = false;
    }

    public function getTransferFolio(): ?int
    {
        return $this->transferFolio;
    }

    public function setTransferFolio(?int $transferFolio): void
    {
        $this->transferFolio = $transferFolio;
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
            self::TYPE_SALE,
        ];

        if (! in_array($type, $allowed, true)) {
            throw new \InvalidArgumentException('Invalid movement type.');
        }
    }

    // Getters
    public function getSourceWarehouseId(): ?int
    {
        return $this->sourceWarehouseId;
    }

    public function getOperationDate(): ?DateTime
    {
        return $this->operationDate;
    }

    // Setters
    public function setSourceWarehouseId(int $sourceWarehouseId): self
    {
        $this->sourceWarehouseId = $sourceWarehouseId;

        return $this;
    }

    public function setOperationDate(DateTime $operationDate): self
    {
        $this->operationDate = $operationDate;

        return $this;
    }

    private function validateQuantity(
        int $quantity,
        string $movementType
    ): void {
        if ($quantity <= 0 && $movementType !== self::TYPE_RELOCATION) {
            throw new \InvalidArgumentException('Quantity must be greater than zero.');
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

    public function isReversed(): bool
    {
        return $this->iSReversed;
    }

    public function setIsReversed(bool $iSReversed): void
    {
        $this->iSReversed = $iSReversed;
    }

    public function getReversedBy(): ?int
    {
        return $this->reversedBy;
    }

    public function setReversedBy(?int $reversedBy): void
    {
        $this->reversedBy = $reversedBy;
    }

    public function getReversalOf(): ?int
    {
        return $this->reversalOf;
    }

    public function setReversalOf(?int $reversalOf): void
    {
        $this->reversalOf = $reversalOf;
    }
}
