<?php

declare(strict_types=1);

namespace App\Mappers;

use App\Contracts\WarehouseInventoryMovementModelMapperI;
use App\Enterprise_Layer\WarehouseInventoryMovements;
use App\Models\WarehouseInventoryMovementsModel;

class WarehouseInventoryMovementModelMapper implements WarehouseInventoryMovementModelMapperI
{
    public function convertWarehouseInventoryMovementsModelToEntity(
        WarehouseInventoryMovementsModel $warehouseInventoryMovementsModel
    ): WarehouseInventoryMovements {
        $entity = new WarehouseInventoryMovements(
            $warehouseInventoryMovementsModel->folio,
            (int) $warehouseInventoryMovementsModel->warehouse_inventory_id,
            $warehouseInventoryMovementsModel->movement_type,
            (int) $warehouseInventoryMovementsModel->quantity,
            $warehouseInventoryMovementsModel->reason,
            $warehouseInventoryMovementsModel->user_id ? (int) $warehouseInventoryMovementsModel->user_id : null
        );

        $entity->setId((int) $warehouseInventoryMovementsModel->id);

        $entity->setTimestamps(
            $warehouseInventoryMovementsModel->created_at,
            $warehouseInventoryMovementsModel->updated_at
        );

        $entity->setIsReversed($warehouseInventoryMovementsModel->is_reversed);
        $entity->setReversedBy($warehouseInventoryMovementsModel->reversed_by);
        $entity->setReversalOf($warehouseInventoryMovementsModel->reversal_of);

        return $entity;
    }
}
