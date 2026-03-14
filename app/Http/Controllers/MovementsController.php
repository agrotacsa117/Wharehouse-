<?php

namespace App\Http\Controllers;

use App\Contracts\WarehouseInventoryServiceInterface;
use App\Contracts\WarehouseMovementsServiceI;
use App\Enterprise_Layer\WarehouseInventory;
use App\Contracts\WarehouseStorageServiceInterface;
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

    public function getView()
    {
        $movements =  $this->warehouseMovementsService->listAllMovements();

        $warehouses = $this->warehouseStorageService->getWarehouseIdAndName();
        $inventories  =  $this->warehouseInventoryService
         ->getAllWarehouseInventories();

        $movementsTotal = $this->warehouseMovementsService
        ->getTotalOfMovements();

        $movementsTotalIN = $this->warehouseMovementsService
        ->countByMovementType(
            "IN"
        );

        //enum('IN','','ADJUSTMENT')
        $movementsTotalOUT = $this->warehouseMovementsService
        ->countByMovementType(
            "OUT"
        );
        return view(
            'module.warehouse_movements.create',
            compact(
                'inventories',
                'movements',
                'movementsTotal',
                'movementsTotalIN',
                'movementsTotalOUT',
                'warehouses'
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
}
