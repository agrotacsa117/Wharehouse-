<?php

namespace App\Application_Layer\Services_Implementation;

use App\Application_Layer\ResultPattern;
use App\Contracts\WarehouseInventoryQueryServiceI;
use App\Contracts\WarehouseInventoryRepositoryInterface;
use App\Contracts\WarehouseInventoryToWarehouseInventoryOutDetailDTOMapperI;

class WarehouseInventoryQueryService implements WarehouseInventoryQueryServiceI
{
    private WarehouseInventoryRepositoryInterface $warehouseInventoryRepository;
    private WarehouseInventoryToWarehouseInventoryOutDetailDTOMapperI $warehouseInventoryToWarehouseInventoryOutDetailDTOMapper;

    public function __construct(
        WarehouseInventoryRepositoryInterface $warehouseInventoryRepository,
        WarehouseInventoryToWarehouseInventoryOutDetailDTOMapperI $warehouseInventoryToWarehouseInventoryOutDetailDTOMapper
    ) {
        $this->warehouseInventoryRepository = $warehouseInventoryRepository;
        $this->warehouseInventoryToWarehouseInventoryOutDetailDTOMapper = $warehouseInventoryToWarehouseInventoryOutDetailDTOMapper;
    }

    public function getInventoryById(int $id): ResultPattern
    {
        $warehouseInventory = $this->warehouseInventoryRepository->findById($id);

        if (!$warehouseInventory) {
            return ResultPattern::failure(
                "¡No se encontro ningun inventario "
                ."registrado con este id ".$id
            );
        }

        $warehouseInventoryOutDetailDTO =
        $this->warehouseInventoryToWarehouseInventoryOutDetailDTOMapper
        ->convertToOutDetailDTO(
            $warehouseInventory
        );

        return ResultPattern::success($warehouseInventoryOutDetailDTO);
    }

     public function relocateInventory(
        int $id,
        string $rack,
        int $level
    ): ResultPattern{
        
    }
}
