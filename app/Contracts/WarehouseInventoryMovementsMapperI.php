<?php

declare(strict_types=1);

namespace App\Contracts;

use App\Enterprise_Layer\WarehouseInventoryMovements;
use App\Mappers\DTO\WarehouseMovementsDTO;

interface WarehouseInventoryMovementsMapperI
{
    public function toWarehouseInventoryMovementsEntity(
        WarehouseMovementsDTO $warehouseMovementsDTO
    ): WarehouseInventoryMovements;
}
