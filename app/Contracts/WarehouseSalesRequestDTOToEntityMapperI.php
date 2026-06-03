<?php

namespace App\Contracts;

use App\Enterprise_Layer\WarehouseSalesEntity;
use App\Mappers\DTO\Requests\WarehouseSalesRequestDTO;

interface WarehouseSalesRequestDTOToEntityMapperI
{
    public function toWarehouseSalesEntity(
        WarehouseSalesRequestDTO $warehouseSalesRequestDTO
    ): WarehouseSalesEntity;
}
