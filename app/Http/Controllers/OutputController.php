<?php

namespace App\Http\Controllers;

use App\Contracts\WarehouseInventoryServiceInterface;
use App\Contracts\WarehouseStorageServiceInterface;
use Symfony\Component\HttpFoundation\Request;
use App\Mappers\DTO\RemoveWarehouseInventoryStockDTO;
use App\Mappers\DTO\TransferInventoryDTO;

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
        $movementType =  $request->movement_type;
        $userId = auth()->id();

        switch ($movementType) {
            case "RELOCATION":
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

            default:
                return back()->withErrors('Tipo de movimiento no válido.');
        }


        $result =  $this->warehouseInventoryService
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

        $warehousesId = $this->warehouseInventoryService
        ->getWarehouseIdsWithInventory();


        $warehousesWithLocationsDTO =  $this->warehouseStorageService->getListAllWarehousesWithLocation(
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

        $movementType = $request->movement_type;
        $request->validate([
                    'new_rack' => 'required|string|max:50',
                    'new_level' => 'required|integer|min:1',
                    'quantity' => 'required|integer|min:1'
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
            (int)$request->new_level
        );

        $this->removeWarehouseInventoryStockDTO
        ->setOperationDate(
            $request->operation_date
        );

        $this->removeWarehouseInventoryStockDTO
        ->setWarehouseId(
            $request->destination_warehouse_id
        );

        $this->warehouseInventoryService
        ->processInventoryOutput(
            $this->removeWarehouseInventoryStockDTO
        );
    }

    private function buildRemoveWarehouseInventoryStockDTO(
        Request $request,
        int $userId,
        string $movementType
    ): RemoveWarehouseInventoryStockDTO {

        $quantity = ($movementType === 'RELOCATION') ? 0 : (int) $request->quantity;
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
            'client_id' => 'required|integer',
            'invoice_sap' => 'nullable|integer',
            'reason' => 'required|string|max:255'
        ]);

        $this->removeWarehouseInventoryStockDTO = $this->buildRemoveWarehouseInventoryStockDTO(
            $request,
            $userId,
            'SALE'
        );

        // Llenar datos específicos de venta
        $this->removeWarehouseInventoryStockDTO->setClientId((int) $request->client_id);

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
            'reason' => 'required|string|max:255'
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
            'destination_warehouse_id' => 'required|integer',
            'transfer_rack' => 'required|string|max:50',
            'transfer_level' => 'required|integer|min:1',
            'reason' => 'required|string|max:255'
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
            (int) $request->destination_warehouse_id, // Bodega destino
            $request->transfer_rack,
            (int) $request->transfer_level,
            $inventory->getLotNumber(),
            (int) $request->quantity,
            $request->reason
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

}
