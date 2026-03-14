<?php

namespace App\Mappers;
use App\Contracts\WarehouseInventoryModelToWarehouseInventoryMapperI;
use App\Enterprise_Layer\WarehouseInventory;
use App\Models\WarehouseInventoryModel;

class WarehouseInventoryModelToWarehouseInventoryMapper implements WarehouseInventoryModelToWarehouseInventoryMapperI{

    
    function convertWarehouseInventoryModelToWarehouseInventory(
        WarehouseInventoryModel $model
    ) : WarehouseInventory{
        $entity = new WarehouseInventory(
            $model->warehouse_id,
            $model->product_id,
            $model->rack,
            $model->_level,
            new \DateTime($model->created_at),
            new \DateTime($model->updated_at),
            $model->warehouse_name,
            $model->quantity,
            $model->lot_number,
            $model->reason,
            new \DateTime($model->expiration_date)
        );

        $entity->setId($model->id);

        return $entity;
    }
} 