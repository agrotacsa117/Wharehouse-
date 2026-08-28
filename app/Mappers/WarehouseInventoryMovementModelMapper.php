<?php

declare(strict_types=1);

namespace App\Mappers;

use App\Contracts\WarehouseInventoryMovementModelMapperI;
use App\Enterprise_Layer\WarehouseInventoryMovements;
use App\Models\WarehouseInventoryMovementsModel;
use Illuminate\Support\Carbon;

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

        // 2. ID
        $entity->setId((int) $warehouseInventoryMovementsModel->id);

        // 3. Fechas de sistema (Timestamps)
        if ($warehouseInventoryMovementsModel->created_at && $warehouseInventoryMovementsModel->updated_at) {
            $entity->setTimestamps(
                Carbon::parse($warehouseInventoryMovementsModel->created_at),
                Carbon::parse($warehouseInventoryMovementsModel->updated_at)
            );
        }

        // 4. Atributos adicionales (aquí es donde faltaban los que mencionaste)

        // Operation Date
        if ($warehouseInventoryMovementsModel->operation_date) {
            $entity->setOperationDate(Carbon::parse($warehouseInventoryMovementsModel->operation_date));
        }

        // Source Warehouse ID
        if ($warehouseInventoryMovementsModel->source_warehouse_id !== null) {
            $entity->setSourceWarehouseId((int) $warehouseInventoryMovementsModel->source_warehouse_id);
        }

        // Transfer Folio
        if ($warehouseInventoryMovementsModel->transfer_folio !== null) {
            $entity->setTransferFolio((int) $warehouseInventoryMovementsModel->transfer_folio);
        }

        // 5. Flags y Reversiones
        $entity->setIsReversed((bool) $warehouseInventoryMovementsModel->is_reversed);

        if ($warehouseInventoryMovementsModel->reversed_by !== null) {
            $entity->setReversedBy((int) $warehouseInventoryMovementsModel->reversed_by);
        }

        if ($warehouseInventoryMovementsModel->reversal_of !== null) {
            $entity->setReversalOf((int) $warehouseInventoryMovementsModel->reversal_of);
        }

        return $entity;
    }
}
