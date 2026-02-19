<?php

namespace App\Contracts;

use App\Mappers\DTO\Requests\WarehouseInventoryRequestDTO;
use App\Enterprise_Layer\WarehouseInventory;

//WarehouseInventoryRequestDTOToWarehouseInventoryEntityMapperI
interface WarehouseInventoryRequestDTOToWarehouseInventoryEntityMapper
{
    public function convertWarehouseInventoryRequestDTOToWarehouseInventoryEntityMapper(
        WarehouseInventoryRequestDTO $warehouseInventoryRequestDTO
    ): WarehouseInventory;

}
