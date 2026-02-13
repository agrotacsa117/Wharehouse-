<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Warehouse;
use App\Mappers\DTO\WarehouseDTO;
use App\Contracts\WarehouseStorageServiceInterface;
use App\Contracts\LocationServiceInterface;

class WarehouseRegistrationController extends Controller
{
    private LocationServiceInterface $locationService;

    public function __construct(LocationServiceInterface $locationService)
    {
        $this->locationService = $locationService;
    }

    public function index()
    {
        $headquarters = $this->locationService->listHeadquartersNames();

        return view('module.warehouses.create', [
            'headquarters' => $headquarters
        ]);
    }

    public function registerWarehouse(Request $request)
    {

        $warehouseDTO = new WarehouseDTO(
            $request->key,
            $request->warehouse_name,
            $request->name_person_responsible,
            $request->phone_number,
            $request->email,
            $request->type_warehouse,
        );


        return redirect()->route('warehouses.create')
            ->with(
                'success',
                '¡Almacén registrado exitosamente!'
            );
    }
}
