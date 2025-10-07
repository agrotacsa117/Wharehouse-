<?php

namespace App\Mappers;

use App\Contracts\InterfaceMapperToEntity;
use App\Mappers\DTO\WarehouseDTO;
use app\Enterprise_Layer\Warehouse;



/**
 * @implements RepositoryInterface<WarehouseDTO, Warehouse>
 */

class WarehouseDTOToEntityMapper  implements InterfaceMapperToEntity
{

    public function convertDTOToEntity($tDTO)
    {
        return new Warehouse(
            $tDTO->getWarehouseName(),
            $tDTO->getUserId(),
            $tDTO->getUserLastUpdate(),
            $tDTO->getWarehouseKey(),
            $tDTO->getResponsiblePersonName(),
            $tDTO->getPhoneNumber(),
            $tDTO->getEmail(),
            $tDTO->getWarehouseType()
        );
    }
}
