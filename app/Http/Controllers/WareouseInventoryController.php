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
        'rack'           => 'nullable|string',
        'level'          => 'nullable|integer|min:1',
        'quantity'       => 'required|integer|min:1',
        'expirationDate' => 'required|date',
        'reason'         => 'required|string',
        'loteNumber'     => 'required|string',
        'module' => 'required|integer|min:1',
        'bay' => 'required|integer|min:1',
        'platform' => 'required|integer|min:1',
        'transfer_folio' => 'integer|min:1'
        ]);

        
        $dto = new WarehouseInventoryRequestDTO(
            $request->productId,
            (int) $request->warehouseId,
            $request->rack !== null ? (int) $request->rack : null,
            $request->level !== null ? (int) $request->level : null,
            (int) $request->quantity,
            new \DateTime($request->expirationDate),
            $request->reason,
            $request->loteNumber,
            $request->transfer_folio
        );

        $dto->setModule(
            $request->module
        );

        $dto->setBay($request->bay);
        $dto->setPlatform(
            $request->platform
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
