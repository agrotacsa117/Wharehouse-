<?php

namespace App\Contracts;

use App\Enterprise_Layer\WarehouseInventoryMovements;

interface WarehouseMovementsRepositoryI
{
    public function findAll(): array;

    public function findAllPaginated(int $perPage = 15): array;

    public function findByInventoryId(int $inventoryId): array;

    public function save(WarehouseInventoryMovements $data): void;

    public function count(): int;


    public function countByMovementType(string $movementType): int;

    public function findByDateRange(
        string $startDate,
        string $endDate,
        ?int $warehouseId,
        ?string $movementType
    ): array;

    public function getMovementCountsByType(
        string $startDate,
        string $endDate
    ): array;
}
