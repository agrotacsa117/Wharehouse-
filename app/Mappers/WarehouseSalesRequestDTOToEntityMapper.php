<?php

namespace App\Mappers;

use App\Contracts\WarehouseSalesRequestDTOToEntityMapperI;
use App\Enterprise_Layer\WarehouseSalesEntity;
use App\Mappers\DTO\Requests\WarehouseSalesRequestDTO;

class WarehouseSalesRequestDTOToEntityMapper implements WarehouseSalesRequestDTOToEntityMapperI
{
    public function toWarehouseSalesEntity(
        WarehouseSalesRequestDTO $warehouseSalesRequestDTO
    ): WarehouseSalesEntity {

        return new WarehouseSalesEntity(
            $warehouseSalesRequestDTO->getMovementId(),
            $warehouseSalesRequestDTO->getInvoiceSap()
        );

    }
}
