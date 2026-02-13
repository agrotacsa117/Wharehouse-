<?php

namespace App\Mappers;

use App\Contracts\InterfaceMapperToEntity;
use App\Enterprise_Layer\Location;
use App\Mappers\DTO\Requests\LocationRequestDTO;

/**
 * @implements InterfaceMapperToEntity<LocationRequestDTO, Location>
 */
class LocationRequestDTOToLocationEntity implements InterfaceMapperToEntity
{
    /**
     * @param TDTO $tDTO
     * @return TEntity
     */
    public function convertDTOToEntity($tDTO): Location
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
