<?php

namespace App\Contracts;

use App\Enterprise_Layer\Location;

interface LocationRepositoryInterface
{
    public function findById(int $id): ?Location;

    public function getAllHeadquartersName(): array;

    public function saveLocation(Location $location): void;

    public function deleteLocation(int $idLocation): void;

    public function updateLocation(Location $location): void;
}
