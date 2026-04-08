<?php

namespace App\Http\Controllers;
use App\Contracts\WarehouseInventoryServiceInterface;
use App\Contracts\WarehouseStorageServiceInterface;
use App\Contracts\StockInTransitServiceI;
use App\Contracts\WarehouseMovementsServiceI;
use Illuminate\Http\Request;
use App\Mappers\DTO\RemoveWarehouseInventoryStockDTO;
use App\Mappers\DTO\TransferRequestDTO;
use App\Mappers\DTO\RelocationRequestDTO;
use App\Enterprise_Layer\WarehouseInventoryMovements;

class OutputController extends Controller
{
    private WarehouseStorageServiceInterface $warehouseStorageService;
    private WarehouseInventoryServiceInterface $warehouseInventoryService;
    private StockInTransitServiceI $stockInTransitService;
    private WarehouseMovementsServiceI $warehouseMovementsService;

    public function __construct(
        WarehouseStorageServiceInterface $warehouseStorageService,
        WarehouseInventoryServiceInterface $warehouseInventoryService,
        StockInTransitServiceI $stockInTransitService,
        WarehouseMovementsServiceI $warehouseMovementsService
    ) {
        $this->warehouseStorageService = $warehouseStorageService;
        $this->warehouseInventoryService = $warehouseInventoryService;
        $this->stockInTransitService = $stockInTransitService;
        $this->warehouseMovementsService = $warehouseMovementsService;
    }

    public function processOutput(Request $request)
    {
        $movementType = $request->input('movement_type', 'OUT');
        $userId = auth()->id();

        switch ($movementType) {
            case WarehouseInventoryMovements::TYPE_OUT:
                return $this->processSimpleOutput($request);

            case WarehouseInventoryMovements::TYPE_SALE:
                return $this->processSale($request, $userId);

            case WarehouseInventoryMovements::TYPE_TRANSFER:
                return $this->processTransfer($request, $userId);

            case WarehouseInventoryMovements::TYPE_RELOCATION:
                return $this->processRelocation($request, $userId);

            default:
                return back()->withErrors('Tipo de movimiento no válido.');
        }
    }

    private function processSimpleOutput(Request $request)
    {
        $outputDTO = new RemoveWarehouseInventoryStockDTO(
            $request->warehouseInventoryId,
            $request->quantity,
            $request->reason
        );

        $result = $this->warehouseInventoryService->processInventoryOutput($outputDTO);

        if ($result->isFailure()) {
            return back()
                ->withErrors($result->getError())
                ->withInput();
        }

        return redirect()
            ->route('output.get')
            ->with('success', 'Salida registrada correctamente');
    }

    private function processSale(Request $request, int $userId)
    {
        $request->validate([
            'client_id' => 'required|integer',
            'invoice_sap' => 'nullable|string|max:50',
            'operation_date' => 'nullable|date',
        ]);

        $outputDTO = new RemoveWarehouseInventoryStockDTO(
            $request->warehouseInventoryId,
            $request->quantity,
            $request->reason ?? 'Venta'
        );

        $result = $this->warehouseInventoryService->processInventoryOutput($outputDTO);

        if ($result->isFailure()) {
            return back()
                ->withErrors($result->getError())
                ->withInput();
        }

        return redirect()
            ->route('output.get')
            ->with('success', "Venta registrada. Factura SAP: {$request->invoice_sap}");
    }

    private function processTransfer(Request $request, int $userId)
    {
        $request->validate([
            'destination_warehouse_id' => 'required|integer|different:warehouse_id',
        ]);

        $transferDTO = new TransferRequestDTO(
            $request->warehouseInventoryId,
            (int)$request->warehouse_id,
            (int)$request->destination_warehouse_id,
            (int)$request->quantity,
            $request->reason ?? 'Traslado entre sucursales',
            $request->operation_date
        );

        $result = $this->stockInTransitService->createTransfer($transferDTO, $userId);

        if ($result->isFailure()) {
            return back()
                ->withErrors($result->getError())
                ->withInput();
        }

        $data = $result->getValue();
        return redirect()
            ->route('output.get')
            ->with('success', "Traslado creado. Folio: {$data['folio']} - Pendiente de recepción");
    }

    private function processRelocation(Request $request, int $userId)
    {
        $request->validate([
            'destination_warehouse_id' => 'required|integer|different:warehouse_id',
            'new_rack' => 'required|string|max:50',
            'new_level' => 'required|integer|min:1',
        ]);

        $relocationDTO = new RelocationRequestDTO(
            $request->warehouseInventoryId,
            (int)$request->warehouse_id,
            (int)$request->destination_warehouse_id,
            (int)$request->quantity,
            $request->new_rack,
            (int)$request->new_level,
            $request->reason ?? 'Reubicación interna',
            $request->operation_date
        );

        $result = $this->warehouseInventoryService->relocateInventory($relocationDTO, $userId);

        if ($result->isFailure()) {
            return back()
                ->withErrors($result->getError())
                ->withInput();
        }

        return redirect()
            ->route('output.get')
            ->with('success', 'Reubicación registrada correctamente');
    }

    public function getView()
    {
        $warehousesId = $this->warehouseInventoryService->getWarehouseIdsWithInventory();
        //var_dump($this->warehouseStorageService->getWarehouseIdAndName());
        $warehousesWithLocationsDTO = $this->warehouseStorageService->getListAllWarehousesWithLocation($warehousesId);

        $allWarehouses = $this->warehouseStorageService->getListAllWarehousesWithLocation([]);

        $pendingReceptions = $this->stockInTransitService->getPendingTransfers(
            auth()->user()->warehouse_id ?? 0
        );

        $recentMovements = [];
        try {
            $recentMovements = $this->warehouseMovementsService->getRecentMovements(10);
        } catch (\Throwable $e) {
            // Temporal: ignora errores del historial
        }

        return view('module.output.create', compact(
            'warehousesWithLocationsDTO',
            'allWarehouses',
            'pendingReceptions',
            'recentMovements'
        ));
    }

    public function getInventory(int $id)
    {
        $inventory = $this->warehouseInventoryService->getWarehouseInventoryByWarehouseId($id);
        return response()->json($inventory);
    }

    public function confirmReception(Request $request, int $id)
    {
        $userId = auth()->id();
        $result = $this->stockInTransitService->confirmReception($id, $userId);

        if ($result->isFailure()) {
            return back()->withErrors($result->getError());
        }

        return back()->with('success', 'Recepción confirmada correctamente');
    }

    public function cancelTransfer(Request $request, int $id)
    {
        $result = $this->stockInTransitService->cancelTransfer($id);

        if ($result->isFailure()) {
            return back()->withErrors($result->getError());
        }

        return back()->with('success', 'Traslado cancelado. Stock restaurado.');
    }

    public function getWarehousesByLocation(int $locationId)
    {
        $warehouses = $this->warehouseStorageService->getWarehousesByLocationId($locationId);

        $data = array_map(function ($wh) {
            return [
                'id' => $wh->getId(),
                'name' => $wh->getWarehouseName(),
                'locationId' => $wh->getLocationId()
            ];
        }, $warehouses);

        return response()->json($data);
    }
}
