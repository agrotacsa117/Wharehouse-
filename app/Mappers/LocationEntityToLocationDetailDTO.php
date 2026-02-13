<?php

namespace App\Mappers;

use App\Contracts\InterfaceEntityToDTOMapper;
use App\Enterprise_Layer\Location;
use App\Mappers\DTO\LocationDetailDTO;

/**
 * @implements InterfaceMapperToEntity<LocationDetailDTO, Location>
 */
class LocationEntityToLocationDetailDTO implements InterfaceEntityToDTOMapper
{
    public function convertEntityToDTO($location)
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
