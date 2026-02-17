<?php

namespace App\Contracts;

use App\Enterprise_Layer\Location;
use App\Models\LocationModel;

interface LocationModelToLocationEntityMapperI
{
    public function convertModelToEntity(
        LocationModel $locationModel
    ): Location;
}
