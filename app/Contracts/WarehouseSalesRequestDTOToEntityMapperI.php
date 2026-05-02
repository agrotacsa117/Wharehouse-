<?php

namespace App\Contracts;

use App\Mappers\DTO\Requests\WarehouseSalesRequestDTO;
use App\Enterprise_Layer\WarehouseSalesEntity;

interface WarehouseSalesRequestDTOToEntityMapperI
{
    public function toWarehouseSalesEntity(
        WarehouseSalesRequestDTO $warehouseSalesRequestDTO
    ): WarehouseSalesEntity;
}
