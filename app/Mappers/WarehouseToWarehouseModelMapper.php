<?php

namespace App\Mappers;

use App\Contracts\EntityToModelMapperInterface;
use App\Models\WarehouseModel;
use App\Enterprise_Layer\Warehouse;

/**
 * @implements InterfaceMapperToEntity<Warehouse, WarehouseModel>
 */
class WarehouseToWarehouseModelMapper implements EntityToModelMapperInterface
{

    public function convertDomainEntityToModel($tEntity)
    {

        return new WarehouseModel(
            [
                'warehouses_name' => $tEntity->getWarehousesName(),
                'user_id' => $tEntity->getUserId(),
                'creation_date' => $tEntity->getCreationDate(),
                'last_update_date' => $tEntity->getLastUpdateDate(),
                'user_last_update' => $tEntity->getLastUpdateDate(),
                'warehouses_key' => $tEntity->getWarehousesKey(),
                'warehouse_manager' => $tEntity->getWarehouseManager(),
                'phone_number' => $tEntity->getPhoneNumber(),
                'email' => $tEntity->getEmail(),
                'warehouse_type' => $tEntity->getWarehouseType()
            ]
        );
    }
}
