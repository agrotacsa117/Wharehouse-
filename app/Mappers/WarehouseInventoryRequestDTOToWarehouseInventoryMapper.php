<?php

declare(strict_types=1);

namespace App\Mappers;

use App\Enterprise_Layer\WarehouseInventory;
use App\Mappers\DTO\Requests\WarehouseInventoryRequestDTO;
use App\Contracts\WarehouseInventoryRequestDTOToWarehouseInventoryMapperI;

class WarehouseInventoryRequestDTOToWarehouseInventoryMapper implements WarehouseInventoryRequestDTOToWarehouseInventoryMapperI
{
    public function convertWarehouseInventoryRequestDTOToWarehouseInventory(
        WarehouseInventoryRequestDTO $warehouseInventoryRequestDTO
    ): WarehouseInventory {

        $now = new \DateTime();
        $warehouseInventory = new WarehouseInventory(
            $warehouseInventoryRequestDTO->getWarehouseId(),
            $warehouseInventoryRequestDTO->getProductId(),
            $warehouseInventoryRequestDTO->getRack(),
            $warehouseInventoryRequestDTO->getLevel(),
            $now, // createdAt
            $now, // updatedAt
            $warehouseName ?? '', // si no lo mandas queda vacío
            $warehouseInventoryRequestDTO->getQuantity(),
            $warehouseInventoryRequestDTO->getLoteNumber(),
            $warehouseInventoryRequestDTO->getReason(),
            $warehouseInventoryRequestDTO->getExpirationDate(),
            $warehouseInventoryRequestDTO->getTransferFolio()
        );

        $warehouseInventory->setModule(
            $warehouseInventoryRequestDTO
            ->getModule()
        );

        $warehouseInventory
        ->setManufacturingDate(
            $warehouseInventoryRequestDTO->getManufacturingDate()
        );

        $warehouseInventory
        ->setBay(
            $warehouseInventoryRequestDTO
            ->getBay()
        );

        $warehouseInventory
        ->setPlatform(
            $warehouseInventoryRequestDTO
            ->getPlatform()
        );
        return $warehouseInventory;
    }
}
