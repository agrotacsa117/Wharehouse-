<?php

namespace App\Contracts;

use App\Application_Layer\ResultPattern;
use App\Mappers\DTO\WarehouseMovementsDTO;
use App\Mappers\DTO\MovementsByPeriodFilterDTO;

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

    public function getMovementsByProductId(string $productId): array;

    public function getRecentMovements(int $limit = 10): array;

    public function getRecentMovementsByWarehouseId(int $warehouseId, int $limit = 10): array;


}
