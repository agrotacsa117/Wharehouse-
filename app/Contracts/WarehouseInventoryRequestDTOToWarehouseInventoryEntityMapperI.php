<?php

namespace App\Contracts;

use App\Enterprise_Layer\WarehouseInventory;
use App\Mappers\DTO\Requests\WarehouseInventoryRequestDTO;

// WarehouseInventoryRequestDTOToWarehouseInventoryEntityMapperI
interface WarehouseInventoryRequestDTOToWarehouseInventoryEntityMapper
{
    public function convertWarehouseInventoryRequestDTOToWarehouseInventoryEntityMapper(
        WarehouseInventoryRequestDTO $warehouseInventoryRequestDTO
    ): WarehouseInventory;
}
