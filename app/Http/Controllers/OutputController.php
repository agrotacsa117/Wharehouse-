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
        

        $outputDTO = new RemoveWarehouseInventoryStockDTO(
            $request->warehouseInventoryId,
            $request->quantity,
            $request->reason
        );

        $result =  $this->warehouseInventoryService
        ->processInventoryOutput(
            $outputDTO
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



}
