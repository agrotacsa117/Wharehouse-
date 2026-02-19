<?php

declare(strict_types=1);

namespace App\Contracts;

use App\Models\WarehouseInventoryModel;
use App\Enterprise_Layer\WarehouseInventory;

interface WarehouseInventoryEntityToWarehouseInventoryModelMapperI
{
    public function warehouseInventoryEntityToWarehouseInventoryModel(WarehouseInventory $warehouseInventory): WarehouseInventoryModel;
}
