<?php

namespace App\Http\Controllers;

use App\Contracts\LocationServiceInterface;
use App\Contracts\WarehouseStorageServiceInterface;
use App\Contracts\WarehouseTypeServiceInterface;
use App\Mappers\DTO\WarehouseDTO;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

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

        $warehousesJson = array_map(function ($w) {
            return [
                'id' => $w->getId(),
                'key' => $w->getWarehouseKey(),
                'name' => $w->getWarehouseName(),
                'createdAt' => $w->getCreatedAt()->format('d-m-Y'),
                'updatedAt' => $w->getUpdatedAt()->format('d-m-Y'),
                'responsable' => $w->getWarehouseManager(),
                'userName' => $w->getUserName(),
                'phone' => $w->getPhoneNumber(),
                'location' => $w->getHeadquartersName() ?? '',
                'email' => $w->getEmail(),
                'type' => $w->getCategoryWarehouse() ?? '',
                'typeId' => $w->getWarehouseTypeId(),
                'locationId' => $w->getLocationId(),
                'active' => true,
            ];
        }, $warehouses);

        $allLocationJson = array_map(function ($loc) {
            return ['id' => $loc->getId(), 'name' => $loc->getHeadquartersName()];
        }, $allLocation);

        $allWarehouseTypeJson = array_map(function ($wt) {
            return ['id' => $wt->getId(), 'name' => $wt->getCategoryWarehouse()];
        }, $allWarehouseType);

        return view(
            'module.warehouse-management.create',
            compact(
                'warehouses',
                'totalWarehouses',
                'totalLocation',
                'allLocation',
                'allWarehouseType',
                'warehousesJson',
                'allLocationJson',
                'allWarehouseTypeJson'
            )
        );
    }

    public function update(Request $request, int $id)
    {
        Log::info('Update warehouse request:', [
            'id' => $id,
            'request' => $request->all(),
        ]);

        $request->validate([
            'key' => 'required|string|max:50',
            'name' => 'required|string|max:255',
            'responsable' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:10',
            'email' => 'nullable|email|max:255',
            'location' => 'required|integer',
            'type' => 'required|integer',
        ]);

        Log::info('DTO being created:', [
            'key' => $request->key,
            'name' => $request->name,
            'responsable' => $request->responsable,
            'type' => (int) $request->type,
            'location' => (int) $request->location,
            'dto_id' => $id,
        ]);

        $dto = new WarehouseDTO(
            $request->key,
            $request->name,
            $request->responsable ?? '',
            $request->phone ?? '',
            $request->email ?? '',
            (int) $request->type,
            (int) $request->location,
            auth()->id(),
            $id
        );

        $result = $this->warehouseStorageService->updateWarehouse($dto);

        return response()->json([
            'success' => true,
            'message' => $result->isFailure(),
        ]);

        Log::info('Update result:', [
            'isFailure' => $result->isFailure(),
            'getValue' => $result->getValue(),
            'getError' => $result->getError(),
        ]);

        if ($result->isFailure()) {
            return response()->json([
                'success' => false,
                'message' => $result->getError(),
            ], 422);
        }

        return response()->json([
            'success' => true,
            'message' => 'Almacén actualizado correctamente.',
        ]);
    }
}
