<?php

namespace App\Http\Controllers;

use App\Contracts\WarehouseInventoryServiceInterface;
use App\Contracts\WarehouseMovementsServiceI;
use App\Contracts\WarehouseStorageServiceInterface;
use App\Mappers\DTO\MovementsByPeriodFilterDTO;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

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
        $inventories = $this->warehouseInventoryService
            ->getAllWarehouseInventories();

        $movementsTotal = $this->warehouseMovementsService
            ->getTotalOfMovements();

        $movementsTotalIN = $this->warehouseMovementsService
            ->countByMovementType(
                'IN'
            );

        $movementsTotalOUT = $this->warehouseMovementsService
            ->countByMovementType(
                'OUT'
            );

        $movementsTotalTRANSFER = $this->warehouseMovementsService
            ->countByMovementType(
                'TRANSFER'
            );

        $movementsTotalADJUSTMENT = $this->warehouseMovementsService
            ->countByMovementType(
                'ADJUSTMENT'
            );

        $movementsTotalRELOCATION = $this->warehouseMovementsService
            ->countByMovementType(
                'RELOCATION'
            );

        $movementsTotalSALE = $this->warehouseMovementsService
            ->countByMovementType(
                'SALE'
            );

        $movements = $this->warehouseMovementsService->listAllMovements();

        $expiredProducts = $this->warehouseInventoryService
            ->getExpiredInventory();

        if ($request->ajax()) {
            return response()->json([
                'movements' => $movementsResult['data'],
                'pagination' => [
                    'total' => $movementsResult['total'],
                    'per_page' => $movementsResult['per_page'],
                    'current_page' => $movementsResult['current_page'],
                    'last_page' => $movementsResult['last_page'],
                ],
            ]);
        }

        $paginationInfo = [
            'total' => $movementsResult['total'],
            'per_page' => $movementsResult['per_page'],
            'current_page' => $movementsResult['current_page'],
            'last_page' => $movementsResult['last_page'],
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
                'movementsTotalRELOCATION',
                'movementsTotalSALE',
                'warehouses',
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
            'warehouse_id' => 'nullable|integer',
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
            $movementsByPeriodFilterDTO
        );

        $movements = $result->getValue();

        return response()->json([
            'data' => $movements,
        ]);
    }

    public function expirationReport(Request $request)
    {
        $expiredInventory = $this->warehouseInventoryService->getExpiredInventory();
        // $ranking = $this->warehouseInventoryService->getExpiredInventoryRanking();

        return response()->json([
            'data' => $expiredInventory,
        ]);
    }

    public function reportByCaducidad(Request $request)
    {
        $expiredInventory = $this->warehouseInventoryService->getExpiredInventory();
        $ranking = $this->warehouseInventoryService->getExpiredInventoryRanking();

        return response()->json([
            'data' => $expiredInventory,
            'ranking' => $ranking,
        ]);
    }

    public function reverseMovement(
        string $folio,
        string $reason
    ): JsonResponse {

        return response()->json([
            'success' => true,
            'message' => 'Productos obtenidos exitosamente',
        ], 200);
    }
}
