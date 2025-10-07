<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Warehouse;
use App\Mappers\DTO\WarehouseDTO;
use App\Contracts\WarehouseStorageServiceInterface;

class WarehouseRegistrationController extends Controller
{
    public function index()
    {
        return view('module.warehouses.create');
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
