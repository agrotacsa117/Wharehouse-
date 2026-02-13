<?php

namespace App\Contracts;

use App\Application_Layer\ResultPattern;
use App\Mappers\DTO\Requests\LocationRequestDTO;
use App\Mappers\DTO\LocationDetailDTO;

interface LocationServiceInterface
{
    public function getLocationById(int $id): ResultPattern;

    public function listHeadquartersNames(): array;

    public function createLocation(LocationRequestDTO $dto): ResultPattern;

    public function updateLocation(
        int $id,
        LocationRequestDTO $dto
    ): ResultPattern;

    public function deleteLocation(int $id): ResultPattern;
}
