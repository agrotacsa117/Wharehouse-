<?php

namespace App\Http\Controllers;

use App\Models\Producto;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Contracts\WarehouseInventoryServiceInterface;
use App\Contracts\WarehouseMovementsServiceI;
use App\Mappers\DTO\InventoryStatsByStateDTO;
use App\Mappers\DTO\MovementsByPeriodFilterDTO;

class ReportController extends Controller
{
    private WarehouseInventoryServiceInterface $warehouseInventoryService;
    private WarehouseMovementsServiceI $warehouseMovementsService;

    public function __construct(
        WarehouseInventoryServiceInterface $warehouseInventoryService,
        WarehouseMovementsServiceI $warehouseMovementsService
    ) {
        $this->warehouseInventoryService = $warehouseInventoryService;
        $this->warehouseMovementsService = $warehouseMovementsService;
    }

    public function index()
    {
        $titulo = "Reportes";
        $hoy = Carbon::now();
        $sieteDiasDespues = $hoy->copy()->addDays(7);
        $user = auth()->user();
        $esAdmin    = $user->rol === 'admin';
        $esTapachula = $user->rol === 'tapachula';
        $esDorado   = $user->rol === 'bodega_dorado';

        $totalTapachula = 0;
        $totalDorado    = 0;
        $porVencerTapachula = 0;
        $porVencerDorado    = 0;
        $productosProximos  = collect();
        $cambioTapachula    = 0;
        $cambioDorado       = 0;

        if ($esAdmin || $esTapachula) {
            $totalTapachula = (int)Producto::where('rol', 'tapachula')->where('activo', true)->sum('cantidad');
            $porVencerTapachula = Producto::where('rol', 'tapachula')
                ->where('fecha_caducidad', '>=', $hoy)->where('fecha_caducidad', '<=', $sieteDiasDespues)
                ->where('activo', true)->sum('cantidad');
            $cambioTapachula = 12;
        }

        if ($esAdmin || $esDorado) {
            $totalDorado = (int)Producto::where('rol', 'bodega_dorado')->where('activo', true)->sum('cantidad');
            $porVencerDorado = Producto::where('rol', 'bodega_dorado')
                ->where('fecha_caducidad', '>=', $hoy)->where('fecha_caducidad', '<=', $sieteDiasDespues)
                ->where('activo', true)->sum('cantidad');
            $cambioDorado = 8;
        }

        $totalGeneral = $esAdmin ? ($totalTapachula + $totalDorado) : 0;

        $queryProximos = Producto::with(['categoria', 'proveedor'])
            ->where('fecha_caducidad', '>=', $hoy)->where('fecha_caducidad', '<=', $sieteDiasDespues)
            ->where('activo', true);
        if (!$esAdmin) { $queryProximos->where('rol', $user->rol); }

        $productosProximos = $queryProximos->orderBy('fecha_caducidad', 'asc')->limit(10)->get()
            ->map(function ($producto) use ($hoy) {
                return [
                    'id'               => $producto->id,
                    'nombre'           => $producto->nombre ?? 'Sin nombre',
                    'bodega'           => ucfirst($producto->rol === 'bodega_dorado' ? 'dorado' : $producto->rol),
                    'cantidad'         => $producto->cantidad . ' unidades',
                    'fecha_vencimiento'=> Carbon::parse($producto->fecha_caducidad)->format('d/m/Y'),
                    'dias_restantes'   => $hoy->diffInDays(Carbon::parse($producto->fecha_caducidad)),
                    'categoria'        => $producto->categoria->nombre ?? 'Sin categoría',
                ];
            });

        $precioTotalTapachula = Producto::where('rol', 'tapachula')->where('cantidad', '>', 0)->sum('precio_total');
        $precioTotalDorado    = Producto::where('rol', 'bodega_dorado')->where('cantidad', '>', 0)->sum('precio_total');
        $precioTotalGeneral   = Producto::where('cantidad', '>', 0)->sum('precio_total');

        $racksTapachula       = \App\Models\Rack::where('bodega', 'tapachula')->get();
        $totalRacksTapachula  = $racksTapachula->count();
        $capacidadMaxTapachula = $racksTapachula->sum('cantidad_max');
        $ocupacionTapachula   = 0;
        foreach ($racksTapachula as $rack) { $ocupacionTapachula += $rack->productosCount()->where('cantidad', '>', 0)->count(); }
        $porcentajeOcupacionTapachula = $capacidadMaxTapachula > 0 ? round(min(100, ($ocupacionTapachula / $capacidadMaxTapachula) * 100), 1) : 0;

        $racksDorado       = \App\Models\Rack::where('bodega', 'bodega_dorado')->get();
        $totalRacksDorado  = $racksDorado->count();
        $capacidadMaxDorado = $racksDorado->sum('cantidad_max');
        $ocupacionDorado   = 0;
        foreach ($racksDorado as $rack) { $ocupacionDorado += $rack->productosCount()->where('cantidad', '>', 0)->count(); }
        $porcentajeOcupacionDorado = $capacidadMaxDorado > 0 ? round(min(100, ($ocupacionDorado / $capacidadMaxDorado) * 100), 1) : 0;

        $vigentesTapachula            = $totalTapachula - $porVencerTapachula;
        $porcentajePorVencerTapachula = ($totalTapachula > 0) ? round(($porVencerTapachula / $totalTapachula) * 100, 1) : 0;
        $vigentesDorado               = $totalDorado - $porVencerDorado;
        $porcentajePorVencerDorado    = ($totalDorado > 0) ? round(($porVencerDorado / $totalDorado) * 100, 1) : 0;

        $vencidosTapachula = 0;
        $vencidosDorado    = 0;
        if ($esAdmin || $esTapachula) {
            $vencidosTapachula = Producto::where('rol', 'tapachula')->where('fecha_caducidad', '<', $hoy)->where('activo', true)->sum('cantidad');
        }
        if ($esAdmin || $esDorado) {
            $vencidosDorado = Producto::where('rol', 'bodega_dorado')->where('fecha_caducidad', '<', $hoy)->where('activo', true)->sum('cantidad');
        }

        $porVencerYVencidosTapachula       = $porVencerTapachula + $vencidosTapachula;
        $porVencerYVencidosDorado          = $porVencerDorado + $vencidosDorado;
        $porcentajePorVencerTapachulaBarra = ($totalTapachula > 0) ? round(($porVencerYVencidosTapachula / $totalTapachula) * 100, 1) : 0;
        $porcentajePorVencerDoradoBarra    = ($totalDorado > 0) ? round(($porVencerYVencidosDorado / $totalDorado) * 100, 1) : 0;

        // ============================================
        // SEMÁFORO
        // ============================================
        $stats         = $this->warehouseInventoryService->getInventoryStatsByState();
        $statsWarehouses = $this->warehouseInventoryService->getInventoryStatsByStateAndWarehouse();

        foreach ($stats as $stat) {
            switch ($stat->getState()) {
                case 3: $critical  = $stat; break;
                case 2: $attention = $stat; break;
                case 1: $ok        = $stat; break;
            }
        }

        $critical  = $critical  ?? new InventoryStatsByStateDTO(3, 0);
        $attention = $attention ?? new InventoryStatsByStateDTO(2, 0);
        $ok        = $ok        ?? new InventoryStatsByStateDTO(1, 0);

        $semaforoCritical  = $this->warehouseInventoryService->getInventoryByState(3);
        $semaforoAttention = $this->warehouseInventoryService->getInventoryByState(2);
        $semaforoOk        = $this->warehouseInventoryService->getInventoryByState(1);

        // ============================================
        // ✅ RANKING TOP 3 CADUCADOS POR ALMACÉN
        // ============================================
        $rankingCaducidad = $this->warehouseInventoryService->getExpiredInventoryRanking();

        // ============================================
        // ✅ MOVIMIENTOS DEL MES ACTUAL
        // ============================================
        $movimientosResult = $this->warehouseMovementsService->filterTransactionsByDateRange(
            new MovementsByPeriodFilterDTO(
                now()->startOfMonth()->format('Y-m-d'),
                now()->format('Y-m-d'),
                null,
                null
            )
        );

        $movimientosRaw = $movimientosResult->isSuccess() ? $movimientosResult->getValue() : [];

        $movimientos = array_map(function ($dto) {
            return [
                'id'                       => $dto->getId(),
                'folio'                    => $dto->getFolio(),
                'date'                     => $dto->getCreatedAt(),
                'type'                     => strtolower($dto->getMovementType()),
                'productName'              => $dto->getProductName(),
                'warehouseOriginId'        => $dto->getWarehouseId(),
                'warehouseOriginName'      => $dto->getWarehousesName(),
                'warehouseDestinationId'   => null,
                'warehouseDestinationName' => null,
                'lotNumber'                => $dto->getLotNumber(),
                'quantity'                 => $dto->getQuantity(),
                'userName'                 => $dto->getUserName(),
            ];
        }, $movimientosRaw);

        $almacenes = \App\Models\WarehouseModel::select('id', 'warehouses_name')->get();

        return view('module.reports.report', compact(
            'titulo',
            'totalTapachula', 'totalDorado', 'totalGeneral',
            'porVencerTapachula', 'porVencerDorado',
            'vigentesTapachula', 'porcentajePorVencerTapachula',
            'vigentesDorado', 'porcentajePorVencerDorado',
            'productosProximos', 'cambioTapachula', 'cambioDorado',
            'precioTotalTapachula', 'precioTotalDorado', 'precioTotalGeneral',
            'totalRacksTapachula', 'capacidadMaxTapachula', 'ocupacionTapachula', 'porcentajeOcupacionTapachula',
            'totalRacksDorado', 'capacidadMaxDorado', 'ocupacionDorado', 'porcentajeOcupacionDorado',
            'vencidosTapachula', 'vencidosDorado',
            'porVencerYVencidosTapachula', 'porVencerYVencidosDorado',
            'porcentajePorVencerTapachulaBarra', 'porcentajePorVencerDoradoBarra',
            'critical', 'attention', 'ok',
            'statsWarehouses',
            'semaforoCritical', 'semaforoAttention', 'semaforoOk',
            'almacenes',
            'rankingCaducidad',  // ✅ Top 3 caducados por almacén
            'movimientos'        // ✅ Movimientos del mes actual
        ));
    }
}