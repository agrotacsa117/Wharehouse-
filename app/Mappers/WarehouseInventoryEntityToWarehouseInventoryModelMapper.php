<?php

declare(strict_types=1);

namespace App\Mappers;

use App\Contracts\WarehouseInventoryEntityToWarehouseInventoryModelMapperI;
use App\Enterprise_Layer\WarehouseInventory;
use App\Models\WarehouseInventoryModel;

class WarehouseInventoryEntityToWarehouseInventoryModelMapper implements WarehouseInventoryEntityToWarehouseInventoryModelMapperI
{
    public function warehouseInventoryEntityToWarehouseInventoryModel(
        WarehouseInventory $warehouseInventory
    ): WarehouseInventoryModel {

        $warehouseInventoryModel = new WarehouseInventoryModel;

        $warehouseInventoryModel->warehouse_id = $warehouseInventory->getWarehouseId();
        $warehouseInventoryModel->product_id = $warehouseInventory->getProductId();
        $warehouseInventoryModel->rack = $warehouseInventory->getRack();
        $warehouseInventoryModel->_level = $warehouseInventory->getLevel();
        $warehouseInventoryModel->warehouse_name = $warehouseInventory->getWarehouseName();
        $warehouseInventoryModel->quantity = $warehouseInventory->getQuantity();
        $warehouseInventoryModel->lot_number = $warehouseInventory->getLotNumber();
        $warehouseInventoryModel->reason = $warehouseInventory->getReason();
        $warehouseInventoryModel->created_at = $warehouseInventory->getCreatedAt()->format('Y-m-d H:i:s');
        $warehouseInventoryModel->updated_at = $warehouseInventory->getUpdatedAt()->format('Y-m-d H:i:s');
        $warehouseInventoryModel->expiration_date = $warehouseInventory->getExpirationDate();
        $warehouseInventoryModel->manufacturing_date = $warehouseInventory->getManufacturingDate();
        $warehouseInventoryModel->module = $warehouseInventory->getModule();
        $warehouseInventoryModel->bay = $warehouseInventory->getBay();
        $warehouseInventoryModel->platform = $warehouseInventory->getPlatform();
        $warehouseInventoryModel->transfer_folio = $warehouseInventory->getTransferFolio();

        return $warehouseInventoryModel;
    }
}
