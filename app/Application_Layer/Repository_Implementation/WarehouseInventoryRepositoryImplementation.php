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

    public function getInventoryStatsByStateAndWarehouse(): array
    {
        $query = WarehouseInventoryModel::selectRaw("
            w.warehouses_name,
            CASE 
                WHEN DATEDIFF(expiration_date, CURDATE()) < 90 THEN 3
                WHEN DATEDIFF(expiration_date, CURDATE()) BETWEEN 90 AND 120 THEN 2
                ELSE 1
            END AS state,
            SUM(i.quantity) AS total_stock
        ")
        ->from('warehouse_inventory as i')
        ->join('warehouses as w', 'i.warehouse_id', '=', 'w.id');

        return $query->groupBy('i.warehouse_id', 'state')
            ->orderBy('state', 'DESC')
            ->get()
            ->toArray();
    }

    public function getInventoryByState(int $state): array
    {
        $query = WarehouseInventoryModel::selectRaw("
            i.*,
            w.warehouses_name,
            DATEDIFF(i.expiration_date, CURDATE()) as days_remaining
        ")
        ->from('warehouse_inventory as i')
        ->join('warehouses as w', 'i.warehouse_id', '=', 'w.id');

        switch ($state) {
            case 3:
                $query->whereRaw('DATEDIFF(i.expiration_date, CURDATE()) < 90');
                break;
            case 2:
                $query->whereRaw('DATEDIFF(i.expiration_date, CURDATE()) BETWEEN 90 AND 120');
                break;
            case 1:
                $query->whereRaw('DATEDIFF(i.expiration_date, CURDATE()) > 120');
                break;
        }

        return $query->orderBy('i.expiration_date', 'ASC')
            ->get()
            ->toArray();
    }

    public function findByProductId(string $productId): array
    {
        return WarehouseInventoryModel::with('warehouse')
            ->where('product_id', $productId)
            ->get()
            ->toArray();
    }

    public function findByWarehouse(int $warehouseId, ?string $rack = null, ?int $level = null): array
    {
        $query = WarehouseInventoryModel::with('warehouse')
            ->where('warehouse_id', $warehouseId);

        if ($rack !== null && $rack !== '') {
            $query->where('rack', $rack);
        }

        if ($level !== null) {
            $query->where('_level', $level);
        }

        return $query->get()->toArray();
    }

    public function findExpired(): array
    {
        return WarehouseInventoryModel::selectRaw("
            wi.product_id,
            w.warehouses_name AS warehouse_name,
            wi.quantity,
            wi.lot_number,
            wi.expiration_date,
            ABS(DATEDIFF(wi.expiration_date, CURDATE())) AS expired_days
        ")
        ->from('warehouse_inventory AS wi')
        ->join('warehouses AS w', 'wi.warehouse_id', '=', 'w.id')
        ->whereRaw('DATEDIFF(wi.expiration_date, CURDATE()) < 0')
        ->orderBy('wi.expiration_date', 'asc')
        ->get()
        ->toArray();
    }

    public function findById(int $id): ?array
    {
        $inventory = WarehouseInventoryModel::with('warehouse')
            ->where('id', $id)
            ->first();

        if (!$inventory) {
            return null;
        }

        return $inventory->toArray();
    }

    public function updateById(int $id, array $data): bool
    {
        return WarehouseInventoryModel::where('id', $id)->update($data) > 0;
    }

    public function transferInventory(
        int $inventoryId,
        int $fromWarehouseId,
        int $toWarehouseId,
        string $rack,
        int $level,
        string $lotNumber,
        int $quantity
    ): array {
        $inventory = WarehouseInventoryModel::find($inventoryId);

        if (!$inventory) {
            return ['success' => false, 'error' => 'Inventario no encontrado'];
        }

        if ($inventory->warehouse_id !== $fromWarehouseId) {
            return ['success' => false, 'error' => 'El inventario no pertenece al almacén de origen'];
        }

        if ($inventory->quantity < $quantity) {
            return ['success' => false, 'error' => 'Cantidad insuficiente en el inventario'];
        }

        if ($inventory->quantity == $quantity) {
            $inventory->delete();
        } else {
            $inventory->quantity = $inventory->quantity - $quantity;
            $inventory->save();
        }

        $newInventory = new WarehouseInventoryModel();
        $newInventory->warehouse_id = $toWarehouseId;
        $newInventory->product_id = $inventory->product_id;
        $newInventory->rack = $rack;
        $newInventory->_level = $level;
        $newInventory->warehouse_name = $inventory->warehouse_name;
        $newInventory->quantity = $quantity;
        $newInventory->lot_number = $lotNumber;
        $newInventory->reason = "Transferencia";
        $newInventory->expiration_date = $inventory->expiration_date;
        $newInventory->save();

            return [
            'success' => true,
            'newInventoryId' => $newInventory->id,
            'remainingQuantity' => $inventory->quantity ?? 0
        ];
    }

    public function findExpiredRanking(): array
    {
        $results = WarehouseInventoryModel::selectRaw("
            wi.id,
            wi.warehouse_id,
            wi.product_id,
            wi.rack,
            wi._level,
            wi.quantity,
            wi.lot_number,
            wi.warehouse_name AS product_name,
            w.warehouses_name AS warehouse_name,
            DATEDIFF(wi.expiration_date, CURDATE()) AS remaining_days,
            DENSE_RANK() OVER (PARTITION BY wi.warehouse_id ORDER BY wi.quantity DESC) AS row_num
        ")
        ->from('warehouse_inventory AS wi')
        ->join('warehouses AS w', 'wi.warehouse_id', '=', 'w.id')
        ->whereRaw('DATEDIFF(wi.expiration_date, CURDATE()) < 0')
        ->orderByRaw('wi.warehouse_id, row_num')
        ->get()
        ->toArray();

        return $results;
    }
}
