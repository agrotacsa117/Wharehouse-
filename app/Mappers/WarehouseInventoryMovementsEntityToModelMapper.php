<?php

namespace App\Mappers;

use App\Contracts\WarehouseInventoryMovementsEntityToModelMapperI;
use App\Enterprise_Layer\WarehouseInventoryMovements;
use App\Models\WarehouseInventoryMovementsModel;

class WarehouseInventoryMovementsEntityToModelMapper implements WarehouseInventoryMovementsEntityToModelMapperI
{
    public function mapToInventoryMovementsModel(
        WarehouseInventoryMovements $warehouseInventoryMovements
    ): WarehouseInventoryMovementsModel {

        $model = new WarehouseInventoryMovementsModel;

        $model->folio = $warehouseInventoryMovements->getFolio();
        $model->warehouse_inventory_id = $warehouseInventoryMovements->getWarehouseInventoryId();
        $model->movement_type = $warehouseInventoryMovements->getMovementType();
        $model->quantity = $warehouseInventoryMovements->getQuantity();
        $model->reason = $warehouseInventoryMovements->getReason();
        $model->user_id = $warehouseInventoryMovements->getUserId();

        if ($warehouseInventoryMovements->getTransferFolio() !== null) {
            $model->transfer_folio = $warehouseInventoryMovements->getTransferFolio();
        }
        // Si la entidad ya trae ID (caso update)
        if ($warehouseInventoryMovements->getId() !== null) {
            $model->id = $warehouseInventoryMovements->getId();
        }

        if ($warehouseInventoryMovements->getSourceWarehouseId() !== null) {
            $model->source_warehouse_id = $warehouseInventoryMovements->getSourceWarehouseId();
        }

        if ($warehouseInventoryMovements->getOperationDate() !== null) {
            $model->operation_date = $warehouseInventoryMovements->getOperationDate();
        }

        $model->is_reversed = $warehouseInventoryMovements
            ->isReversed();
        $model->reversed_by = $warehouseInventoryMovements
            ->getReversedBy();
        $model->reversal_of = $warehouseInventoryMovements
            ->getReversalOf();

        if ($warehouseInventoryMovements->getRelocatedFromInventoryId() !== null) {
            $model->relocated_from_inventory_id = $warehouseInventoryMovements->getRelocatedFromInventoryId();
        }

        if ($warehouseInventoryMovements->getRelocatedToInventoryId() !== null) {
            $model->relocated_to_inventory_id = $warehouseInventoryMovements->getRelocatedToInventoryId();
        }

        return $model;
    }
}
