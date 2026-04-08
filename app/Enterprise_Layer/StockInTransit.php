<?php

declare(strict_types=1);

namespace App\Enterprise_Layer;

use DateTime;
use InvalidArgumentException;

class StockInTransit
{
    public const STATUS_PENDING_RECEPTION = 'PENDING_RECEPTION';
    public const STATUS_RECEIVED = 'RECEIVED';
    public const STATUS_CANCELLED = 'CANCELLED';

    private ?int $id;
    private int $inventoryId;
    private int $originWarehouseId;
    private int $destinationWarehouseId;
    private int $quantity;
    private string $status;
    private string $folio;
    private DateTime $sentAt;
    private ?DateTime $receivedAt;
    private ?int $receivedBy;

    public function __construct(
        int $inventoryId,
        int $originWarehouseId,
        int $destinationWarehouseId,
        int $quantity,
        string $folio
    ) {
        $this->validateWarehousesDifferent($originWarehouseId, $destinationWarehouseId);
        $this->validateQuantity($quantity);

        $this->inventoryId = $inventoryId;
        $this->originWarehouseId = $originWarehouseId;
        $this->destinationWarehouseId = $destinationWarehouseId;
        $this->quantity = $quantity;
        $this->status = self::STATUS_PENDING_RECEPTION;
        $this->folio = $folio;
        $this->sentAt = new DateTime();
    }

    private function validateWarehousesDifferent(int $origin, int $destination): void
    {
        if ($origin === $destination) {
            throw new InvalidArgumentException("Origin and destination warehouses must be different.");
        }
    }

    private function validateQuantity(int $quantity): void
    {
        if ($quantity <= 0) {
            throw new InvalidArgumentException("Quantity must be greater than zero.");
        }
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getInventoryId(): int
    {
        return $this->inventoryId;
    }

    public function getOriginWarehouseId(): int
    {
        return $this->originWarehouseId;
    }

    public function getDestinationWarehouseId(): int
    {
        return $this->destinationWarehouseId;
    }

    public function getQuantity(): int
    {
        return $this->quantity;
    }

    public function getStatus(): string
    {
        return $this->status;
    }

    public function getFolio(): string
    {
        return $this->folio;
    }

    public function getSentAt(): DateTime
    {
        return $this->sentAt;
    }

    public function getReceivedAt(): ?DateTime
    {
        return $this->receivedAt;
    }

    public function getReceivedBy(): ?int
    {
        return $this->receivedBy;
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function confirmReception(int $userId): void
    {
        if ($this->status !== self::STATUS_PENDING_RECEPTION) {
            throw new InvalidArgumentException(
                "Cannot confirm reception. Current status is: {$this->status}"
            );
        }

        $this->status = self::STATUS_RECEIVED;
        $this->receivedAt = new DateTime();
        $this->receivedBy = $userId;
    }

    public function cancel(): void
    {
        if ($this->status !== self::STATUS_PENDING_RECEPTION) {
            throw new InvalidArgumentException(
                "Cannot cancel. Only PENDING_RECEPTION transfers can be cancelled."
            );
        }

        $this->status = self::STATUS_CANCELLED;
    }

    public function isPending(): bool
    {
        return $this->status === self::STATUS_PENDING_RECEPTION;
    }

    public function isReceived(): bool
    {
        return $this->status === self::STATUS_RECEIVED;
    }

    public function isCancelled(): bool
    {
        return $this->status === self::STATUS_CANCELLED;
    }
}
