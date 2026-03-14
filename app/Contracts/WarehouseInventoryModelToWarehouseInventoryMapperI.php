<?php

namespace App\Contracts;

use App\Enterprise_Layer\WarehouseInventory;
use App\Models\WarehouseInventoryModel;

interface WarehouseInventoryModelToWarehouseInventoryMapperI{


    function convertWarehouseInventoryModelToWarehouseInventory(
        WarehouseInventoryModel $model
    ) : WarehouseInventory;
} 