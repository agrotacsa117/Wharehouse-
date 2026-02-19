<?php

namespace App\Contracts;

use App\Contracts\WarehouseInventoryRepositoryInterface;
use App\Enterprise_Layer\WarehouseInventory;
use App\Models\WarehouseInventoryModel;
use App\Contracts\WarehouseInventoryEntityToWarehouseInventoryModelMapperI;
use App\Infrastructure\Exception\CouldNotPersistLocationException;
use App\Infrastructure\Exception\CouldNotDeleteLocationException;

class WarehouseInventoryRepositoryImplementation implements WarehouseInventoryRepositoryInterface
{
    private WarehouseInventoryEntityToWarehouseInventoryModelMapperI $WarehouseInventoryMapper;

    public function __construct(
        WarehouseInventoryEntityToWarehouseInventoryModelMapperI $warehouseInventoryMapper
    ) {
        $this->WarehouseInventoryMapper = $warehouseInventoryMapper;
    }

    public function findAll(): array
    {
        $warehouseInventory = WarehouseInventoryModel::all();
        $inventories = array();
        $index = 0;

        foreach ($warehouseInventory as $inventory) {
            $inventories[$index] = $inventory;
            $index++;
        }

        return $inventories;
    }

    public function save(WarehouseInventory $warehouseInventory): void
    {
        $warehouseInventoryModel = $this->WarehouseInventoryMapper
        ->warehouseInventoryEntityToWarehouseInventoryModel(
            $warehouseInventory
        );


        try {
            $warehouseInventoryModel->save();
        } catch (\Throwable $th) {
            throw new CouldNotPersistLocationException(
                'Error saving inventory',
                0,
                $th
            );
        }
    }

    public function update(WarehouseInventory $warehouseInventory): void
    {
        try {
            $this->save($warehouseInventory);
        } catch (\Throwable $th) {
            throw new CouldNotPersistLocationException(
                'Error updating inventory',
                0,
                $th
            );
        }
    }

    public function delete(int $id): void
    {
        $locationModel = WarehouseInventoryModel::find(
            $id
        );

        try {
            $locationModel->delete();
        } catch (\Throwable $th) {
            throw new CouldNotDeleteLocationException(
                'Error deleting location',
                0,
                $th
            );
        }
    }

}
