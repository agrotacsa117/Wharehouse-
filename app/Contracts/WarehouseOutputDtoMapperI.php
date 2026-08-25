<?php

declare(strict_types=1);

namespace App\Contracts;

use App\Mappers\DTO\RemoveWarehouseInventoryStockDTO;
use App\Mappers\DTO\TransferInventoryDTO;
use App\Mappers\DTO\WarehouseInventoryOutDetailDTO;

interface WarehouseOutputDtoMapperI
{
    public function toSimpleOutputDto(array $data, int $userId): RemoveWarehouseInventoryStockDTO;

    public function toSaleDto(array $data, int $userId): RemoveWarehouseInventoryStockDTO;

    public function toRelocationDto(array $data, int $userId): RemoveWarehouseInventoryStockDTO;

    public function toLocationUpdateDto(array $data, int $userId): RemoveWarehouseInventoryStockDTO;

    public function toDto(string $movementType, array $data, int $userId): RemoveWarehouseInventoryStockDTO;

    public function toTransferDto(
        int $warehouseInventoryId,
        WarehouseInventoryOutDetailDTO $inventory,
        array $data
    ): TransferInventoryDTO;
}
