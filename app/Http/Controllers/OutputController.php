<?php

namespace App\Http\Controllers;

use App\Contracts\WarehouseInventoryServiceInterface;
use App\Contracts\WarehouseStorageServiceInterface;
use Symfony\Component\HttpFoundation\Request;
use App\Mappers\DTO\RemoveWarehouseInventoryStockDTO;

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


        return view(
            'module.output.create',
            compact('warehousesWithLocationsDTO')
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
                    'destination_warehouse_id' => 'required|integer|different:warehouse_id',
                    'new_rack' => 'required|string|max:50',
                    'new_level' => 'required|integer|min:1',
                ]);

        $this->removeWarehouseInventoryStockDTO =
        $this->buildRemoveWarehouseInventoryStockDTO(
            $request,
            $userId
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

        $this->warehouseInventoryService
        ->processInventoryOutput(
            $this->removeWarehouseInventoryStockDTO
        );
    }

    private function buildRemoveWarehouseInventoryStockDTO(
        Request $request,
        int $userId
    ): RemoveWarehouseInventoryStockDTO {

        $dto = new RemoveWarehouseInventoryStockDTO(
            $request->warehouseInventoryId,
            $request->quantity,
            $request->reason
        );

        $dto->setUserId($userId);

        return $dto;
    }
}
