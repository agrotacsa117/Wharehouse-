<?php

namespace App\Contracts;

use App\Enterprise_Layer\Location;
use App\Mappers\DTO\Requests\LocationRequestDTO;

interface LocationRequestDTOToLocationEntityMapperI
{
    public function convertDTOToEntity(
        LocationRequestDTO $locationRequestDTO
    ): Location;
}
