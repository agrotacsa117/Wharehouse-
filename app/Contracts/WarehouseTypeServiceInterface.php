<?php

namespace App\Contracts;

use App\Application_Layer\ResultPattern;
use App\Mappers\DTO\WarehouseDTO;
use App\Mappers\DTO\WarehouseTypeListDTO;
use App\Mappers\DTO\WarehouseTypeDetailDTO;
use App\Mappers\DTO\Requests\WarehouseRequestDTO;

interface WarehouseTypeServiceInterface
{
    public function getWarehouseTypeById(int $id): ResultPattern;

    public function listWarehouseTypesNames(): array;

    public function createWarehouseType(WarehouseRequestDTO $dto): ResultPattern;

    public function updateWarehouseType(
        int $id,
        WarehouseRequestDTO $dto
    ): ResultPattern;

    public function deleteWarehouseType(int $id): ResultPattern;
}
//app\Contracts\WarehouseTypeServiceInterface.php