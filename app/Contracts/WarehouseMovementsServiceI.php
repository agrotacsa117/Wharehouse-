<?php

namespace App\Contracts;

use App\Application_Layer\ResultPattern;
use App\Mappers\DTO\MovementsByPeriodFilterDTO;
use App\Mappers\DTO\WarehouseMovementsDTO;

interface WarehouseMovementsServiceI
{
    public function listAllMovements(): array;

    public function listAllMovementsPaginated(int $page = 1, int $perPage = 15): array;

    public function getTotalOfMovements(): int;

    public function countByMovementType(string $movementType): int;

    public function saveWarehouseMovement(
        WarehouseMovementsDTO $warehouseMovementsDTO
    ): ResultPattern;

    public function generateMovementFolio(): string;

    public function filterTransactionsByDateRange(
        MovementsByPeriodFilterDTO $movementsByPeriodFilterDTO
    ): ResultPattern;

    public function isReserved(string $folio): bool;

    public function getWarehouseMovementsByFolio(
        string $folio
    ): ?WarehouseMovementsDTO;
}
