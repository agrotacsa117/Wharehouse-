<?php

namespace App\Application_Layer\Repository_Implementation;

use App\Contracts\WarehouseInventoryRepositoryInterface;
use App\Enterprise_Layer\WarehouseInventory;
use App\Models\WarehouseInventoryModel;
use App\Contracts\WarehouseInventoryEntityToWarehouseInventoryModelMapperI;
use App\Infrastructure\Exception\CouldNotPersistLocationException;
use App\Infrastructure\Exception\CouldNotDeleteLocationException;
use App\Contracts\WarehouseInventoryModelToWarehouseInventoryMapperI;

class WarehouseInventoryRepositoryImplementation implements WarehouseInventoryRepositoryInterface
{
    private WarehouseInventoryEntityToWarehouseInventoryModelMapperI $warehouseInventoryMapper;


    public function __construct(
        WarehouseInventoryEntityToWarehouseInventoryModelMapperI $warehouseInventoryMapper
    ) {
        $this->warehouseInventoryMapper = $warehouseInventoryMapper;
    }

    public function findAll(): array
    {
        $warehouseInventory = WarehouseInventoryModel::with(
            'warehouse'
        )->get()->toArray();

        return $warehouseInventory;
    }

    public function save(WarehouseInventory $warehouseInventory): WarehouseInventory
    {
        $warehouseInventoryModel = $this->warehouseInventoryMapper
        ->warehouseInventoryEntityToWarehouseInventoryModel(
            $warehouseInventory
        );


        try {
            $warehouseInventoryModel->save();
            $warehouseInventory->setId($warehouseInventoryModel->id);
        } catch (\Throwable $th) {
            throw new CouldNotPersistLocationException(
                'Error saving inventory',
                0,
                $th
            );
        }

        return  $warehouseInventory;
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

    public function existById(int $warehouseId, string $productId): bool
    {
        return WarehouseInventoryModel::where(
            'product_id',
            $productId
        )->where(
            'warehouse_id',
            $warehouseId
        )->exists();
    }

    public function countDistinctByWarehouseId(): array
    {
        $warehouseIds = WarehouseInventoryModel::select(
            'warehouse_id'
        )->distinct()->get();

        $warehouseIds = $warehouseIds->toArray();
        return $warehouseIds;
    }

    public function findInventoryByWarehouseId(int $warehouseId): array
    {
        $inventory = WarehouseInventoryModel::where(
            'warehouse_id',
            $warehouseId
        )->get();

        $inventory = $inventory->toArray();

        return $inventory;
    }

    public function updateQuantity(
        int $warehouseInventoryId,
        int $quantity
    ): bool {

        return WarehouseInventoryModel::where(
            'id',
            $warehouseInventoryId
        )->update(
            ['quantity' => $quantity]
        ) > 0;
    }


    public function findQuantityById(
        int $warehouseInventoryId
    ): int {
        return WarehouseInventoryModel::where(
            'id',
            $warehouseInventoryId
        )->value(
            'quantity'
        );
    }

    public function getInventoryStatsByState(): array
    {
        $query = WarehouseInventoryModel::selectRaw("
            CASE 
                WHEN DATEDIFF(expiration_date, CURDATE()) < 90 THEN 3
                WHEN DATEDIFF(expiration_date, CURDATE()) BETWEEN 90 AND 120 THEN 2
                ELSE 1
            END AS state,
            SUM(quantity) AS total_stock
        ");

        return $query->groupBy('state')
            ->orderBy('state', 'DESC')
            ->get()
            ->toArray();
    }
}
