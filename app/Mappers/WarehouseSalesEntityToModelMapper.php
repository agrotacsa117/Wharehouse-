<?php

namespace App\Mappers;

use App\Contracts\WarehouseSalesEntityToModelMapperI;
use App\Enterprise_Layer\WarehouseSalesEntity;
use App\Models\WarehouseSalesModel;

class WarehouseSalesEntityToModelMapper implements WarehouseSalesEntityToModelMapperI
{
    public function toWarehouseSalesModel(
        WarehouseSalesEntity $warehouseSalesEntity
    ): WarehouseSalesModel {

        $warehouseSalesModel = new WarehouseSalesModel;

        if ($warehouseSalesEntity->getId()) {
            $warehouseSalesModel->exists = true;
            $warehouseSalesModel->id = $warehouseSalesEntity->getId();
        }

        $warehouseSalesModel
            ->movement_id = $warehouseSalesEntity
            ->getMovementId();
        $warehouseSalesModel
            ->invoice_sap = $warehouseSalesEntity
            ->getInvoiceSap();

        return $warehouseSalesModel;
    }
}
