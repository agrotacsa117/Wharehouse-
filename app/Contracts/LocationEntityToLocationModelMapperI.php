<?php

namespace App\Contracts;

use App\Models\LocationModel;
use App\Enterprise_Layer\Location;

interface LocationEntityToLocationModelMapperI
{
    public function convertDomainEntityToModel(
        Location $location
    ): LocationModel;
}
