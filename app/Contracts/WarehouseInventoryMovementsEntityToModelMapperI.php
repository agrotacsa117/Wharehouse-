<?php

namespace App\Contracts;

use App\Enterprise_Layer\WarehouseInventoryMovements;
use App\Models\WarehouseInventoryMovementsModel;

interface WarehouseInventoryMovementsEntityToModelMapperI
{
    public function mapToInventoryMovementsModel(
        WarehouseInventoryMovements $WarehouseInventoryMovements
    ): WarehouseInventoryMovementsModel;
}
