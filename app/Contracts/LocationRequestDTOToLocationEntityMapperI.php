<?php

namespace App\Contracts;

use App\Mappers\DTO\Requests\LocationRequestDTO;
use App\Enterprise_Layer\Location;

interface LocationRequestDTOToLocationEntityMapperI
{
    public function convertDTOToEntity(
        LocationRequestDTO $locationRequestDTO
    ): Location;

}
