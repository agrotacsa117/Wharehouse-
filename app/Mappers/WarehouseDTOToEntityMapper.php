<?php

namespace App\Mappers;

use App\Contracts\InterfaceMapperToEntity;
use App\Mappers\DTO\WarehouseDTO;
use App\Enterprise_Layer\Warehouse;


/**
 * @implements RepositoryInterface<WarehouseDTO, Warehouse>
 */
class WarehouseDTOToEntityMapper implements InterfaceMapperToEntity
{

    public function convertDTOToEntity($tDTO)
    {
        Warehouse::builder()->setWarehouseId(
            $tDTO->getWarehouseId()
        )
            ->setUserId(
                $tDTO->getUserId()
            )
            ->setWarehousesName(
                $tDTO->getWarehouseName()
            )
            ->setWarehouseId($tDTO->getWarehouseId());
    }
}
