<?php

namespace App\Application_Layer\Repository_Implementation;

use App\Contracts\WarehouseMovementsRepositoryI;
use App\Models\WarehouseInventoryMovementsModel;
use App\Enterprise_Layer\WarehouseInventoryMovements;
use App\Contracts\WarehouseInventoryMovementsEntityToModelMapperI;
use App\Infrastructure\Exception\CouldNotPersistLocationException;
use App\Contracts\WarehouseInventoryMovementModelMapperI;

class WarehouseMovementsRepository implements WarehouseMovementsRepositoryI
{
    private WarehouseInventoryMovementsEntityToModelMapperI $warehouseInventoryMovementsEntityToModelMapperI;
    private WarehouseInventoryMovementModelMapperI $warehouseInventoryMovementModelMapper;

    public function __construct(
        WarehouseInventoryMovementsEntityToModelMapperI $warehouseInventoryMovementsEntityToModelMapperI,
        WarehouseInventoryMovementModelMapperI $warehouseInventoryMovementModelMapper
    ) {
        $this->warehouseInventoryMovementsEntityToModelMapperI = $warehouseInventoryMovementsEntityToModelMapperI;
        $this->warehouseInventoryMovementModelMapper = $warehouseInventoryMovementModelMapper;
    }

    public function findAll(): array
    {
        $movements = WarehouseInventoryMovementsModel::with(
            ['inventory.warehouse',
            'user']
        )->orderBy('created_at', 'asc')
        ->get();

        $movements = $movements->toArray();

        return $movements;
    }

    public function findAllPaginated(int $perPage = 15): array
    {
        $paginator = WarehouseInventoryMovementsModel::with(
            ['inventory.warehouse',
            'user']
        )
        ->orderBy('created_at', 'asc')
        ->paginate($perPage);

        $items = collect($paginator->items())->map(function ($item) {
            return $item->toArray();
        })->toArray();

        return [
            'data' => $items,
            'total' => $paginator->total(),
            'per_page' => $paginator->perPage(),
            'current_page' => $paginator->currentPage(),
            'last_page' => $paginator->lastPage()
        ];
    }

    public function findByInventoryId(int $inventoryId): array
    {
        return [];
    }

    public function save(WarehouseInventoryMovements $warehouseMovements): void
    {
        $warehouseInventoryMovementsModel =
        $this->warehouseInventoryMovementsEntityToModelMapperI
        ->mapToInventoryMovementsModel($warehouseMovements);

        try {
            $warehouseInventoryMovementsModel->save();
        } catch (\Throwable $th) {
            throw new CouldNotPersistLocationException(
                $th->getMessage(),
                0,
                $th
            );
        }
    }

    public function count(): int
    {
        return WarehouseInventoryMovementsModel::count();
    }

    public function countByMovementType(string $movementType): int
    {
        return WarehouseInventoryMovementsModel::where(
            'movement_type',
            $movementType
        )->count();

    }

    public function findByDateRange(
        string $startDate,
        string $endDate,
        ?int $warehouseId = null,
        ?string $movementType = null
    ): array {

        $filteredMovements =  WarehouseInventoryMovementsModel::query()
        ->with(['inventory.warehouse', 'user'])
        ->whereBetween('created_at', [
            $startDate,
            $endDate
        ]);

        if ($warehouseId) {
            $filteredMovements->where(
                'warehouse_id',
                $warehouseId
            );
        }

        if ($movementType) {
            $filteredMovements->where(
                'movement_type',
                $movementType
            );
        }

        $filteredMovements = $filteredMovements->get();
        $filteredMovements = $filteredMovements->toArray();

        return $filteredMovements;
    }

    public function getMovementCountsByType(
        string $startDate,
        string $endDate
    ): array {

        $movementCounts = WarehouseInventoryMovementsModel::whereBetween(
            'created_at',
            [$startDate,
            $endDate]
        )->groupBy('movement_type')->selectRaw(
            'movement_type, COUNT(movement_type) as count'
        )->pluck(
            'count',
            'movement_type'
        );

        $movementCounts = $movementCounts->toArray();

        return $movementCounts;
    }
}
