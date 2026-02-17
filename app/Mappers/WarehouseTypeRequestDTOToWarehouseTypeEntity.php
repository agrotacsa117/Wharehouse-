<?php

namespace App\Mappers;

use App\Enterprise_Layer\WarehouseType;
use App\Mappers\DTO\Requests\WarehouseTypeRequestDTO;
use App\Contracts\WarehouseTypeRequestDTOToWarehouseTypeEntityMapperI;

class WarehouseTypeRequestDTOToWarehouseTypeEntity implements WarehouseTypeRequestDTOToWarehouseTypeEntityMapperI
{
    public function convertWarehouseTypeRequestDTOToWarehouseTypeEntity(
        WarehouseTypeRequestDTO $warehouseTypeRequestDTO
    ): WarehouseType {
        
        if (!$warehouseTypeRequestDTO instanceof WarehouseTypeRequestDTO) {
            throw new \InvalidArgumentException(
                'Expected WarehouseTypeRequestDTO'
            );
        }

        return new WarehouseType(
            $warehouseTypeRequestDTO->getCategoryWarehouse()
        );
    }
}
