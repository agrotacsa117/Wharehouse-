<?php

namespace App\Mappers;

use App\Contracts\LocationModelToLocationEntityMapperI;
use App\Enterprise_Layer\Location;
use App\Models\LocationModel;

class LocationModelToLocationEntityMapper implements LocationModelToLocationEntityMapperI
{
    public function convertModelToEntity(LocationModel $model): Location
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
