<?php

namespace App\Http\Controllers;

use App\Contracts\CartOutputServiceInterface;
use App\Contracts\WarehouseInventoryServiceInterface;
use App\Contracts\WarehouseOutputDtoMapperI;
use App\Contracts\WarehouseStorageServiceInterface;
use Illuminate\Http\Request;

class OutputController extends Controller
{
    private WarehouseStorageServiceInterface $warehouseStorageService;

    private WarehouseInventoryServiceInterface $warehouseInventoryService;

    private WarehouseOutputDtoMapperI $outputDtoMapper;

    private CartOutputServiceInterface $cartOutputService;

    public function __construct(
        WarehouseStorageServiceInterface $warehouseStorageService,
        WarehouseInventoryServiceInterface $warehouseInventoryService,
        WarehouseOutputDtoMapperI $outputDtoMapper,
        CartOutputServiceInterface $cartOutputService
    ) {
        $this->warehouseStorageService = $warehouseStorageService;
        $this->warehouseInventoryService = $warehouseInventoryService;
        $this->outputDtoMapper = $outputDtoMapper;
        $this->cartOutputService = $cartOutputService;
    }

    public function processOutput(Request $request)
    {
        $movementType = $request->movement_type;
        $userId = auth()->id();

        switch ($movementType) {
            case 'RELOCATION':
                return $this->processRelocation(
                    $request,
                    $userId
                );

            case 'SALE':
                return $this->processSale(
                    $request,
                    $userId
                );

            case 'OUT':
                return $this->processSimpleOutput(
                    $request,
                    $userId
                );

            case 'TRANSFER':
                return $this->processTransfer(
                    $request
                );

            case 'LOCATION_UPDATE':
                return $this->processLocationUpdate(
                    $request,
                    $userId
                );
            default:
                return back()->withErrors('Tipo de movimiento no válido.');
        }
    }

    public function getView()
    {
        $warehousesId = $this->warehouseInventoryService
            ->getWarehouseIdsWithInventory();

        $warehousesWithLocationsDTO = $this->warehouseStorageService->getListAllWarehousesWithLocation(
            $warehousesId
        );

        // ✅ AGREGAR LISTA DE TODOS LOS ALMACENES PARA EL SELECT DE TRANSFER
        $allWarehouses = $this->warehouseStorageService->listAllWarehouses();

        return view(
            'module.output.create',
            compact('warehousesWithLocationsDTO', 'allWarehouses')
        );
    }

    public function getInventory(int $id)
    {

        $inventory = $this->warehouseInventoryService
            ->getWarehouseInventoryByWarehouseId(
                $id
            );

        return response()->json($inventory);
    }

    public function getWarehousesByLocation(int $locationId)
    {
        $coLocatedWarehouses = $this->warehouseStorageService->getWarehousesByLocationId(
            $locationId
        );

        return response()->json($coLocatedWarehouses);
    }

    private function processRelocation(
        Request $request,
        int $userId
    ) {

        $request->validate([
            'new_rack' => 'nullable|integer|max:50',
            'new_level' => 'nullable|integer|min:1',
            'quantity' => 'required|integer|min:1',
            'module' => 'nullable|integer|min:1',
            'bay' => 'nullable|integer|min:1',
            'platform' => 'nullable|integer|min:1',
            'new_manufacturing_date' => 'nullable|date',
        ]);

        $dto = $this->outputDtoMapper->toRelocationDto($request->all(), $userId);

        $result = $this->warehouseInventoryService
            ->processInventoryOutput($dto);

        if ($result->isFailure()) {
            return back()
                ->withErrors($result->getError())
                ->withInput();
        }

        return redirect()
            ->route('output.get')
            ->with('success', 'Reubicación registrada correctamente');

    }

    // ═══════════════════════════════════════════════════════════
    // SALE (Venta)
    // ═══════════════════════════════════════════════════════════
    private function processSale(Request $request, int $userId)
    {
        $request->validate([
            'warehouseInventoryId' => 'required|integer',
            'quantity' => 'required|integer|min:1',
            'invoice_sap' => 'nullable|integer',
            'reason' => 'required|string|max:255',
        ]);

        $dto = $this->outputDtoMapper->toSaleDto($request->all(), $userId);

        $result = $this->warehouseInventoryService->processInventoryOutput($dto);

        if ($result->isFailure()) {
            return back()
                ->withErrors($result->getError())
                ->withInput();
        }

        return redirect()
            ->route('output.get')
            ->with('success', 'Venta registrada correctamente');
    }

    // ═══════════════════════════════════════════════════════════
    // OUT (Salida Simple)
    // ═══════════════════════════════════════════════════════════
    private function processSimpleOutput(Request $request, int $userId)
    {
        $request->validate([
            'warehouseInventoryId' => 'required|integer',
            'quantity' => 'required|integer|min:1',
            'reason' => 'required|string|max:255',
        ]);

        $dto = $this->outputDtoMapper->toSimpleOutputDto($request->all(), $userId);

        $result = $this->warehouseInventoryService->processInventoryOutput($dto);

        if ($result->isFailure()) {
            return back()
                ->withErrors($result->getError())
                ->withInput();
        }

        return redirect()
            ->route('output.get')
            ->with('success', 'Salida registrada correctamente');
    }

    // ═══════════════════════════════════════════════════════════
    // TRANSFER (Traslado)
    // ═══════════════════════════════════════════════════════════
    private function processTransfer(
        Request $request
    ) {
        $request->validate([
            'warehouseInventoryId' => 'required|integer',
            'quantity' => 'required|integer|min:1',
            'reason' => 'required|string|max:255',
            'destination_warehouse' => 'required|string|max:255',
            'folio_transfer' => 'required|integer|min:1',
        ]);

        // Obtener inventario actual
        $inventoryResult = $this->warehouseInventoryService->getInventoryById(
            (int) $request->warehouseInventoryId
        );

        if ($inventoryResult->isFailure()) {
            return back()->withErrors($inventoryResult->getError())->withInput();
        }

        $inventory = $inventoryResult->getValue();

        // Validar que no sea el mismo almacén
        if ($inventory->getWarehouseId() == $request->destination_warehouse_id) {
            return back()->withErrors('No se puede transferir al mismo almacén')->withInput();
        }

        $transferDTO = $this->outputDtoMapper->toTransferDto(
            (int) $request->warehouseInventoryId,
            $inventory,
            $request->all()
        );

        // Ejecutar transferencia
        $result = $this->warehouseInventoryService
            ->transferInventory(
                $transferDTO
            );

        if ($result->isFailure()) {
            return back()->withErrors(
                $result->getError()
            )->withInput();
        }

        return redirect()->route('output.get')->with('success', 'Traslado registrado correctamente');
    }

    private function processLocationUpdate(
        Request $request,
        int $userId
    ) {
        $request->validate([
            'quantity' => 'required|integer|min:1',
            'new_rack_r' => 'nullable|integer|max:50',
            'new_level_r' => 'nullable|integer|min:1',
            'new_module_r' => 'nullable|integer|min:1',
            'new_bay_r' => 'nullable|integer|min:1',
            'new_platform_r' => 'nullable|integer|min:1',
            'reason' => 'nullable|string|max:255',
        ]);

        $dto = $this->outputDtoMapper->toLocationUpdateDto($request->all(), $userId);

        $result = $this->warehouseInventoryService
            ->processInventoryOutput($dto);

        if ($result->isFailure()) {
            return back()->withErrors(
                $result->getError()
            )->withInput();
        }

        return redirect()
            ->route('output.get')
            ->with(
                'success',
                '¡Reubicación en la misma
                 bodega registrada correctamente!');
    }

    // ═══════════════════════════════════════════════════════════
    // CART BATCH (mejor esfuerzo — cada ítem se procesa
    // independientemente, sin transacción envolvente)
    // ═══════════════════════════════════════════════════════════
    public function processCart(Request $request)
    {
        $request->validate([
            'movement_type' => 'required|string',
            'reason' => 'nullable|string|max:255',
            'operation_date' => 'nullable|date',
            'invoice_sap' => 'nullable|integer',
            'destination_warehouse' => 'nullable|string|max:255',
            'destination_warehouse_id' => 'nullable|integer',
            'folio_transfer' => 'nullable|integer|min:1',
            'items' => 'required|array|min:1',
            'items.*.warehouseInventoryId' => 'required|integer',
            'items.*.quantity' => 'nullable|integer|min:1',
            'items.*.new_rack_r' => 'nullable|integer',
            'items.*.new_level_r' => 'nullable|integer',
            'items.*.new_module_r' => 'nullable|integer',
            'items.*.new_bay_r' => 'nullable|integer',
            'items.*.new_platform_r' => 'nullable|integer',
            'items.*.new_rack' => 'nullable|integer|max:50',
            'items.*.new_level' => 'nullable|integer|min:1',
            'items.*.module' => 'nullable|integer|min:1',
            'items.*.bay' => 'nullable|integer|min:1',
            'items.*.platform' => 'nullable|integer|min:1',
        ]);

        $movementType = $request->movement_type;
        $userId = auth()->id();
        $shared = $request->except('items');

        $results = $this->cartOutputService->processBatch(
            $movementType,
            $shared,
            $request->items,
            $userId
        );

        return response()->json(['results' => $results]);
    }
}
