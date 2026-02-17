<?php

namespace App\Mappers;

use App\Contracts\WarehouseTypeEntityToWarehouseTypeDetailDTOMapperI;
use App\Enterprise_Layer\WarehouseType;
use App\Mappers\DTO\WarehouseTypeDetailDTO;


class WarehouseTypeEntityToWarehouseTypeDetailDTO implements WarehouseTypeEntityToWarehouseTypeDetailDTOMapperI
{
    public function convertWarehouseTypeEntityToWarehouseTypeDetailDTO( WarehouseType $warehouseType): WarehouseTypeDetailDTO
    {
        return new WarehouseTypeDetailDTO(
            $warehouseType->getId(),
            $warehouseType->getCategoryWarehouse(),
            $warehouseType->getCreatedAt(),
            $warehouseType->getUpdatedAt()
        );
    }
}
