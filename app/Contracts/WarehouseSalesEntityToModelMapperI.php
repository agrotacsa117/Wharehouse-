<?php

namespace App\Contracts;

use App\Models\WarehouseSalesModel;
use App\Enterprise_Layer\WarehouseSalesEntity;

interface WarehouseSalesEntityToModelMapperI
{
    public function toWarehouseSalesModel(
        WarehouseSalesEntity $warehouseSalesEntity
    ): WarehouseSalesModel;

}
