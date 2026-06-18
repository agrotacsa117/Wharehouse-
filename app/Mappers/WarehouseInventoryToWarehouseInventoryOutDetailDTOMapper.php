<?php

namespace App\Mappers;

use App\Contracts\WarehouseInventoryToWarehouseInventoryOutDetailDTOMapperI;
use App\Enterprise_Layer\WarehouseInventory;
use App\Mappers\DTO\WarehouseInventoryOutDetailDTO;

class WarehouseInventoryToWarehouseInventoryOutDetailDTOMapper implements WarehouseInventoryToWarehouseInventoryOutDetailDTOMapperI
{
    public function convertToOutDetailDTO(WarehouseInventory $warehouseInventory): WarehouseInventoryOutDetailDTO
    {

        // $warehouseInventory->getManufacturingDate()->format('Y-m-d')
        $warehouseInventoryOutDetailDTO
        = new WarehouseInventoryOutDetailDTO(
            $warehouseInventory->getId(),
            $warehouseInventory->getWarehouseId(),
            $warehouseInventory->getRack(),
            $warehouseInventory->getLevel(),
            (string) $warehouseInventory->getProductId(),
            $warehouseInventory->getWarehouseName(),
            $warehouseInventory->getQuantity(),
            $warehouseInventory->getLotNumber(),
            $warehouseInventory->getExpirationDate()->format('Y-m-d'),
            $warehouseInventory->getModule(),
            $warehouseInventory->getBay(),
            $warehouseInventory->getPlatform(),
            $warehouseInventory->getManufacturingDate()?->format('Y-m-d') ?? null
        );

        $warehouseInventoryOutDetailDTO
            ->setActiveInventory(
                $warehouseInventory
                    ->isActiveInventory()
            );

        $warehouseInventoryOutDetailDTO
            ->setReason(
                $warehouseInventory
                    ->getReason()
            );

        $warehouseInventoryOutDetailDTO
            ->setTransferFolio(
                $warehouseInventory
                    ->getTransferFolio()
            );

        return $warehouseInventoryOutDetailDTO;
    }
}
