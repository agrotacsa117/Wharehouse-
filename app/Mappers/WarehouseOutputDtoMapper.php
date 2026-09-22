<?php

declare(strict_types=1);

namespace App\Mappers;

use App\Contracts\WarehouseOutputDtoMapperI;
use App\Mappers\DTO\RemoveWarehouseInventoryStockDTO;
use App\Mappers\DTO\TransferInventoryDTO;
use App\Mappers\DTO\WarehouseInventoryOutDetailDTO;

class WarehouseOutputDtoMapper implements WarehouseOutputDtoMapperI
{
    public function toRelocationDto(array $data, int $userId): RemoveWarehouseInventoryStockDTO
    {
        $dto = $this->baseDto($data, $userId, 'RELOCATION');

        $dto->setQuantity((int) ($data['quantity'] ?? 0));
        $dto->setRack($this->toNullableInt($data['new_rack'] ?? null));
        $dto->setLevel((int) ($data['new_level'] ?? 0));
        $dto->setOperationDate($data['operation_date'] ?? now()->format('Y-m-d'));
        $dto->setWarehouseId((int) ($data['destination_warehouse_id'] ?? 0));
        $dto->setBay($this->toNullableInt($data['bay'] ?? null));
        $dto->setModule($this->toNullableInt($data['module'] ?? null));
        $dto->setPlatform($this->toNullableInt($data['platform'] ?? null));
        $dto->setManufacturingDate($data['new_manufacturing_date'] ?? null);

        return $dto;
    }

    public function toSaleDto(array $data, int $userId): RemoveWarehouseInventoryStockDTO
    {
        $dto = $this->baseDto($data, $userId, 'SALE');

        if (! empty($data['invoice_sap'])) {
            $dto->setInvoiceId((int) $data['invoice_sap']);
        }

        $dto->setOperationDate($data['operation_date'] ?? now()->format('Y-m-d'));

        return $dto;
    }

    public function toSimpleOutputDto(array $data, int $userId): RemoveWarehouseInventoryStockDTO
    {
        $dto = $this->baseDto($data, $userId, 'OUT');
        $dto->setOperationDate($data['operation_date'] ?? now()->format('Y-m-d'));

        return $dto;
    }

    public function toLocationUpdateDto(array $data, int $userId): RemoveWarehouseInventoryStockDTO
    {
        $dto = $this->baseDto($data, $userId, 'LOCATION_UPDATE');

        $dto->setQuantity((int) ($data['quantity'] ?? 0));
        $dto->setRack($this->toNullableInt($data['new_rack_r'] ?? null));
        $dto->setLevel((int) ($data['new_level_r'] ?? 0));
        $dto->setModule($this->toNullableInt($data['new_module_r'] ?? null));
        $dto->setBay($this->toNullableInt($data['new_bay_r'] ?? null));
        $dto->setPlatform($this->toNullableInt($data['new_platform_r'] ?? null));
        $dto->setReason($data['reason'] ?? '');

        return $dto;
    }

    public function toDto(string $movementType, array $data, int $userId): RemoveWarehouseInventoryStockDTO
    {
        switch ($movementType) {
            case 'RELOCATION':
                return $this->toRelocationDto($data, $userId);
            case 'SALE':
                return $this->toSaleDto($data, $userId);
            case 'LOCATION_UPDATE':
                return $this->toLocationUpdateDto($data, $userId);
            case 'OUT':
            default:
                return $this->toSimpleOutputDto($data, $userId);
        }
    }

    public function toTransferDto(
        int $warehouseInventoryId,
        WarehouseInventoryOutDetailDTO $inventory,
        array $data
    ): TransferInventoryDTO {
        return new TransferInventoryDTO(
            $warehouseInventoryId,
            $inventory->getWarehouseId(),
            $data['destination_warehouse'] ?? '',
            '',
            0,
            $inventory->getLotNumber(),
            (int) ($data['quantity'] ?? 0),
            $data['reason'] ?? '',
            (int) ($data['folio_transfer'] ?? 0)
        );
    }

    private function baseDto(
        array $data,
        int $userId,
        string $movementType
    ): RemoveWarehouseInventoryStockDTO {
        $quantity = (
            $movementType === 'RELOCATION'
        || $movementType === 'LOCATION_UPDATE') ? 0 : (int) ($data['quantity'] ?? 0);
        $dto = new RemoveWarehouseInventoryStockDTO(
            (int) $data['warehouseInventoryId'],
            $quantity,
            $data['reason'] ?? ''
        );

        $dto->setMovementType($movementType);
        $dto->setUserId($userId);

        return $dto;
    }

    private function toNullableInt($value): ?int
    {
        if ($value === null || $value === '') {
            return null;
        }

        return (int) $value;
    }
}
