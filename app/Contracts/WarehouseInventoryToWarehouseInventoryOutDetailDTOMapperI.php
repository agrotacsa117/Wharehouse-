<?php

namespace App\Contracts;

use App\Mappers\DTO\WarehouseInventoryOutDetailDTO;
use App\Enterprise_Layer\WarehouseInventory;

interface WarehouseInventoryToWarehouseInventoryOutDetailDTOMapperI
{
    public function convertToOutDetailDTO(WarehouseInventory $warehouseInventory): WarehouseInventoryOutDetailDTO;
}
