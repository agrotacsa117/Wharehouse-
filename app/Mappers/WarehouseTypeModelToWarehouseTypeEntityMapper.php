<?php

namespace App\Mappers;

use App\Contracts\ModelMapperToEntityInterface;
use App\Models\WarehouseTypeModel;
use App\Enterprise_Layer\WarehouseType;

/**
 * @implements ModelMapperToEntityInterface<WarehouseTypeModel, WarehouseType>
 */
class WarehouseTypeModelToWarehouseTypeEntityMapper implements ModelMapperToEntityInterface
{
    public function convertModelToEntity($model): WarehouseType
    {
        if (!$model instanceof WarehouseTypeModel) {
            throw new \InvalidArgumentException(
                "Expected instance of WarehouseTypeModel"
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
