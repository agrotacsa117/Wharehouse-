<?php

namespace App\Contracts;

use App\Enterprise_Layer\WarehouseInventoryMovements;
use App\Mappers\DTO\WarehouseMovementsDTO;

interface WarehouseMovementMapperI
{
    public function convertToWarehouseMovementsDTO(
        WarehouseInventoryMovements $warehouseInventoryMovements
    ): WarehouseMovementsDTO;
}
