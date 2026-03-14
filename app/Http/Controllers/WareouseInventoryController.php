<?php

namespace App\Http\Controllers;

use App\Contracts\ProductServiceInterface;
use App\Contracts\WarehouseInventoryServiceInterface;
use App\Contracts\WarehouseStorageServiceInterface;
use App\Enterprise_Layer\Warehouse;
use App\Enterprise_Layer\WarehouseInventory;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Mappers\DTO\Requests\WarehouseInventoryRequestDTO;

class WareouseInventoryController extends Controller
{
    private ProductServiceInterface $productService;
    private WarehouseStorageServiceInterface $warehouseStorageService;
    private WarehouseInventoryServiceInterface $warehouseInventoryService;

    public function __construct(
        ProductServiceInterface $productService,
        WarehouseStorageServiceInterface $warehouseStorageService,
        WarehouseInventoryServiceInterface $warehouseInventoryService
    ) {
        $this->productService = $productService;
        $this->warehouseStorageService = $warehouseStorageService;
        $this->warehouseInventoryService = $warehouseInventoryService;
    }

    public function getView()
    {
        $products = $this->productService->listAllProducts();
        $warehouses = $this->warehouseStorageService
        ->getWarehouseIdAndName();
        return view(
            'module.operations.create',
            compact(
                'products',
                'warehouses'
            )
        );
    }

    public function store(Request $request)
    {
        $request->validate([
        'productId'      => 'required|string',
        'warehouseId'    => 'required|integer',
        'rack'           => 'required|string',
        'level'          => 'required|integer|min:1',
        'quantity'       => 'required|integer|min:1',
        'expirationDate' => 'required|date',
        'reason'         => 'required|string',
        'loteNumber'     => 'required|string',
        ]);

        $dto = new WarehouseInventoryRequestDTO(
            $request->productId,
            (int) $request->warehouseId,
            $request->rack,
            (int) $request->level,
            (int) $request->quantity,
            new \DateTime($request->expirationDate),
            $request->reason,
            $request->loteNumber
        );

        $result = $this->warehouseInventoryService->create($dto);

        if ($result->isFailure()) {
            return back()
                ->withErrors($result->getError())
                ->withInput();
        }

        return redirect()
        ->route('operation.get')
        ->with('success', 'Inventario registrado correctamente');
    }
}
