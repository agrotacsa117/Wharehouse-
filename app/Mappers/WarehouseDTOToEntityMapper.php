<?php

namespace App\Mappers;

use App\Contracts\WarehouseDTOToEntityMapperInterface;
use App\Enterprise_Layer\Warehouse;
use App\Mappers\DTO\WarehouseDTO;

/**
 * @implements WarehouseDTOToEntityMapperInterface<WarehouseDTO, Warehouse>
 */
class WarehouseDTOToEntityMapper implements WarehouseDTOToEntityMapperInterface
{
    public function convertDTOToEntity($tDTO): Warehouse
    {
        $warehouse = Warehouse::builder()
            ->setUserId($tDTO->getUserId())
            ->setWarehousesName($tDTO->getWarehouseName())
            ->setWarehousesKey($tDTO->getWarehouseKey())
            ->setWarehouseManager($tDTO->getResponsiblePersonName())
            ->setPhoneNumber($tDTO->getPhoneNumber())
            ->setEmail($tDTO->getEmail())
            ->setWarehouseTypeId($tDTO->getWarehouseTypeId())
            ->setUserLastUpdate($tDTO->getUserLastUpdate())
            ->setLocationId($tDTO->getLocationId())
            ->build();

        if ($tDTO->getId() > 0) {
            $warehouse->setWarehouseId($tDTO->getId());
        }

        return $warehouse;
    }
}
