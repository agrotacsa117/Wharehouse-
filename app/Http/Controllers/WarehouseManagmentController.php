<?php

namespace App\Http\Controllers;

use App\Contracts\LocationServiceInterface;
use App\Contracts\WarehouseStorageServiceInterface;
use App\Contracts\WarehouseTypeServiceInterface;
use App\Enterprise_Layer\Warehouse;
use App\Enterprise_Layer\WarehouseType;
use Illuminate\Http\Request;

class WarehouseManagmentController extends Controller
{
    private WarehouseStorageServiceInterface $warehouseStorageService;
    private LocationServiceInterface $locationService;
    private WarehouseTypeServiceInterface $warehouseTypeService;

    public function __construct(
        WarehouseStorageServiceInterface $warehouseStorageService,
        LocationServiceInterface $locationService,
        WarehouseTypeServiceInterface $warehouseTypeService
    ) {
        $this->warehouseStorageService = $warehouseStorageService;
        $this->locationService = $locationService;
        $this->warehouseTypeService = $warehouseTypeService;
    }

    public function getView()
    {
        $warehouses = $this->warehouseStorageService->listAllWarehouses();

        $totalWarehouses = $this->warehouseStorageService
        ->getTotalWarehouse();

        $totalLocation = $this->locationService->getTotalLocation();

        $allLocation = $this->locationService
        ->listHeadquartersNames();

        $allWarehouseType = $this->warehouseTypeService
        ->listWarehouseTypesNames();

        return view(
            'module.warehouse-management.create',
            compact(
                'warehouses',
                'totalWarehouses',
                'totalLocation',
                'allLocation',
                'allWarehouseType'
            )
        );
    }

    public function update(Request $request)
    {
        
    }
}
