<?php

namespace App\Mappers;

use App\Contracts\InterfaceMapperToEntity;
use App\Mappers\DTO\WarehouseDTO;
use App\Enterprise_Layer\Warehouse;

/**
 * @implements InterfaceMapperToEntity<WarehouseDTO, Warehouse>
 */
class WarehouseDTOToEntityMapper implements InterfaceMapperToEntity
{
    public function convertDTOToEntity($tDTO): Warehouse
    {
        return Warehouse::builder()->setWarehouseId(
            $tDTO->getWarehouseId()
        )->setUserId(
            $tDTO->getUserId()
        )->setWarehousesName(
            $tDTO->getWarehouseName()
        )->setWarehouseId(
            $tDTO->getWarehouseId()
        )->setWarehousesKey(
            $tDTO->getWarehouseKey()
        )->setWarehouseManager(
            $tDTO->getResponsiblePersonName()
        )->setPhoneNumber(
            $tDTO->getPhoneNumber()
        )->setEmail(
            $tDTO->getEmail()
        )->setWarehouseTypeId(
            $tDTO->getWarehouseType()
        )->setUserLastUpdate(
            $tDTO->getUserLastUpdate()
        )->build();
    }
}
