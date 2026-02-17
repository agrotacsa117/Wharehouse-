<?php

namespace App\Mappers;

use App\Contracts\LocationEntityToLocationDetailDTOMapperI;
use App\Enterprise_Layer\Location;
use App\Mappers\DTO\LocationDetailDTO;


class LocationEntityToLocationDetailDTO implements LocationEntityToLocationDetailDTOMapperI
{
    public function convertEntityToDTO(Location $location): LocationDetailDTO
    {
        return new LocationDetailDTO(
            $location->getId(),
            $location->getHeadquartersName(),
            $location->getPostalCode(),
            $location->getState(),
            $location->getCity(),
            $location->getAddress(),
            $location->getCreatedAt(),
            $location->getUpdatedAt()
        );
    }
}
