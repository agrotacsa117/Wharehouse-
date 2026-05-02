<?php

namespace App\Mappers;

use App\Contracts\WarehouseSalesRequestDTOToEntityMapperI;
use App\Mappers\DTO\Requests\WarehouseSalesRequestDTO;
use App\Enterprise_Layer\WarehouseSalesEntity;

class WarehouseSalesRequestDTOToEntityMapper implements WarehouseSalesRequestDTOToEntityMapperI
{
    public function toWarehouseSalesEntity(
        WarehouseSalesRequestDTO $warehouseSalesRequestDTO
    ): WarehouseSalesEntity {

        return new WarehouseSalesEntity(
            $warehouseSalesRequestDTO->getMovementId(),
            $warehouseSalesRequestDTO->getClientId(),
            $warehouseSalesRequestDTO->getInvoiceSap()
        );

    }
}
