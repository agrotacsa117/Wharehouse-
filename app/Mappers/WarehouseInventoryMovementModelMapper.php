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
            $model->folio,
            (int) $model->warehouse_inventory_id,
            $model->movement_type,
            (int) $model->quantity,
            $model->reason,
            $model->user_id ? (int) $model->user_id : null
        );

        $entity->setId((int) $model->id);

        $entity->setTimestamps(
            $model->created_at,
            $model->updated_at
        );

        return  $entity;
    }
}
