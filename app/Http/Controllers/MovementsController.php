<?php

namespace App\Http\Controllers;

use App\Contracts\WarehouseInventoryServiceInterface;
use App\Contracts\WarehouseMovementsServiceI;
use App\Enterprise_Layer\WarehouseInventory;
use App\Contracts\WarehouseStorageServiceInterface;
use App\Models\WarehouseInventoryModel;
use Illuminate\Http\Request;
use App\Mappers\DTO\MovementsByPeriodFilterDTO;

class MovementsController extends Controller
{
    private WarehouseInventoryServiceInterface $warehouseInventoryService;
    private WarehouseMovementsServiceI $warehouseMovementsService;
    private WarehouseStorageServiceInterface $warehouseStorageService;

    public function __construct(
        WarehouseInventoryServiceInterface $warehouseInventoryService,
        WarehouseMovementsServiceI $warehouseMovementsService,
        WarehouseStorageServiceInterface $warehouseStorageService
    ) {
        $this->warehouseInventoryService = $warehouseInventoryService;
        $this->warehouseMovementsService = $warehouseMovementsService;
        $this->warehouseStorageService = $warehouseStorageService;
    }

    public function getView(Request $request)
    {
        $page = (int) $request->get('page', 1);
        $perPage = 15;

        
        $movementsResult = $this->warehouseMovementsService->listAllMovementsPaginated($page, $perPage);

        $warehouses = $this->warehouseStorageService->getWarehouseIdAndName();
        
        $productosInventario = WarehouseInventoryModel::select('product_id', 'warehouse_name')
            ->distinct()
            ->orderBy('product_id')
            ->get()
            ->toArray();
            
        $inventories  =  $this->warehouseInventoryService
         ->getAllWarehouseInventories();

        $movementsTotal = $this->warehouseMovementsService
        ->getTotalOfMovements();

        $movementsTotalIN = $this->warehouseMovementsService
        ->countByMovementType(
            "IN"
        );

        $movementsTotalOUT = $this->warehouseMovementsService
        ->countByMovementType(
            "OUT"
        );

        $movementsTotalTRANSFER = $this->warehouseMovementsService
        ->countByMovementType(
            "TRANSFER"
        );

        $movementsTotalADJUSTMENT = $this->warehouseMovementsService
        ->countByMovementType(
            "ADJUSTMENT"
        );

        $movements = $this->warehouseMovementsService->listAllMovements();

        if ($request->ajax()) {
            return response()->json([
                'movements' => $movementsResult['data'],
                'pagination' => [
                    'total' => $movementsResult['total'],
                    'per_page' => $movementsResult['per_page'],
                    'current_page' => $movementsResult['current_page'],
                    'last_page' => $movementsResult['last_page']
                ]
            ]);
        }

        $paginationInfo = [
            'total' => $movementsResult['total'],
            'per_page' => $movementsResult['per_page'],
            'current_page' => $movementsResult['current_page'],
            'last_page' => $movementsResult['last_page']
        ];

        return view(
            'module.warehouse_movements.create',
            compact(
                'inventories',
                'movements',
                'movementsTotal',
                'movementsTotalIN',
                'movementsTotalOUT',
                'movementsTotalTRANSFER',
                'movementsTotalADJUSTMENT',
                'warehouses',
                'productosInventario',
                'paginationInfo'
            )
        );
    }

    public function reportByPeriod(Request $request)
    {
        $data = $request->validate([
        'fecha_inicio' => 'required|date',
        'fecha_fin' => 'required|date',
        'tipo_movimiento' => 'nullable|string',
        'warehouse_id' => 'nullable|integer'
        ]);

        
        $startDate = $data['fecha_inicio'];
        $endDate = $data['fecha_fin'];
        
        $movementType = $data['tipo_movimiento'] ?? null;
        $warehouseId = $data['warehouse_id'] ?? null;

        $movementsByPeriodFilterDTO = new MovementsByPeriodFilterDTO(
            $startDate,
            $endDate,
            $movementType,
            $warehouseId
        );

        $result = $this->warehouseMovementsService->filterTransactionsByDateRange(
            $movementsByPeriodFilterDTO);

        $movements = $result->getValue();

        return response()->json([
            'data' => $movements
        ]);
    }

    public function reportByProduct(Request $request)
    {
        $productId = $request->input('product_id');
        $warehouseId = $request->input('warehouse_id') ? (int)$request->input('warehouse_id') : null;

        $inventory = $this->warehouseInventoryService->getInventoryByProductId($productId);

        if ($warehouseId !== null) {
            $inventory = array_filter($inventory, function($item) use ($warehouseId) {
                return (int)$item['warehouse_id'] === $warehouseId;
            });
            $inventory = array_values($inventory);
        }

        $movements = $this->warehouseMovementsService->getMovementsByProductId($productId);

        $formattedInventory = array_map(function($item) {
            return [
                'type' => 'inventory',
                'warehouse' => $item['warehouse']['warehouses_name'] ?? '',
                'rack' => $item['rack'] ?? '',
                'level' => $item['_level'] ?? '',
                'quantity' => $item['quantity'] ?? 0,
                'lote' => $item['lot_number'] ?? '',
                'expiration' => $item['expiration_date'] ?? '',
            ];
        }, $inventory);

        $formattedMovements = array_map(function($item) {
            return [
                'type' => 'movement',
                'folio' => $item['folio'] ?? '',
                'warehouse' => $item['warehouses_name'] ?? '',
                'movement_type' => $item['movement_type'] ?? '',
                'quantity' => $item['quantity'] ?? 0,
                'reason' => $item['reason'] ?? '',
                'created_at' => $item['created_at'] ?? '',
            ];
        }, $movements);

        $result = array_merge($formattedMovements, $formattedInventory);

        usort($result, function($a, $b) {
            $dateA = $a['created_at'] ?? $a['expiration'] ?? '';
            $dateB = $b['created_at'] ?? $b['expiration'] ?? '';
            return strtotime($dateB) - strtotime($dateA);
        });

        return response()->json([
            'data' => $result
        ]);
    }

    public function getRacksAndLevels(Request $request)
    {
        $warehouseId = $request->input('warehouse_id');

        $query = WarehouseInventoryModel::select('rack', '_level');

        if ($warehouseId) {
            $query->where('warehouse_id', (int)$warehouseId);
        }

        $items = $query->distinct()->orderBy('rack')->get()->toArray();

        $racks = array_unique(array_column($items, 'rack'));
        $levels = array_unique(array_column($items, '_level'));
        sort($levels);

        return response()->json([
            'racks' => array_values($racks),
            'levels' => array_values($levels)
        ]);
    }

    public function reportByWarehouse(Request $request)
    {
        $warehouseId = (int)$request->input('warehouse_id');
        $rack = $request->input('rack') ?: null;
        $level = $request->input('level') ? (int)$request->input('level') : null;

        $inventory = $this->warehouseInventoryService->getInventoryByWarehouse($warehouseId, $rack, $level);

        $formattedInventory = array_map(function($item) {
            return [
                'product' => $item['product_id'] ?? '',
                'warehouse' => $item['warehouse']['warehouses_name'] ?? '',
                'rack' => $item['rack'] ?? '',
                'level' => $item['_level'] ?? '',
                'quantity' => $item['quantity'] ?? 0,
                'lote' => $item['lot_number'] ?? '',
                'expiration' => $item['expiration_date'] ?? '',
            ];
        }, $inventory);

        return response()->json([
            'data' => $formattedInventory
        ]);
    }

    public function reportByCaducidad(Request $request)
    {
        $expiredInventory = $this->warehouseInventoryService->getExpiredInventory();
        $ranking = $this->warehouseInventoryService->getExpiredInventoryRanking();

        return response()->json([
            'data' => $expiredInventory,
            'ranking' => $ranking
        ]);
    }
}
