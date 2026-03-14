<?php

namespace App\Mappers\DTO;

class MovementsByPeriodFilterDTO
{
    private string $startDate;
    private string $endDate;
    private ?string $movementType;
    private ?int $warehouseId;

    public function __construct(
        string $startDate,
        string $endDate,
        ?string $movementType,
        ?int $warehouseId
    ) {
        $this->startDate = $startDate;
        $this->endDate = $endDate;
        $this->movementType = $movementType;
        $this->warehouseId = $warehouseId;
    }

    public function getStartDate(): string
    {
        return $this->startDate;
    }

    public function setStartDate(string $startDate): void
    {
        $this->startDate = $startDate;
    }

    public function getEndDate(): string
    {
        return $this->endDate;
    }

    public function setEndDate(string $endDate): void
    {
        $this->endDate = $endDate;
    }

    public function getMovementType(): ?string
    {
        return $this->movementType;
    }

    public function setMovementType(?string $movementType): void
    {
        $this->movementType = $movementType;
    }

    public function getWarehouseId(): ?int
    {
        return $this->warehouseId;
    }

    public function setWarehouseId(?int $warehouseId): void
    {
        $this->warehouseId = $warehouseId;
    }
}
