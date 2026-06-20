<?php

namespace App\Mappers;

use App\Contracts\WarehouseTypeModelToWarehouseTypeEntityMapperI;
use App\Enterprise_Layer\WarehouseType;
use App\Models\WarehouseTypeModel;

class WarehouseTypeModelToWarehouseTypeEntityMapper implements WarehouseTypeModelToWarehouseTypeEntityMapperI
{
    public function convertWarehouseTypeModelToWarehouseTypeEntity(WarehouseTypeModel $model): WarehouseType
    {
        if (! $model instanceof WarehouseTypeModel) {
            throw new \InvalidArgumentException(
                'Expected instance of WarehouseTypeModel'
            );
        }

        $entity = new WarehouseType(
            $model->category_warehouse
        );

        $entity->setId($model->id);

        if ($model->created_at) {
            $entity->setCreatedAt(
                new \DateTime(
                    $model->created_at
                        ->toDateTimeString()
                )
            );
        }

        if ($model->updated_at) {
            $entity->setUpdatedAt(
                new \DateTime(
                    $model->updated_at
                        ->toDateTimeString()
                )
            );
        }

        return $entity;
    }
}
