<?php

namespace App\Mappers;

use App\Contracts\EntityToModelMapperInterface;
use App\Models\WarehouseModel;
use App\Enterprise_Layer\Warehouse;
use App\Contracts\WarehouseEntityToWarehouseModelMapperI;
/**
 * @implements EntityToModelMapperInterface<Warehouse, WarehouseModel>
 */
class WarehouseToWarehouseModelMapper implements WarehouseEntityToWarehouseModelMapperI
{
    public function convertDomainEntityToModel($tEntity)
    {
        return new WarehouseModel(
            [
                'warehouses_name' => $tEntity->getWarehousesName(),
                'user_last_update' => $tEntity->getUserLastUpdate(),
                'created_at' => $tEntity->getCreationDate(),
                'updated_at' => $tEntity->getLastUpdateDate(),
                'warehouses_key' => $tEntity->getWarehousesKey(),
                'warehouse_manager' => $tEntity->getWarehouseManager(),
                'phone_number' => $tEntity->getPhoneNumber(),
                'email' => $tEntity->getEmail(),
                'warehouse_type_id' => $tEntity->getWarehouseTypeId(),
                'location_id' => $tEntity->getLocationId()
            ]
        );
    }
}
