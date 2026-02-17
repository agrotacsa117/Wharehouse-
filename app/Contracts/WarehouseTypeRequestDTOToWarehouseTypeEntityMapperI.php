<?php

namespace App\Contracts;

use App\Enterprise_Layer\WarehouseType;
use App\Mappers\DTO\Requests\WarehouseTypeRequestDTO;

interface WarehouseTypeRequestDTOToWarehouseTypeEntityMapperI
{
    public function convertWarehouseTypeRequestDTOToWarehouseTypeEntity(
        WarehouseTypeRequestDTO $warehouseTypeRequestDTO
    ): WarehouseType;
}
