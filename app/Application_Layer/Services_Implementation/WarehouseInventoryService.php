<?php

namespace App\Application_Layer\Services_Implementation;

use App\Application_Layer\ResultPattern;
use App\Contracts\ProductServiceInterface;
use App\Contracts\WarehouseInventoryRepositoryInterface;
use App\Contracts\WarehouseInventoryRequestDTOToWarehouseInventoryMapperI;
use App\Contracts\WarehouseInventoryServiceI;
use App\Contracts\WarehouseStorageServiceInterface;
use App\Enterprise_Layer\WarehouseInventory;
use App\Mappers\DTO\Requests\WarehouseInventoryRequestDTO;

class WarehouseInventoryService implements WarehouseInventoryServiceI
{
    private WarehouseStorageServiceInterface $warehouseStorageService;

    private WarehouseInventoryRequestDTOToWarehouseInventoryMapperI $warehouseInventoryRequestDTOToWarehouseInventory;

    private ProductServiceInterface $productService;

    private WarehouseInventoryRepositoryInterface $warehouseInventoryRepository;

    private WarehouseInventory $warehouseInventory;

    public function __construct(
        WarehouseStorageServiceInterface $warehouseStorageService,
        WarehouseInventoryRequestDTOToWarehouseInventoryMapperI $warehouseInventoryRequestDTOToWarehouseInventory,
        ProductServiceInterface $productService,
        WarehouseInventoryRepositoryInterface $warehouseInventoryRepository
    ) {
        $this->warehouseStorageService = $warehouseStorageService;
        $this->warehouseInventoryRequestDTOToWarehouseInventory = $warehouseInventoryRequestDTOToWarehouseInventory;
        $this->productService = $productService;
        $this->warehouseInventoryRepository = $warehouseInventoryRepository;
    }

    public function saveInventory(
        WarehouseInventoryRequestDTO $warehouseInventoryDTO): ResultPattern
    {
        $warehouseName = $this->warehouseStorageService
            ->getWarehouseNameById(
                $warehouseInventoryDTO->getWarehouseId()
            );

        // if ($this->existProductInInventory(
        //     $warehouseInventoryDTO->getWarehouseId(),
        //     $warehouseInventoryDTO->getProductId()
        // )) {
        //     return ResultPattern::failure(
        //         "Error: producto ".$warehouseInventoryDTO->getProductId()
        //         ." ya existe en el inventario de  ".$warehouseName
        //     );
        // }

        $this->warehouseInventory = $this->warehouseInventoryRequestDTOToWarehouseInventory
            ->convertWarehouseInventoryRequestDTOToWarehouseInventory(
                $warehouseInventoryDTO
            );

        $productName = $this->productService
            ->getProductNameById(
                $this->warehouseInventory->getProductId()
            )->getValue();

        $this->warehouseInventory->setWarehouseName($productName);
        $ok = '';
        try {
            $ok .= 'Passed here!';
            $this->warehouseInventory = $this->warehouseInventoryRepository->save(
                $this->warehouseInventory
            );

            return ResultPattern::success(
                $this->warehouseInventory
            );

        } catch (\Throwable $th) {

            return ResultPattern::failure(
                $th->getMessage()
            );
        }
    }
}
