<?php

namespace App\Contracts;

use App\Models\WarehouseInventoryMovementsModel;
use App\Enterprise_Layer\WarehouseInventoryMovements;

interface WarehouseInventoryMovementsEntityToModelMapperI
{
    public function mapToInventoryMovementsModel(
        WarehouseInventoryMovements $WarehouseInventoryMovements
    ): WarehouseInventoryMovementsModel;
}
