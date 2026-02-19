<?php

namespace App\Contracts;

use App\Enterprise_Layer\WarehouseInventory;
use App\Mappers\DTO\Requests\WarehouseInventoryRequestDTO;

interface WarehouseInventoryRequestDTOToWarehouseInventoryMapperI
{
    public function convertWarehouseInventoryRequestDTOToWarehouseInventory(
        WarehouseInventoryRequestDTO $warehouseInventoryRequestDTO
    ): WarehouseInventory;
}
