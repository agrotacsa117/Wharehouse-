<?php

namespace App\Contracts;

use App\Enterprise_Layer\Location;
use App\Mappers\DTO\LocationDetailDTO;

interface LocationEntityToLocationDetailDTOMapperI
{
    public function convertEntityToDTO(Location $tEntity): LocationDetailDTO;
}
