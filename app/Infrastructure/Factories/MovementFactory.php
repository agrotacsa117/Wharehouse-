<?php

namespace App\Infrastructure\Factories;

use App\Mappers\DTO\RemoveWarehouseInventoryStockDTO;
use App\Mappers\DTO\WarehouseMovementsDTO;

class MovementFactory
{
    public static function createStandardOutputRecord(
        RemoveWarehouseInventoryStockDTO $removeWarehouseInventoryStockDTO,
        string $type,
        string $folio
    ): WarehouseMovementsDTO {

        return new WarehouseMovementsDTO(
            $folio,
            $removeWarehouseInventoryStockDTO
                ->getWarehouseInventoryId(),
            $type,
            $removeWarehouseInventoryStockDTO
                ->getQuantity(),
            $removeWarehouseInventoryStockDTO
                ->getReason(),
            $removeWarehouseInventoryStockDTO
                ->getUserId()
        );
    }
}
