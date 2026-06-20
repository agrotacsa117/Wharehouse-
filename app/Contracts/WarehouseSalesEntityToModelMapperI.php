<?php

namespace App\Contracts;

use App\Enterprise_Layer\WarehouseSalesEntity;
use App\Models\WarehouseSalesModel;

interface WarehouseSalesEntityToModelMapperI
{
    public function toWarehouseSalesModel(
        WarehouseSalesEntity $warehouseSalesEntity
    ): WarehouseSalesModel;
}
