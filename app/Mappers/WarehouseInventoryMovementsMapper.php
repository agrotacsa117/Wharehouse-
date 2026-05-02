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

        $warehouseInventoryMovements = new WarehouseInventoryMovements(
            $warehouseMovementsDTO->getFolio(),
            $warehouseMovementsDTO->getWarehouseInventoryId(),
            $warehouseMovementsDTO->getMovementType(),
            $warehouseMovementsDTO->getQuantity(),
            $warehouseMovementsDTO->getReason(),
            $warehouseMovementsDTO->getUserId()
        );
        
        
        if ($warehouseMovementsDTO->getSourceWarehouseId() !== null) {
            $warehouseInventoryMovements->setSourceWarehouseId(
                $warehouseMovementsDTO->getSourceWarehouseId()
            );
        }

        


        if ($warehouseMovementsDTO->getOperationDate() !== null) {
            $warehouseInventoryMovements->setOperationDate(
                $warehouseMovementsDTO->getOperationDate()
            );
        }

        return $warehouseInventoryMovements;
    }
}
