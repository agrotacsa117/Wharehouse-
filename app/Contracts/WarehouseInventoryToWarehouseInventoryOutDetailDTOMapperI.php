<?php

namespace App\Contracts;

use App\Enterprise_Layer\WarehouseInventory;
use App\Mappers\DTO\WarehouseInventoryOutDetailDTO;

interface WarehouseInventoryToWarehouseInventoryOutDetailDTOMapperI
{
    public function convertToOutDetailDTO(WarehouseInventory $warehouseInventory): WarehouseInventoryOutDetailDTO;
}
