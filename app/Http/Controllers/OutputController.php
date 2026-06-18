<?php

namespace App\Http\Controllers;

use App\Contracts\WarehouseInventoryServiceInterface;
use App\Contracts\WarehouseStorageServiceInterface;
use App\Mappers\DTO\RemoveWarehouseInventoryStockDTO;
use App\Mappers\DTO\TransferInventoryDTO;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Request;

class OutputController extends Controller
{
    private WarehouseStorageServiceInterface $warehouseStorageService;

    private WarehouseInventoryServiceInterface $warehouseInventoryService;

    private RemoveWarehouseInventoryStockDTO $removeWarehouseInventoryStockDTO;

    public function __construct(
        WarehouseStorageServiceInterface $warehouseStorageService,
        WarehouseInventoryServiceInterface $warehouseInventoryService
    ) {
        $this->warehouseStorageService = $warehouseStorageService;
        $this->warehouseInventoryService = $warehouseInventoryService;
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
                    $request,
                    $userId,
                    $movementType
                );

            case 'LOCATION_UPDATE':
                return $this->processLocationUpdate(
                    $request,
                    $userId,
                    $movementType
                );
            default:
                return back()->withErrors('Tipo de movimiento no válido.');
        }

        $result = $this->warehouseInventoryService
            ->processInventoryOutput(
                $this->removeWarehouseInventoryStockDTO
            );

        if ($result->isFailure()) {
            return back()
                ->withErrors($result->getError())
                ->withInput();
        }

        return redirect()
            ->route('output.get')
            ->with('success', 'Salida registrada correctamente');
    }

    public function getView()
    {
        // DB::enableQueryLog();
        $warehousesId = $this->warehouseInventoryService
            ->getWarehouseIdsWithInventory();

        $warehousesWithLocationsDTO = $this->warehouseStorageService->getListAllWarehousesWithLocation(
            $warehousesId
        );

        // ✅ AGREGAR LISTA DE TODOS LOS ALMACENES PARA EL SELECT DE TRANSFER
        $allWarehouses = $this->warehouseStorageService->listAllWarehouses();

        // dd(DB::getQueryLog());
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

        $movementType = $request->movement_type;
        $request->validate([
            'new_rack' => 'nullable|integer|max:50',
            'new_level' => 'nullable|integer|min:1',
            'quantity' => 'required|integer|min:1',
            'module' => 'nullable|integer|min:1',
            'bay' => 'nullable|integer|min:1',
            'platform' => 'nullable|integer|min:1',
        ]);

        $this->removeWarehouseInventoryStockDTO =
        $this->buildRemoveWarehouseInventoryStockDTO(
            $request,
            $userId,
            $movementType
        );

        $this->removeWarehouseInventoryStockDTO
            ->setQuantity(
                $request->quantity
            );

        $this->removeWarehouseInventoryStockDTO
            ->setRack(
                $request->new_rack
            );

        $this->removeWarehouseInventoryStockDTO
            ->setLevel(
                (int) $request->new_level
            );

        $this->removeWarehouseInventoryStockDTO
            ->setOperationDate(
                $request->operation_date
            );

        $this->removeWarehouseInventoryStockDTO
            ->setWarehouseId(
                $request->destination_warehouse_id
            );

        $this->removeWarehouseInventoryStockDTO
            ->setBay(
                $request->bay
            );

        $this->removeWarehouseInventoryStockDTO
            ->setModule(
                $request->module
            );

        $this->removeWarehouseInventoryStockDTO
            ->setPlatform(
                $request->platform
            );

        $result = $this->warehouseInventoryService
            ->processInventoryOutput(
                $this->removeWarehouseInventoryStockDTO
            );

        if ($result->isFailure()) {
            return back()
                ->withErrors($result->getError())
                ->withInput();
        }

        return redirect()
            ->route('output.get')
            ->with('success', 'Reubicación registrada correctamente');

    }

    private function buildRemoveWarehouseInventoryStockDTO(
        Request $request,
        int $userId,
        string $movementType
    ): RemoveWarehouseInventoryStockDTO {

        $quantity = (
            $movementType === 'RELOCATION'
        || $movementType === 'LOCATION_UPDATE') ? 0 : (int) $request->quantity;
        $dto = new RemoveWarehouseInventoryStockDTO(
            $request->warehouseInventoryId,
            $quantity,
            $request->reason
        );

        $dto->setMovementType($movementType);
        $dto->setUserId($userId);

        return $dto;
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

        $this->removeWarehouseInventoryStockDTO = $this->buildRemoveWarehouseInventoryStockDTO(
            $request,
            $userId,
            'SALE'
        );

        // Llenar datos específicos de venta
        if ($request->invoice_sap) {
            $this->removeWarehouseInventoryStockDTO->setInvoiceId((int) $request->invoice_sap);
        }

        $this->removeWarehouseInventoryStockDTO->setOperationDate($request->operation_date ?? now()->format('Y-m-d'));

        $result = $this->warehouseInventoryService->processInventoryOutput(
            $this->removeWarehouseInventoryStockDTO
        );

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

        $this->removeWarehouseInventoryStockDTO = $this->buildRemoveWarehouseInventoryStockDTO(
            $request,
            $userId,
            'OUT'
        );

        $this->removeWarehouseInventoryStockDTO->setOperationDate($request->operation_date ?? now()->format('Y-m-d'));

        $result = $this->warehouseInventoryService->processInventoryOutput(
            $this->removeWarehouseInventoryStockDTO
        );

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
    // TRANSFER (Traslado) - PLACEHOLDER
    // ═══════════════════════════════════════════════════════════
    private function processTransfer(
        Request $request,
        int $userId,
        string $movementType
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

        // Crear DTO de transferencia
        $transferDTO = new TransferInventoryDTO(
            (int) $request->warehouseInventoryId,
            $inventory->getWarehouseId(), // Bodega origen
            $request->destination_warehouse, // Bodega destino
            '',
            0,
            $inventory->getLotNumber(),
            (int) $request->quantity,
            $request->reason,
            (int) $request->folio_transfer
        );

        $this->removeWarehouseInventoryStockDTO
        = $this->buildRemoveWarehouseInventoryStockDTO(
            $request,
            $userId,
            $movementType
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

    public function processLocationUpdate(
        Request $request,
        int $userId,
        string $movementType
    ) {
        $request->validate([
            'new_rack_r' => 'nullable|integer|max:50',
            'new_level_r' => 'nullable|integer|min:1',
            'new_module_r' => 'nullable|integer|min:1',
            'new_bay_r' => 'nullable|integer|min:1',
            'new_platform_r' => 'nullable|integer|min:1',
            'reason' => 'nullable|string|max:255',
        ]);

        $this->removeWarehouseInventoryStockDTO = $this
            ->buildRemoveWarehouseInventoryStockDTO(
                $request,
                $userId,
                $movementType
            );

        $this->removeWarehouseInventoryStockDTO
            ->setRack($request->new_rack_r);

        $this->removeWarehouseInventoryStockDTO
            ->setLevel(
                $request->new_level_r
            );

        $this->removeWarehouseInventoryStockDTO
            ->setModule(
                $request->new_module_r
            );

        $this->removeWarehouseInventoryStockDTO
            ->setBay(
                $request->new_bay_r
            );

        $this->removeWarehouseInventoryStockDTO
            ->setPlatform(
                $request->new_platform_r
            );

        $this->removeWarehouseInventoryStockDTO
            ->setReason(
                $request->reason
            );

        $result = $this->warehouseInventoryService
            ->processInventoryOutput(
                $this->removeWarehouseInventoryStockDTO
            );

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
}
