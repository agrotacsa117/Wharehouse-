<?php

declare(strict_types=1);

namespace App\Mappers;

use App\Mappers\DTO\WarehouseMovementsDTO;
use App\Enterprise_Layer\WarehouseInventoryMovements;
use App\Contracts\WarehouseInventoryMovementsMapperI;

class WarehouseInventoryMovementsMapper implements WarehouseInventoryMovementsMapperI
{
    public function toWarehouseInventoryMovementsEntity(
        WarehouseMovementsDTO $warehouseMovementsDTO
    ): WarehouseInventoryMovements {
        return new WarehouseInventoryMovements(
            $warehouseMovementsDTO->getFolio(),
            $warehouseMovementsDTO->getWarehouseInventoryId(),
            $warehouseMovementsDTO->getMovementType(),
            $warehouseMovementsDTO->getQuantity(),
            $warehouseMovementsDTO->getReason(),
            $warehouseMovementsDTO->getUserId()
        );
    }
}
