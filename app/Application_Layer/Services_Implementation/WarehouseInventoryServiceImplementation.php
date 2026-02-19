<?php

namespace App\Application_Layer\Services_Implementation;

use App\Contracts\WarehouseInventoryServiceInterface;
use App\Mappers\DTO\Requests\WarehouseInventoryRequestDTO;
use App\Contracts\WarehouseInventoryRepositoryInterface;
use App\Contracts\WarehouseInventoryRequestDTOToWarehouseInventoryMapperI;

class WarehouseInventoryServiceImplementation implements WarehouseInventoryServiceInterface
{
    private WarehouseInventoryRepositoryInterface $warehouseInventoryRepository;
    private WarehouseInventoryRequestDTOToWarehouseInventoryMapperI $warehouseInventoryRequestDTOToWarehouseInventory;

    public function __construct(
        WarehouseInventoryRepositoryInterface $warehouseInventoryRepository,
        WarehouseInventoryRequestDTOToWarehouseInventoryMapperI $warehouseInventoryRequestDTOToWarehouseInventory
    ) {
        $this->warehouseInventoryRepository = $warehouseInventoryRepository;
        $this->warehouseInventoryRequestDTOToWarehouseInventory = $warehouseInventoryRequestDTOToWarehouseInventory;
    }

    public function getAllWarehouseInventories(): array
    {
        return [];
    }

    public function create(
        WarehouseInventoryRequestDTO $warehouseInventoryDTO
    ): void {
        $warehouseInventory = $this->warehouseInventoryRequestDTOToWarehouseInventory
        ->convertWarehouseInventoryRequestDTOToWarehouseInventory(
            $warehouseInventoryDTO
        );

        try {
            $this->warehouseInventoryRepository->save(
                $warehouseInventory
            );
        } catch (\Throwable $th) {
            
        }
    }

    public function update(
        WarehouseInventoryRequestDTO $warehouseInventory
    ): void {

    }

    public function delete(int $id): void
    {

    }
}
