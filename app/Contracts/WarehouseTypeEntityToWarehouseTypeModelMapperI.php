<?php

namespace App\Contracts;

use App\Enterprise_Layer\WarehouseType;
use App\Models\WarehouseTypeModel;

interface WarehouseTypeEntityToWarehouseTypeModelMapperI
{
    public function convertWarehouseTypeDomainEntityToWarehouseTypeModel(
        WarehouseType $warehouseType
    ): WarehouseTypeModel;
}
