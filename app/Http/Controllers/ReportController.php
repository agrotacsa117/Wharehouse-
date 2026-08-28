<?php

namespace App\Http\Controllers;

use App\Contracts\WarehouseInventoryServiceInterface;
use App\Contracts\WarehouseMovementsServiceI;
use App\Contracts\WarehouseSalesServiceI;
use App\Contracts\WarehouseStorageServiceInterface;
use App\Exports\PhysicalCountExport;
use App\Exports\ProductLotsExport;
use App\Mappers\DTO\InventoryStatsByStateDTO;
use App\Mappers\DTO\MovementsByPeriodFilterDTO;
use App\Models\Producto;
use App\Models\Rack;
use App\Models\WarehouseModel;
use Carbon\Carbon;
use Excel;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use PDF;

class ReportController extends Controller
{
    private WarehouseInventoryServiceInterface $warehouseInventoryService;

    private WarehouseMovementsServiceI $warehouseMovementsService;

    private WarehouseSalesServiceI $warehouseSalesService;

    private WarehouseStorageServiceInterface $warehouseStorageService;

    public function __construct(
        WarehouseInventoryServiceInterface $warehouseInventoryService,
        WarehouseMovementsServiceI $warehouseMovementsService,
        WarehouseSalesServiceI $warehouseSalesService,
        WarehouseStorageServiceInterface $warehouseStorageService
    ) {
        $this->warehouseInventoryService = $warehouseInventoryService;
        $this->warehouseMovementsService = $warehouseMovementsService;
        $this->warehouseSalesService = $warehouseSalesService;
        $this->warehouseStorageService = $warehouseStorageService;
    }

    public function index()
    {
        $this->warehouseInventoryService
            ->getStockByWarehouse(25);
        $stockSummary = $this->warehouseInventoryService
            ->getStockSummaryPerWarehouse();

        $warehousesId = $this->warehouseInventoryService
            ->getWarehouseIdsWithInventory();

        $warehousesWithLocationsDTO = $this->warehouseStorageService->getListAllWarehousesWithLocation(
            $warehousesId
        );

        $reportDTO = $this->warehouseSalesService
            ->getSalesReport();

        $titulo = 'Reportes';
        $hoy = Carbon::now();
        $sieteDiasDespues = $hoy->copy()->addDays(7);
        $user = auth()->user();
        $esAdmin = $user->rol === 'admin';
        $esTapachula = $user->rol === 'tapachula';
        $esDorado = $user->rol === 'bodega_dorado';

        $totalTapachula = 0;
        $totalDorado = 0;
        $porVencerTapachula = 0;
        $porVencerDorado = 0;
        $productosProximos = collect();
        $cambioTapachula = 0;
        $cambioDorado = 0;

        if ($esAdmin || $esTapachula) {
            $totalTapachula = (int) Producto::where('rol', 'tapachula')->where('activo', true)->sum('cantidad');
            $porVencerTapachula = Producto::where('rol', 'tapachula')
                ->where('fecha_caducidad', '>=', $hoy)->where('fecha_caducidad', '<=', $sieteDiasDespues)
                ->where('activo', true)->sum('cantidad');
            $cambioTapachula = 12;
        }

        if ($esAdmin || $esDorado) {
            $totalDorado = (int) Producto::where('rol', 'bodega_dorado')->where('activo', true)->sum('cantidad');
            $porVencerDorado = Producto::where('rol', 'bodega_dorado')
                ->where('fecha_caducidad', '>=', $hoy)->where('fecha_caducidad', '<=', $sieteDiasDespues)
                ->where('activo', true)->sum('cantidad');
            $cambioDorado = 8;
        }

        $totalGeneral = $esAdmin ? ($totalTapachula + $totalDorado) : 0;

        $queryProximos = Producto::with(['categoria', 'proveedor'])
            ->where('fecha_caducidad', '>=', $hoy)->where('fecha_caducidad', '<=', $sieteDiasDespues)
            ->where('activo', true);
        if (! $esAdmin) {
            $queryProximos->where('rol', $user->rol);
        }

        $productosProximos = $queryProximos->orderBy('fecha_caducidad', 'asc')->limit(10)->get()
            ->map(function ($producto) use ($hoy) {
                return [
                    'id' => $producto->id,
                    'nombre' => $producto->nombre ?? 'Sin nombre',
                    'bodega' => ucfirst($producto->rol === 'bodega_dorado' ? 'dorado' : $producto->rol),
                    'cantidad' => $producto->cantidad.' unidades',
                    'fecha_vencimiento' => Carbon::parse($producto->fecha_caducidad)->format('d/m/Y'),
                    'dias_restantes' => $hoy->diffInDays(Carbon::parse($producto->fecha_caducidad)),
                    'categoria' => $producto->categoria->nombre ?? 'Sin categoría',
                ];
            });

        $precioTotalTapachula = Producto::where('rol', 'tapachula')->where('cantidad', '>', 0)->sum('precio_total');
        $precioTotalDorado = Producto::where('rol', 'bodega_dorado')->where('cantidad', '>', 0)->sum('precio_total');
        $precioTotalGeneral = Producto::where('cantidad', '>', 0)->sum('precio_total');

        $racksTapachula = Rack::where('bodega', 'tapachula')->get();
        $totalRacksTapachula = $racksTapachula->count();
        $capacidadMaxTapachula = $racksTapachula->sum('cantidad_max');
        $ocupacionTapachula = 0;
        foreach ($racksTapachula as $rack) {
            $ocupacionTapachula += $rack->productosCount()->where('cantidad', '>', 0)->count();
        }
        $porcentajeOcupacionTapachula = $capacidadMaxTapachula > 0 ? round(min(100, ($ocupacionTapachula / $capacidadMaxTapachula) * 100), 1) : 0;

        $racksDorado = Rack::where('bodega', 'bodega_dorado')->get();
        $totalRacksDorado = $racksDorado->count();
        $capacidadMaxDorado = $racksDorado->sum('cantidad_max');
        $ocupacionDorado = 0;
        foreach ($racksDorado as $rack) {
            $ocupacionDorado += $rack->productosCount()->where('cantidad', '>', 0)->count();
        }
        $porcentajeOcupacionDorado = $capacidadMaxDorado > 0 ? round(min(100, ($ocupacionDorado / $capacidadMaxDorado) * 100), 1) : 0;

        $vigentesTapachula = $totalTapachula - $porVencerTapachula;
        $porcentajePorVencerTapachula = ($totalTapachula > 0) ? round(($porVencerTapachula / $totalTapachula) * 100, 1) : 0;
        $vigentesDorado = $totalDorado - $porVencerDorado;
        $porcentajePorVencerDorado = ($totalDorado > 0) ? round(($porVencerDorado / $totalDorado) * 100, 1) : 0;

        $vencidosTapachula = 0;
        $vencidosDorado = 0;
        if ($esAdmin || $esTapachula) {
            $vencidosTapachula = Producto::where('rol', 'tapachula')->where('fecha_caducidad', '<', $hoy)->where('activo', true)->sum('cantidad');
        }
        if ($esAdmin || $esDorado) {
            $vencidosDorado = Producto::where('rol', 'bodega_dorado')->where('fecha_caducidad', '<', $hoy)->where('activo', true)->sum('cantidad');
        }

        $porVencerYVencidosTapachula = $porVencerTapachula + $vencidosTapachula;
        $porVencerYVencidosDorado = $porVencerDorado + $vencidosDorado;
        $porcentajePorVencerTapachulaBarra = ($totalTapachula > 0) ? round(($porVencerYVencidosTapachula / $totalTapachula) * 100, 1) : 0;
        $porcentajePorVencerDoradoBarra = ($totalDorado > 0) ? round(($porVencerYVencidosDorado / $totalDorado) * 100, 1) : 0;

        // ============================================
        // SEMÁFORO
        // ============================================
        $stats = $this->warehouseInventoryService->getInventoryStatsByState();
        $statsWarehouses = $this->warehouseInventoryService->getInventoryStatsByStateAndWarehouse();

        foreach ($stats as $stat) {
            switch ($stat->getState()) {
                case 3: $critical = $stat;
                    break;
                case 2: $attention = $stat;
                    break;
                case 1: $ok = $stat;
                    break;
            }
        }

        $critical = $critical ?? new InventoryStatsByStateDTO(3, 0);
        $attention = $attention ?? new InventoryStatsByStateDTO(2, 0);
        $ok = $ok ?? new InventoryStatsByStateDTO(1, 0);

        $semaforoCritical = $this->warehouseInventoryService->getInventoryByState(3);
        $semaforoAttention = $this->warehouseInventoryService->getInventoryByState(2);
        $semaforoOk = $this->warehouseInventoryService->getInventoryByState(1);

        // ============================================
        // ✅ RANKING TOP 3 CADUCADOS POR ALMACÉN
        // ============================================
        $rankingCaducidad = $this->warehouseInventoryService->getExpiredInventoryRanking();
        $expiredProducts = $this->warehouseInventoryService->getExpiredInventory();

        // // // ============================================
        // ✅ MOVIMIENTOS DEL MES ACTUAL
        // ============================================
        $movimientosResult = $this->warehouseMovementsService->filterTransactionsByDateRange(
            new MovementsByPeriodFilterDTO(
                now()->startOfMonth()->format('2026-01-01'),
                now()->format('Y-m-d'),
                null,
                null
            )
        );

        $movimientos = $movimientosResult->getValue();

        $movementsTotalIN = $this->warehouseMovementsService
            ->countByMovementType(
                'IN'
            );

        $movementsTotalOUT = $this->warehouseMovementsService
            ->countByMovementType(
                'OUT'
            );

        $movementsTotalTRANSFER = $this->warehouseMovementsService
            ->countByMovementType(
                'TRANSFER'
            );

        $movementsTotalADJUSTMENT = $this->warehouseMovementsService
            ->countByMovementType(
                'ADJUSTMENT'
            );

        $movementsTotalRELOCATION = $this->warehouseMovementsService
            ->countByMovementType(
                'RELOCATION'
            );

        $movementsTotalSALE = $this->warehouseMovementsService
            ->countByMovementType(
                'SALE'
            );

        // $movimientosRaw = $movimientosResult->isSuccess() ? $movimientosResult->getValue() : [];

        // $movimientos = array_map(function ($dto) {
        //     return [
        //         'id'                       => $dto->getId(),
        //         'folio'                    => $dto->getFolio(),
        //         'date'                     => $dto->getCreatedAt(),
        //         'type'                     => strtolower($dto->getMovementType()),
        //         'productName'              => $dto->getProductName(),
        //          'warehouseOriginId'        => $dto->getWarehouseId(),
        //         'warehouseOriginName'      => $dto->getWarehousesName(),
        //         'warehouseDestinationId'   => null,
        //         'warehouseDestinationName' => null,
        //         'lotNumber'                => $dto->getLotNumber(),
        //         'quantity'                 => $dto->getQuantity(),
        //         'userName'                 => $dto->getUserName(),
        //     ];
        // }, $movimientosRaw);

        $almacenes = WarehouseModel::select('id', 'warehouses_name')->get();

        return view('module.reports.report', compact(
            'titulo',
            'totalTapachula',
            'totalDorado',
            'totalGeneral',
            'porVencerTapachula',
            'porVencerDorado',
            'vigentesTapachula',
            'porcentajePorVencerTapachula',
            'vigentesDorado',
            'porcentajePorVencerDorado',
            'productosProximos',
            'cambioTapachula',
            'cambioDorado',
            'precioTotalTapachula',
            'precioTotalDorado',
            'precioTotalGeneral',
            'totalRacksTapachula',
            'capacidadMaxTapachula',
            'ocupacionTapachula',
            'porcentajeOcupacionTapachula',
            'totalRacksDorado',
            'capacidadMaxDorado',
            'ocupacionDorado',
            'porcentajeOcupacionDorado',
            'vencidosTapachula',
            'vencidosDorado',
            'porVencerYVencidosTapachula',
            'porVencerYVencidosDorado',
            'porcentajePorVencerTapachulaBarra',
            'porcentajePorVencerDoradoBarra',
            'critical',
            'attention',
            'ok',
            'statsWarehouses',
            'semaforoCritical',
            'semaforoAttention',
            'semaforoOk',
            'almacenes',
            'rankingCaducidad',
            'movimientos',
            'expiredProducts',
            'movementsTotalIN',
            'movementsTotalOUT',
            'movementsTotalADJUSTMENT',
            'movementsTotalRELOCATION',
            'movementsTotalSALE',
            'movementsTotalTRANSFER',
            'warehousesWithLocationsDTO',
            'stockSummary'
        ));
    }

    public function getProductosByWarehouse(int $warehouseId): JsonResponse
    {

        $reportStock = $this
            ->warehouseInventoryService
            ->getStockByWarehouse(
                $warehouseId);

        return response()->json([
            'success' => true,
            'message' => 'Reporte obtenido exitosamente',
            'products' => $reportStock,
        ], 200);
    }

    public function getStocksByWarehouse(int $warehouseId): JsonResponse
    {
        $warehouseInventory = $this->warehouseInventoryService
            ->getWarehouseInventory($warehouseId);

        return response()->json([
            'success' => true,
            'message' => 'Productos obtenidos exitosamente',
            'products' => $warehouseInventory,
        ], 200);
    }

    public function getTransactionsByDateRange(
        Request $request
    ): JsonResponse {

        $validated = $request->validate([
            'start_date' => 'required|date_format:Y-m-d',
            'end_date' => 'required|date_format:Y-m-d|after_or_equal:start_date',
            'warehouse_id' => 'nullable|integer',
            'movement_type' => 'nullable|string',
        ]);

        $filterDTO = new MovementsByPeriodFilterDTO(
            $validated['start_date'],
            $validated['end_date'],
            $validated['movement_type'] ?? null,
            $validated['warehouse_id'] ?? null
        );

        $detailsOfMovements = $this
            ->warehouseMovementsService
            ->filterTransactionsByDateRange(
                $filterDTO
            );

        if ($detailsOfMovements->isFailure()) {
            return response()->json(
                [
                    'success' => false,
                    'message' => $detailsOfMovements->getError(),
                    'data' => null,
                ],
                400
            );
        }

        $detailsOfMovements = $detailsOfMovements->getValue();

        return response()->json([
            'success' => true,
            'message' => 'Movimientos obtenidos exitosamente',
            'data' => [
                'details' => $detailsOfMovements->getDetails(),
                'statistics' => $detailsOfMovements->getStatics(),
            ],
        ], 200);
    }

    public function getStocksByProductAndWarehouse(
        int $warehouseId,
        string $productCode
    ): JsonResponse {
        Log::info(
            'Initializin saerching of produdct '
            .$productCode
            .' into warehouse: '
            .$warehouseId);

        $productInventory = $this->warehouseInventoryService
            ->getProductInventory(
                $warehouseId,
                $productCode
            );

        // 2. Guardas en el log pasando el array como contexto
        Log::info('Consulta de inventario de producto exitosa.', [
            'warehouse_id' => $warehouseId,
            'product_code' => $productCode,
            'inventory_data' => $productInventory, // Aquí se guarda todo el contenido del array
        ]);

        $metrics = $this->warehouseInventoryService
            ->getProductExpirationMetrics(
                $warehouseId,
                $productCode
            );

        Log::info('Consulting about metrics: \n', [
            'warehouse_id' => $warehouseId,
            'product_code' => $productCode,
            'kpis' => $metrics, // Aquí se guarda todo el contenido del array
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Productos obtenidos exitosamente',
            'products' => $productInventory,
            'kpis' => $metrics,
        ], 200);
    }

    public function exportProductLotsExcel(Request $request)
    {
        $lots = $this->decodeLotsPayload($request);
        $rows = $this->prepareLotExportRows($lots);
        $filename = $this->buildExportFilename($lots, 'xlsx', 'Lots');

        return Excel::download(new ProductLotsExport($rows), $filename, \Maatwebsite\Excel\Excel::XLSX, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        ]);
    }

    public function exportProductLotsPdf(Request $request)
    {
        $lots = $this->decodeLotsPayload($request);
        $rows = $this->prepareLotExportRows($lots);
        $filename = $this->buildExportFilename($lots, 'pdf', 'Lots');
        $context = $this->extractLotsContext($lots);

        $pdf = PDF::loadView('module.reports.exports.lots_pdf', [
            'productName' => $context['productName'],
            'productId' => $context['productId'],
            'warehouseName' => $context['warehouseName'],
            'isMultiProduct' => $context['isMultiProduct'],
            'rows' => $rows,
        ]);

        return $pdf->download($filename);
    }

    public function exportPhysicalCountPdf(Request $request)
    {
        $lots = $this->decodeLotsPayload($request);
        $rows = $this->preparePhysicalCountRows($lots);
        $filename = $this->buildExportFilename($lots, 'pdf', 'ConteoFisico');
        $context = $this->extractLotsContext($lots);

        $pdf = PDF::loadView('module.reports.exports.physical_count_pdf', [
            'productName' => $context['productName'],
            'productId' => $context['productId'],
            'warehouseName' => $context['warehouseName'],
            'isMultiProduct' => $context['isMultiProduct'],
            'rows' => $rows,
        ]);

        return $pdf->download($filename);
    }

    private function decodeLotsPayload(Request $request): array
    {
        $decoded = json_decode((string) $request->input('lots', '[]'), true);

        return is_array($decoded) ? $decoded : [];
    }

    private function extractLotsContext(array $lots): array
    {
        $first = $lots[0] ?? [];
        $distinctProductIds = array_unique(array_column($lots, 'productId'));

        return [
            'productName' => $first['productName'] ?? '',
            'productId' => $first['productId'] ?? '',
            'warehouseName' => $first['warehouseName'] ?? '',
            'warehouseId' => $first['warehouseId'] ?? '',
            'isMultiProduct' => count($distinctProductIds) > 1,
        ];
    }

    private function prepareLotExportRows(array $lots): array
    {
        $rows = [];
        foreach ($lots as $index => $lot) {
            $remainingDays = (int) ($lot['remainingDays'] ?? 0);
            $rows[] = [
                'productCode' => $lot['productId'] ?? '-',
                'productName' => $lot['productName'] ?? '-',
                'number' => $index + 1,
                'lotNumber' => $lot['lotNumber'] ?? '-',
                'location' => $this->formatLotLocation($lot),
                'quantity' => $lot['quantity'] ?? 0,
                'expirationDate' => $lot['expirationDate'] ?? '-',
                'remainingDaysLabel' => $this->formatRemainingDaysLabel($remainingDays),
                'obsolescence' => number_format((float) ($lot['obsolescence'] ?? 0), 1).'%',
                'status' => $this->formatLotStatus($remainingDays),
            ];
        }

        return $rows;
    }

    private function formatLotLocation(array $lot): string
    {
        $parts = array_filter([
            ! empty($lot['rack']) ? 'Rack '.$lot['rack'] : null,
            ! empty($lot['level']) ? 'Nv.'.$lot['level'] : null,
            ! empty($lot['module']) ? 'Mod.'.$lot['module'] : null,
            ! empty($lot['bay']) ? 'Bahía '.$lot['bay'] : null,
            ! empty($lot['platform']) ? 'Plat.'.$lot['platform'] : null,
        ]);

        return $parts === [] ? 'Sin ubicación' : implode(' · ', $parts);
    }

    private function formatRemainingDaysLabel(int $days): string
    {
        return $days < 0 ? "Vencido hace {$days} días" : "{$days} días";
    }

    private function buildExportFilename(array $lots, string $extension, string $prefix): string
    {
        $context = $this->extractLotsContext($lots);
        $productId = $context['isMultiProduct'] ? 'TodosLosProductos' : ($context['productId'] ?: 'product');
        $warehouseName = $context['warehouseName'] ?: ($context['warehouseId'] ?: 'warehouse');
        $safe = preg_replace('/[^A-Za-z0-9_\-]/', '_', "{$prefix}_{$productId}_{$warehouseName}");

        return "{$safe}.{$extension}";
    }

    private function preparePhysicalCountRows(array $lots): array
    {
        $rows = [];
        foreach ($lots as $lot) {
            $rows[] = [
                'productCode' => $lot['productId'] ?? '-',
                'warehouseName' => $lot['warehouseName'] ?? '-',
                'location' => $this->formatLotLocation($lot),
                'productName' => $lot['productName'] ?? '-',
                'systemQuantity' => $lot['quantity'] ?? 0,
                'count' => '',
                'difference' => '',
            ];
        }

        return $rows;
    }

    public function exportPhysicalCountExcel(Request $request)
    {
        $lots = $this->decodeLotsPayload($request);
        $rows = $this->preparePhysicalCountRows($lots);
        $filename = $this->buildExportFilename($lots, 'xlsx', 'ConteoFisico');

        return Excel::download(new PhysicalCountExport($rows), $filename, \Maatwebsite\Excel\Excel::XLSX, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        ]);
    }

    private function formatLotStatus(int $days): string
    {
        if ($days < 0) {
            return 'Vencido';
        }

        return $days <= 90 ? 'Por caducar' : 'Vigente';
    }
}
