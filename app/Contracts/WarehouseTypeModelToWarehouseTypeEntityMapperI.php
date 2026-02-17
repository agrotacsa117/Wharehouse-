<?php

namespace App\Contracts;

use App\Enterprise_Layer\WarehouseType;
use App\Models\WarehouseTypeModel;

interface WarehouseTypeModelToWarehouseTypeEntityMapperI
{
    public function convertWarehouseTypeModelToWarehouseTypeEntity(
        WarehouseTypeModel $warehouseTypeModel
    ): WarehouseType;


}
