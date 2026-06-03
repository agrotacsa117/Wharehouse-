<?php

declare(strict_types=1);

namespace App\Mappers;

use App\Contracts\LocationEntityToLocationModelMapperI;
use App\Enterprise_Layer\Location;
use App\Models\LocationModel;

class LocationEntityToLocationModel implements LocationEntityToLocationModelMapperI
{
    public function convertDomainEntityToModel(Location $tEntity): LocationModel
    {
        /** @var Location $entity */
        $model = new LocationModel;

        $model->headquarters_name = $tEntity->getHeadquartersName();
        $model->postal_code = $tEntity->getPostalCode();
        $model->state = $tEntity->getState();
        $model->city = $tEntity->getCity();
        $model->adress = $tEntity->getAddress();
        $model->created_at = $tEntity->getCreatedAt();
        $model->updated_at = $tEntity->getUpdatedAt();

        return $model;
    }
}
