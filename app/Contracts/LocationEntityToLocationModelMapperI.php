<?php

namespace App\Contracts;

use App\Enterprise_Layer\Location;
use App\Models\LocationModel;

interface LocationEntityToLocationModelMapperI
{
    public function convertDomainEntityToModel(
        Location $location
    ): LocationModel;
}
