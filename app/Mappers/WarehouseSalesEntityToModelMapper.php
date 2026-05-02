<?php

namespace App\Mappers;

use App\Contracts\WarehouseSalesEntityToModelMapperI;
use App\Models\WarehouseSalesModel;
use App\Enterprise_Layer\WarehouseSalesEntity;

class WarehouseSalesEntityToModelMapper implements WarehouseSalesEntityToModelMapperI
{
    public function toWarehouseSalesModel(
        WarehouseSalesEntity $warehouseSalesEntity
    ): WarehouseSalesModel {

        $warehouseSalesModel = new WarehouseSalesModel();

        if ($warehouseSalesEntity->getId()) {
            $warehouseSalesModel->exists = true;
            $warehouseSalesModel->id = $warehouseSalesEntity->getId();
        }

        $warehouseSalesModel
        ->movement_id = $warehouseSalesEntity
        ->getMovementId();
        $warehouseSalesModel
        ->client_id   = $warehouseSalesEntity
        ->getClientId();
        $warehouseSalesModel
        ->invoice_sap = $warehouseSalesEntity
        ->getInvoiceSap();
        
        return $warehouseSalesModel;
    }
}
