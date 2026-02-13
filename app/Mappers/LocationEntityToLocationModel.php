<?php

declare(strict_types=1);

namespace App\Mappers;

use App\Enterprise_Layer\Location;
use App\Models\LocationModel;
use App\Contracts\EntityToModelMapperInterface;

/**
 * @implements EntityToModelMapperInterface<Location, LocationModel>
 */
class LocationEntityToLocationModel implements EntityToModelMapperInterface
{
    public function convertDomainEntityToModel($tEntity): LocationModel
    {
        /** @var Location $entity */
        $model = new LocationModel();


        if ($tEntity->getId() !== 0) {
            $model->id = $tEntity->getId();
        }

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
