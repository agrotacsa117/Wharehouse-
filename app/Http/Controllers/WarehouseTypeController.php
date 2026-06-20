<?php

namespace App\Http\Controllers;

use App\Contracts\WarehouseTypeServiceInterface;
use App\Mappers\DTO\Requests\WarehouseTypeRequestDTO;
use Illuminate\Http\Request;

class WarehouseTypeController extends Controller
{
    private WarehouseTypeServiceInterface $warehouseTypeServiceInterface;

    public function __construct(
        WarehouseTypeServiceInterface $warehouseTypeServiceInterface
    ) {
        $this->warehouseTypeServiceInterface = $warehouseTypeServiceInterface;
    }

    public function getView()
    {
        return view('module.warehouse-types.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate(
            ['categoryWarehouse' => 'required|string|min:3|max:100']
        );

        $warehouseTypeRequestDTO = new WarehouseTypeRequestDTO(
            $validated['categoryWarehouse']
        );

        $result = $this->warehouseTypeServiceInterface
            ->createWarehouseType(
                $warehouseTypeRequestDTO
            );

        if ($result->isFailure()) {
            return back()
                ->withErrors($result->getError())
                ->withInput();
        }

        return redirect()
            ->route('warehouse-type.get')
            ->with('success', 'Tipo de almacén creado correctamente');
    }
}
