<?php

namespace App\Http\Controllers;

use App\Contracts\WarehouseInventoryServiceInterface;
use App\Mappers\DTO\UpdateInventoryDTO;
use App\Mappers\DTO\TransferInventoryDTO;
use App\Models\WarehouseModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Contracts\ProductServiceInterface;
use App\Contracts\WarehouseStorageServiceInterface;

class InventoryManagementController extends Controller
{
    private WarehouseInventoryServiceInterface $warehouseInventoryService;
    private ProductServiceInterface $productService;
    private WarehouseStorageServiceInterface $warehouseStorageService;

    public function __construct(
        WarehouseInventoryServiceInterface $warehouseInventoryService,
        ProductServiceInterface $productService,
        WarehouseStorageServiceInterface $warehouseStorageService
    ) {
        $this->warehouseInventoryService = $warehouseInventoryService;
        $this->productService = $productService;
        $this->warehouseStorageService = $warehouseStorageService;
    }

    public function index()
    {
        $titulo = "Gestión de Inventario";
        $almacenes = WarehouseModel::select('id', 'warehouses_name')->get();
        $inventory = $this->warehouseInventoryService->getAllWarehouseInventories();
        $products = $this->productService->listAllProducts();
        //$inventory = $this->warehouseInventoryService->getAllInventoryForManagement();
        $warehouses = $this->warehouseStorageService
                ->getWarehouseIdAndName();

        return view('module.inventory-management.index', compact(
            'titulo',
            'almacenes',
            'inventory',
            'products',
            'warehouses'
        ));
    }

    public function getById($id)
    {
        $inventory = $this->warehouseInventoryService->getInventoryById($id);

        if (!$inventory) {
            return response()->json(['error' => 'Inventario no encontrado'], 404);
        }

        return response()->json($inventory);
    }

    public function update(Request $request)
    {
        $request->validate([
            'id' => 'required|integer',
            'editProductId' => 'required|integer',
            'warehouse' => 'required|integer',
            'rack' => 'nullable|string|max:50',
            'level' => 'nullable|integer|min:1',
            'lot_number' => 'required|string|max:100',
            'quantity' => 'required|integer|min:0',
            'expiration_date' => 'required|date',
            'reason' => 'required|string|max:255'
        ]);

        

        
        //die("The product id is: ".$request->editProductId);
        $dto = new UpdateInventoryDTO(
            (int)$request->id,
            $request->rack,
            (int)$request->level,
            $request->lot_number,
            (int)$request->quantity,
            $request->expiration_date,
            $request->reason
        );

        $result = $this->warehouseInventoryService->updateInventory($dto);

        if ($result->isFailure()) {
            return response()->json([
                'success' => false,
                'message' => $result->getError()
            ], 400);
        }

        return response()->json([
            'success' => true,
            'message' => 'Inventario actualizado correctamente'
        ]);
    }

    public function transfer(Request $request)
    {
        $request->validate([
            'inventory_id' => 'required|integer',
            'from_warehouse_id' => 'required|integer',
            'to_warehouse_id' => 'required|integer',
            'rack' => 'required|string|max:50',
            'level' => 'required|integer|min:1',
            'lot_number' => 'required|string|max:100',
            'quantity' => 'required|integer|min:1',
            'reason' => 'required|string|max:255'
        ]);

        $dto = new TransferInventoryDTO(
            (int)$request->inventory_id,
            (int)$request->from_warehouse_id,
            (int)$request->to_warehouse_id,
            $request->rack,
            (int)$request->level,
            $request->lot_number,
            (int)$request->quantity,
            $request->reason
        );

        return response()->json([
           'success' => true,
           'message' =>  $dto
        ]);

        $result = $this->warehouseInventoryService->transferInventory($dto);

        if ($result->isFailure()) {
            return response()->json([
                'success' => false,
                'message' => $result->getError()
            ], 400);
        }

        return response()->json([
            'success' => true,
            'message' => 'Transferencia realizada correctamente'
        ]);
    }
}
