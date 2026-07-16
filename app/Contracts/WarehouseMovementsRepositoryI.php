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

    public function countFolio(): int;

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

    public function isReversed(string $folio): bool;

    public function findByFolio(string $folio): ?WarehouseInventoryMovements;

    public function findByWarehouseInventoryIdAndFolioNot(
        int $warehouseInventoryId,
        string $folio
    ): array;

    public function findIdByFolio(string $folio): int;

    public function findByIdGreaterThan(int $id, int $limit = 15): array;

    public function setReversedByFolio(
        string $folio,
        int $reversedBy): bool;

    public function updateIsReversedStatus(
        string $folio,
        bool $status
    ): bool;
}
