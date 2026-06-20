<?php

namespace App\Contracts;

use App\Application_Layer\ResultPattern;
use App\Mappers\DTO\Requests\WarehouseTypeRequestDTO;

interface WarehouseTypeServiceInterface
{
    public function getWarehouseTypeById(int $id): ResultPattern;

    public function listWarehouseTypesNames(): array;

    public function createWarehouseType(WarehouseTypeRequestDTO $dto): ResultPattern;

    public function updateWarehouseType(
        int $id,
        WarehouseTypeRequestDTO $dto
    ): ResultPattern;

    public function deleteWarehouseType(int $id): ResultPattern;
}
