<?php

declare(strict_types=1);

namespace App\Enterprise_Layer;

use DateTimeInterface;
use InvalidArgumentException;

class WarehouseInventoryMovements
{
    public const TYPE_IN = 'IN';

    public const TYPE_OUT = 'OUT';

    public const TYPE_ADJUSTMENT = 'ADJUSTMENT';

    public const TYPE_TRANSFER = 'TRANSFER';

    public const TYPE_SALE = 'SALE';

    public const TYPE_RELOCATION = 'RELOCATION';

    public const LOCATION_UPDATE = 'LOCATION_UPDATE';

    private ?int $id = null;

    private string $folio;

    private int $warehouseInventoryId;

    private string $movementType;

    private int $quantity;

    private ?string $reason;

    private ?int $userId;

    private ?DateTimeInterface $createdAt = null;

    private ?DateTimeInterface $updatedAt = null;

    private ?int $sourceWarehouseId = null;

    private ?DateTimeInterface $operationDate = null;

    private ?int $transferFolio = null;

    private bool $isReversed = false;

    private ?int $reversedBy = null;

    private ?int $reversalOf = null;

    private ?int $relocatedFromInventoryId = null;

    private ?int $relocatedToInventoryId = null;

    public function __construct(
        string $folio,
        int $warehouseInventoryId,
        string $movementType,
        int $quantity,
        ?string $reason,
        ?int $userId
    ) {
        $this->validateMovementType($movementType);
        $this->validateQuantity($quantity, $movementType);

        $this->folio = $folio;
        $this->warehouseInventoryId = $warehouseInventoryId;
        $this->movementType = $movementType;
        $this->quantity = $quantity;
        $this->reason = $reason;
        $this->userId = $userId;
    }

    public function setTimestamps(DateTimeInterface $createdAt, DateTimeInterface $updatedAt): void
    {
        $this->createdAt = $createdAt;
        $this->updatedAt = $updatedAt;
    }

    // ... (El resto de tus getters y setters se mantienen igual)

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(int $id): void
    {
        $this->id = $id;
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

    public function getCreatedAt(): ?DateTimeInterface
    {
        return $this->createdAt;
    }

    public function getUpdatedAt(): ?DateTimeInterface
    {
        return $this->updatedAt;
    }

    public function getSourceWarehouseId(): ?int
    {
        return $this->sourceWarehouseId;
    }

    public function setSourceWarehouseId(int $sourceWarehouseId): self
    {
        $this->sourceWarehouseId = $sourceWarehouseId;

        return $this;
    }

    public function getOperationDate(): ?DateTimeInterface
    {
        return $this->operationDate;
    }

    public function setOperationDate(?DateTimeInterface $operationDate): self
    {
        $this->operationDate = $operationDate;

        return $this;
    }

    public function getTransferFolio(): ?int
    {
        return $this->transferFolio;
    }

    public function setTransferFolio(?int $transferFolio): void
    {
        $this->transferFolio = $transferFolio;
    }

    public function isReversed(): bool
    {
        return $this->isReversed;
    }

    public function setIsReversed(bool $isReversed): void
    {
        $this->isReversed = $isReversed;
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

    public function getRelocatedFromInventoryId(): ?int
    {
        return $this->relocatedFromInventoryId;
    }

    public function setRelocatedFromInventoryId(?int $relocatedFromInventoryId): void
    {
        $this->relocatedFromInventoryId = $relocatedFromInventoryId;
    }

    public function getRelocatedToInventoryId(): ?int
    {
        return $this->relocatedToInventoryId;
    }

    public function setRelocatedToInventoryId(?int $relocatedToInventoryId): void
    {
        $this->relocatedToInventoryId = $relocatedToInventoryId;
    }

    private function validateMovementType(string $type): void
    {
        $allowed = [
            self::TYPE_IN, self::TYPE_OUT, self::TYPE_ADJUSTMENT,
            self::TYPE_TRANSFER, self::TYPE_RELOCATION, self::TYPE_SALE,
            self::LOCATION_UPDATE,
        ];

        if (! in_array($type, $allowed, true)) {
            throw new InvalidArgumentException('Invalid movement type.');
        }
    }

    private function validateQuantity(int $quantity, string $movementType): void
    {
        if ($quantity <= 0 && $movementType !== self::TYPE_RELOCATION) {
            throw new InvalidArgumentException('Quantity must be greater than zero.');
        }
    }
}
