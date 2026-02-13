<?php

namespace App\Mappers;

use App\Contracts\ModelMapperToEntityInterface;
use App\Models\LocationModel;
use App\Enterprise_Layer\Location;

/**
 * @implements ModelMapperToEntityInterface<LocationModel, Location>
 */
class LocationModelToLocationEntityMapper implements ModelMapperToEntityInterface
{
    public function convertModelToEntity($model): Location
    {
        /** @var LocationModel $model */
        $entity = new Location(
            $model->headquarters_name,
            (int) $model->postal_code,
            $model->state,
            $model->city,
            $model->adress
        );

        if ($model->id !== null) {
            $entity->setId((int) $model->id);
        }

        if ($model->created_at !== null) {
            $entity->setCreatedAt($model->created_at);
        }

        if ($model->updated_at !== null) {
            $entity->setUpdatedAt($model->updated_at);
        }

        return $entity;
    }
}
