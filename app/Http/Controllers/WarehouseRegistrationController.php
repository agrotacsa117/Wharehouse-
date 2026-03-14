<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Warehouse;
use App\Mappers\DTO\WarehouseDTO;
use App\Contracts\WarehouseStorageServiceInterface;
use App\Contracts\LocationServiceInterface;
use App\Contracts\WarehouseTypeServiceInterface;
use App\Enterprise_Layer\WarehouseType;
use App\Infrastructure\StoreWarehouseRequest;

class WarehouseRegistrationController extends Controller
{
    private LocationServiceInterface $locationService;
    private WarehouseTypeServiceInterface $warehouseTypeService;
    private WarehouseStorageServiceInterface $warehouseStorageService;

    public function __construct(
        LocationServiceInterface $locationService,
        WarehouseTypeServiceInterface $warehouseTypeService,
        WarehouseStorageServiceInterface $warehouseStorageService
    ) {
        $this->locationService = $locationService;
        $this->warehouseTypeService = $warehouseTypeService;
        $this->warehouseStorageService = $warehouseStorageService;
    }

    public function index()
    {

        $headquarters = $this->locationService->listHeadquartersNames();

        $warehouseTypes = $this->warehouseTypeService->listWarehouseTypesNames();

        return view('module.warehouses.create', [
            'headquarters' => $headquarters,
            'warehouseTypes' =>   $warehouseTypes
        ]);
    }

    public function registerWarehouse(StoreWarehouseRequest $request)
    {

        $warehouseDTO = new WarehouseDTO(
            $request->warehouses_key,
            $request->warehouses_name,
            $request->warehouse_manager,
            $request->phone_number,
            $request->email,
            (int)$request->warehouse_type_id,
            (int)$request->location_id,
            auth()->id()
        );

        $result = $this->warehouseStorageService->registerWarehouse(
            $warehouseDTO
        );

        if ($result->isFailure()) {
            return redirect()->route('warehouses.create')
    ->with(
        'success',
        $result->getError()
    );
        }

        return redirect()->route('warehouses.create')
    ->with(
        'success',
        "¡Almacén registrado con éxito!"
    );

    }
}
