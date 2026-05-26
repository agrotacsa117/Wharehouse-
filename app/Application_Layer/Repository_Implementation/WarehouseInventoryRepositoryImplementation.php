<?php

namespace App\Application_Layer\Repository_Implementation;

use App\Contracts\WarehouseInventoryRepositoryInterface;
use App\Enterprise_Layer\WarehouseInventory;
use App\Models\WarehouseInventoryModel;
use App\Contracts\WarehouseInventoryEntityToWarehouseInventoryModelMapperI;
use App\Infrastructure\Exception\CouldNotPersistLocationException;
use App\Infrastructure\Exception\CouldNotDeleteLocationException;
use App\Contracts\WarehouseInventoryModelToWarehouseInventoryMapperI;
use App\Models\ProductModel;
use App\Infrastructure\Exception\InventoryNotFoundException;
use Illuminate\Support\Facades\DB;

class WarehouseInventoryRepositoryImplementation implements WarehouseInventoryRepositoryInterface {

    private WarehouseInventoryEntityToWarehouseInventoryModelMapperI $warehouseInventoryMapper;
    private WarehouseInventoryModelToWarehouseInventoryMapperI $warehouseInventoryModelToWarehouseInventory;

    public function __construct(
            WarehouseInventoryEntityToWarehouseInventoryModelMapperI $warehouseInventoryMapper,
            WarehouseInventoryModelToWarehouseInventoryMapperI $warehouseInventoryModelToWarehouseInventory
    ) {
        $this->warehouseInventoryMapper = $warehouseInventoryMapper;
        $this->warehouseInventoryModelToWarehouseInventory = $warehouseInventoryModelToWarehouseInventory;
    }

    public function findAll(): array {
        $warehouseInventory = WarehouseInventoryModel::with(
                        'warehouse'
                )->get()->toArray();

        return $warehouseInventory;
    }

    public function save(WarehouseInventory $warehouseInventory): WarehouseInventory {
        $warehouseInventoryModel = $this->warehouseInventoryMapper
                ->warehouseInventoryEntityToWarehouseInventoryModel(
                        $warehouseInventory
                );

        try {
            $warehouseInventoryModel->save();
            $warehouseInventory->setId($warehouseInventoryModel->id);
        } catch (\Throwable $th) {
            die($th->getMessage());
            throw new CouldNotPersistLocationException(
                            'Error saving inventory',
                            0,
                            $th
                    );
        }

        return $warehouseInventory;
    }

    public function update(WarehouseInventory $warehouseInventory): void {
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

    public function delete(int $id): void {
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

    public function existById(int $warehouseId, string $productId): bool {
        return WarehouseInventoryModel::where(
                        'product_id',
                        $productId
                )->where(
                        'warehouse_id',
                        $warehouseId
                )->exists();
    }

    public function countDistinctByWarehouseId(): array {
        $warehouseIds = WarehouseInventoryModel::select(
                        'warehouse_id'
                )->distinct()->get();

        $warehouseIds = $warehouseIds->toArray();
        return $warehouseIds;
    }

    public function findInventoryByWarehouseId(int $warehouseId): array {
        $inventory = WarehouseInventoryModel::where(
                        'warehouse_id',
                        $warehouseId
                )->where('quantity', '>', 0)->get();

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

    public function findQuantityByIdWithLock(
            int $warehouseInventoryId
    ): int {

        $warehouseInventoryModel = WarehouseInventoryModel::lockForUpdate()
                ->find($warehouseInventoryId);

        if (!$warehouseInventoryModel) {
            throw new InventoryNotFoundException(
                            "Inventario {$warehouseInventoryId} no encontrado."
                    );
        }

        return (int) $warehouseInventoryModel->quantity;
    }

    public function getInventoryStatsByState(): array {
        $query = WarehouseInventoryModel::selectRaw("
            " . $this->getQueryBase() . ",
            SUM(quantity) AS total_stock
        ");

        return $query->groupBy('state')
                        ->orderBy('state', 'DESC')
                        ->get()
                        ->toArray();
    }

    public function getInventoryStatsByStateAndWarehouse(): array {
        $query = WarehouseInventoryModel::selectRaw("
            i.warehouse_id,
            w.warehouses_name,
            " . $this->getQueryBase() . ",
            SUM(i.quantity) AS total_stock
        ")
                ->from('warehouse_inventory as i')
                ->join('warehouses as w', 'i.warehouse_id', '=', 'w.id');

        return $query->groupBy('i.warehouse_id', 'w.warehouses_name', 'state')
                        ->orderBy('state', 'DESC')
                        ->get()
                        ->toArray();
    }

    public function getInventoryByState(int $state): array {
        $query = WarehouseInventoryModel::selectRaw("
            i.*,
            w.warehouses_name,
            DATEDIFF(i.expiration_date, CURDATE()) as days_remaining,
            ROUND((
                DATEDIFF(CURDATE(), i.created_at) / 
                NULLIF(DATEDIFF(i.expiration_date, i.created_at), 0)
            ) * 100, 2) AS obsolescence
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

    public function findById(int $id): ?WarehouseInventory {
        $inventory = WarehouseInventoryModel::with('warehouse')
                ->where('id', $id)
                ->first();

        if (!$inventory) {
            return null;
        }

        $inventory = $this->warehouseInventoryModelToWarehouseInventory
                ->convertWarehouseInventoryModelToWarehouseInventory(
                        $inventory
                );

        return $inventory;
    }

    public function updateById(int $id, array $data): bool {
        return WarehouseInventoryModel::where('id', $id)->update($data) > 0;
    }

    public function transferInventory(
            int $inventoryId,
            string $fromWarehouseId,
            string $lotNumber,
            int $quantity
    ): array {
        $inventory = WarehouseInventoryModel::find($inventoryId);

        if (!$inventory) {
            return [
                'success' => false,
                'error' => 'Inventario no encontrado'];
        }



        if ($inventory->quantity < $quantity) {
            return ['success' => false, 'error' => 'Cantidad insuficiente en el inventario'];
        }


        $inventory->quantity = $inventory->quantity - $quantity;
        $inventory->save();

        // $newInventory = new WarehouseInventoryModel();
        // $newInventory->product_id = $inventory->product_id;
        // $newInventory->warehouse_name = $inventory->warehouse_name;
        // $newInventory->quantity = $quantity;
        // $newInventory->lot_number = $lotNumber;
        // $newInventory->reason = "Transferencia";
        // $newInventory->expiration_date = $inventory->expiration_date;
        // $newInventory->save();

        return [
            'success' => true,
            'remainingQuantity' => $inventory->quantity ?? 0
        ];
    }

    private function getQueryBase(): string {
        $query = "
            CASE 
                WHEN DATEDIFF(expiration_date, CURDATE()) < 90 THEN 3
                WHEN DATEDIFF(expiration_date, CURDATE()) BETWEEN 90 AND 120 THEN 2
                ELSE 1
            END AS state";

        return $query;
    }

    public function findExpired(): array {
        return WarehouseInventoryModel::selectRaw("
            wi.product_id,
            wi.warehouse_id,
            wi.warehouse_name AS product_name,
            w.warehouses_name AS warehouse_name,
            wi.quantity,
            wi.lot_number,
            wi.rack,
            wi._level,
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

    public function updateInventoryLocation(
            int $id,
            ?string $rack,
            ?int $level,
            ?int $module,
            ?int $bay,
            ?int $platform
    ): bool {
        return WarehouseInventoryModel::where('id', $id)
                        ->update([
                            'rack' => $rack,
                            '_level' => $level,
                            'module' => $module,
                            'bay' =>  $bay,
                            'platform' =>  $platform
                        ]) > 0;
    }

    public function findExpiredRanking(): array {
        // Compatible con MySQL 5.7 (sin window functions)
        // Obtener productos vencidos ordenados por bodega y cantidad desc
        $expired = \Illuminate\Support\Facades\DB::select("
            SELECT
                wi.id,
                wi.warehouse_id,
                wi.product_id,
                wi.rack,
                wi._level            AS level,
                wi.quantity,
                wi.lot_number,
                wi.warehouse_name    AS product_name,
                w.warehouses_name    AS warehouse_name,
                DATEDIFF(wi.expiration_date, CURDATE()) AS remaining_days
            FROM warehouse_inventory AS wi
            INNER JOIN warehouses AS w ON wi.warehouse_id = w.id
            WHERE DATEDIFF(wi.expiration_date, CURDATE()) < 0
            ORDER BY wi.warehouse_id ASC, wi.quantity DESC
        ");

        // Simular DENSE_RANK en PHP: agrupar por bodega y asignar rank
        $grouped = [];

        foreach ($expired as $row) {
            $row = (array) $row;
            $warehouseId = $row['warehouse_id'];

            if (!isset($grouped[$warehouseId])) {
                $grouped[$warehouseId] = [
                    'warehouse_id' => $warehouseId,
                    'warehouse_name' => $row['warehouse_name'],
                    'items' => [],
                ];
            }

            $rank = count(
                            $grouped[$warehouseId]['items']
                    ) + 1;

            if ($rank <= 3) {
                $grouped[$warehouseId]['items'][] = array_merge(
                        $row,
                        ['row_num' => $rank]
                );
            }
        }

        // Aplanar a lista de filas con row_num (mismo formato que antes)
        $results = [];
        foreach ($grouped as $warehouse) {
            foreach ($warehouse['items'] as $item) {
                $results[] = $item;
            }
        }

        return $results;
    }

    public function findSpecificInventory(
            int $warehouseId,
            ?int $rack,
            ?int $level,
            ?int $module,
            ?int $platform,
            ?int $bay,
            string $productId,
            string $lotNumber
    ): ?WarehouseInventory {

        // 1. Ejecutar la consulta con Eloquent
        $model = WarehouseInventoryModel::where('warehouse_id', $warehouseId)
                ->where('rack', $rack)
                ->where('_level', $level)
                ->where('module', $module)
                ->where('bay', $bay)
                ->where('platform', $platform)
                ->where('product_id', $productId)
                ->where('lot_number', $lotNumber)
                ->first();

        if (!$model) {
            return null;
        }

        return $this->warehouseInventoryModelToWarehouseInventory
                        ->convertWarehouseInventoryModelToWarehouseInventory(
                                $model
                        );
    }

    public function updateActiveInventory(
            int $inventoryId): void {
        WarehouseInventoryModel::where(
                'id',
                $inventoryId)->update([
            'active_inventory' => false
        ]);
    }

    public function findStockAndProductCountGroupedByWarehouse(): array {
        $inventorySummary = WarehouseInventoryModel::select(
            'warehouse_id')
                ->selectRaw(
                    'SUM(quantity) as stock')
                ->selectRaw(
                    'COUNT(DISTINCT product_id) as products')
                ->groupBy(
                    'warehouse_id')
                ->get();
        return $inventorySummary->toArray();
    }

    public function sumQuantityOfExpiredByWarehouse() : array {
        return WarehouseInventoryModel::select(
            'warehouse_id',
             DB::raw(
                'SUM(quantity) as total_quantity'))
        ->whereDate(
            'expiration_date',
             '<', now())
        ->groupBy(
            'warehouse_id')
        ->get()->keyBy('warehouse_id')->toArray();
    }

    public function getStockByWarehouseId(
        int $warehouseId) : array {
            return WarehouseInventoryModel::select('product_id', 'warehouse_name', DB::raw('SUM(quantity) as stock'))
            ->where('warehouse_id', $warehouseId)
            ->groupBy('product_id', 'warehouse_name')
            ->get()->toArray();
    }

    public function findByProductIdAndWarehouseId(
        int $warehouseId, 
        string $productId
    ) : array{
        return WarehouseInventoryModel::where('product_id', $productId)
            ->where('warehouse_id', $warehouseId)
            ->get()       // Trae la colección con todos los registros que coincidan
            ->toArray();
    }
}
