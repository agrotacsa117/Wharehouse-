<?php

namespace App\Mappers;

use App\Contracts\LocationRequestDTOToLocationEntityMapperI;
use App\Enterprise_Layer\Location;
use App\Mappers\DTO\Requests\LocationRequestDTO;

class LocationRequestDTOToLocationEntity implements LocationRequestDTOToLocationEntityMapperI
{
    public function convertDTOToEntity(LocationRequestDTO $tDTO): Location
    {
        return new Location(
            $tDTO->getHeadquartersName(),
            $tDTO->getPostalCode(),
            $tDTO->getState(),
            $tDTO->getCity(),
            $tDTO->getAddress()
        );
    }
}
