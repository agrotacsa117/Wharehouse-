<?php

declare(strict_types=1);

namespace App\Contracts;

use App\Enterprise_Layer\WarehouseInventory;
use App\Models\WarehouseInventoryModel;

interface WarehouseInventoryEntityToWarehouseInventoryModelMapperI
{
    public function warehouseInventoryEntityToWarehouseInventoryModel(WarehouseInventory $warehouseInventory): WarehouseInventoryModel;
}
