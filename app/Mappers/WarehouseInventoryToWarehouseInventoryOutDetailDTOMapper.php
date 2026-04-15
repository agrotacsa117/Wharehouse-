<?php

namespace App\Mappers;

use App\Contracts\WarehouseInventoryToWarehouseInventoryOutDetailDTOMapperI;
use App\Enterprise_Layer\WarehouseInventory;
use App\Mappers\DTO\WarehouseInventoryOutDetailDTO;

class WarehouseInventoryToWarehouseInventoryOutDetailDTOMapper implements WarehouseInventoryToWarehouseInventoryOutDetailDTOMapperI
{
    public function convertToOutDetailDTO(WarehouseInventory $warehouseInventory): WarehouseInventoryOutDetailDTO
    {
        return new WarehouseInventoryOutDetailDTO(
            $warehouseInventory->getId(),
            $warehouseInventory->getWarehouseId(),
            $warehouseInventory->getRack(),
            $warehouseInventory->getLevel(),
            (string)$warehouseInventory->getProductId(),
            $warehouseInventory->getWarehouseName(),
            $warehouseInventory->getQuantity(),
            $warehouseInventory->getLotNumber(),
            $warehouseInventory->getExpirationDate()->format('Y-m-d')
        );
    }

}
