<?php

namespace App\Contracts;

use App\Enterprise_Layer\WarehouseType;
use App\Mappers\DTO\WarehouseTypeDetailDTO;

interface WarehouseTypeEntityToWarehouseTypeDetailDTOMapperI
{
    public function convertWarehouseTypeEntityToWarehouseTypeDetailDTO(
        WarehouseType $warehouseType
    ): WarehouseTypeDetailDTO;
}
