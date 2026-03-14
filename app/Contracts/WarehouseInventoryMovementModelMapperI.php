<?php

namespace App\Contracts;

use App\Enterprise_Layer\WarehouseInventoryMovements;
use App\Models\WarehouseInventoryMovementsModel;

interface WarehouseInventoryMovementModelMapperI
{
    public function convertWarehouseInventoryMovementsModelToEntity(
        WarehouseInventoryMovementsModel $warehouseInventoryMovementsModel
        ): WarehouseInventoryMovements;
}
