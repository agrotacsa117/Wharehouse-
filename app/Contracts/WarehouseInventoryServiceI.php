<?php

namespace App\Contracts;

use App\Application_Layer\ResultPattern;
use App\Mappers\DTO\Requests\WarehouseInventoryRequestDTO;

interface WarehouseInventoryServiceI
{
    public function saveInventory(
        WarehouseInventoryRequestDTO $warehouseInventoryRequestDTO): ResultPattern;
}
