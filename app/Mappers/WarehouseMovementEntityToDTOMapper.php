<?php

namespace App\Mappers;

use App\Contracts\WarehouseMovementMapperI;
use App\Enterprise_Layer\WarehouseInventoryMovements;
use App\Mappers\DTO\WarehouseMovementsDTO;

class WarehouseMovementEntityToDTOMapper implements WarehouseMovementMapperI
{
    public function convertToWarehouseMovementsDTO(
        WarehouseInventoryMovements $warehouseInventoryMovements
    ): WarehouseMovementsDTO {
        $dto = new WarehouseMovementsDTO(
            $warehouseInventoryMovements->getFolio(),
            $warehouseInventoryMovements->getWarehouseInventoryId(),
            $warehouseInventoryMovements->getMovementType(),
            $warehouseInventoryMovements->getQuantity(),
            $warehouseInventoryMovements->getReason(),
            $warehouseInventoryMovements->getUserId()
        );

        $dto->setOperationDate($warehouseInventoryMovements->getOperationDate());
        $dto->setSourceWarehouseId($warehouseInventoryMovements->getSourceWarehouseId());
        $dto->setTransferFolio($warehouseInventoryMovements->getTransferFolio());
        $dto->setIsReversed($warehouseInventoryMovements->isReversed());
        $dto->setReversedBy($warehouseInventoryMovements->getReversedBy());
        $dto->setReversedOf($warehouseInventoryMovements->getReversalOf());
        $dto->setRelocatedFromInventoryId($warehouseInventoryMovements->getRelocatedFromInventoryId());
        $dto->setRelocatedToInventoryId($warehouseInventoryMovements->getRelocatedToInventoryId());

        return $dto;
    }
}
