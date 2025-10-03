<?php

namespace App\Http\Controllers;

use App\Models\Producto;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class Dashboard extends Controller
{
    public function index()
    {
        $titulo = "Panel de Control";
        $hoy = Carbon::now();
        $sieteDiasDespues = $hoy->copy()->addDays(7);
        $user = auth()->user();
        $esAdmin = $user->rol === 'admin';
        $esTapachula = $user->rol === 'tapachula';
        $esDorado = $user->rol === 'bodega_dorado';

        // Inicializar variables
        $totalTapachula = 0;
        $totalDorado = 0;
        $porVencerTapachula = 0;
        $porVencerDorado = 0;
        $productosProximos = collect();
        $cambioTapachula = 0;
        $cambioDorado = 0;

        // Si es admin o tiene rol de tapachula
        if ($esAdmin || $esTapachula) {
            $totalTapachula = (int)Producto::where('rol', 'tapachula')
                ->where('activo', true)
                ->sum('cantidad');

            $porVencerTapachula = Producto::where('rol', 'tapachula')
                ->where('fecha_caducidad', '>=', $hoy)
                ->where('fecha_caducidad', '<=', $sieteDiasDespues)
                ->where('activo', true)
                ->sum('cantidad');

            $cambioTapachula = 12; // Porcentaje de ejemplo
        }

        // Si es admin o tiene rol de dorado
        if ($esAdmin || $esDorado) {
            $totalDorado = (int)Producto::where('rol', 'bodega_dorado')
                ->where('activo', true)
                ->sum('cantidad');

            $porVencerDorado = Producto::where('rol', 'bodega_dorado')
                ->where('fecha_caducidad', '>=', $hoy)
                ->where('fecha_caducidad', '<=', $sieteDiasDespues)
                ->where('activo', true)
                ->sum('cantidad');

            $cambioDorado = 8; // Porcentaje de ejemplo
        }

        // Total general de productos (solo para admin)
        $totalGeneral = $esAdmin ? ($totalTapachula + $totalDorado) : 0;

        // Productos próximos a vencer (filtrado por rol)
        $queryProximos = Producto::with(['categoria', 'proveedor'])
            ->where('fecha_caducidad', '>=', $hoy)
            ->where('fecha_caducidad', '<=', $sieteDiasDespues)
            ->where('activo', true);

        if (!$esAdmin) {
            $queryProximos->where('rol', $user->rol);
        }

        $productosProximos = $queryProximos->orderBy('fecha_caducidad', 'asc')
            ->limit(10)
            ->get()
            ->map(function ($producto) use ($hoy) {
                $diasRestantes = $hoy->diffInDays(Carbon::parse($producto->fecha_caducidad));
                return [
                    'id' => $producto->id,
                    'nombre' => $producto->nombre ?? 'Sin nombre',
                    'bodega' => ucfirst($producto->rol === 'bodega_dorado' ? 'dorado' : $producto->rol),
                    'cantidad' => $producto->cantidad . ' unidades',
                    'fecha_vencimiento' => Carbon::parse($producto->fecha_caducidad)->format('d/m/Y'),
                    'dias_restantes' => $diasRestantes,
                    'categoria' => $producto->categoria->nombre ?? 'Sin categoría',
                ];
            });

        // Totales de precio por bodega y general
        $precioTotalTapachula = Producto::where('rol', 'tapachula')->where('cantidad', '>', 0)->sum('precio_total');
        $precioTotalDorado = Producto::where('rol', 'bodega_dorado')->where('cantidad', '>', 0)->sum('precio_total');
        $precioTotalGeneral = Producto::where('cantidad', '>', 0)->sum('precio_total');

        // NUEVO: Totales de racks, capacidad máxima y ocupación por bodega
        $racksTapachula = \App\Models\Rack::where('bodega', 'tapachula')->get();
        $totalRacksTapachula = $racksTapachula->count();
        $capacidadMaxTapachula = $racksTapachula->sum('cantidad_max');
        $ocupacionTapachula = 0;
        foreach ($racksTapachula as $rack) {
            $ocupacionTapachula += $rack->productosCount()->where('cantidad', '>', 0)->count();
        }
        $porcentajeOcupacionTapachula = $capacidadMaxTapachula > 0 ? round(min(100, ($ocupacionTapachula / $capacidadMaxTapachula) * 100), 1) : 0;

        $racksDorado = \App\Models\Rack::where('bodega', 'bodega_dorado')->get();
        $totalRacksDorado = $racksDorado->count();
        $capacidadMaxDorado = $racksDorado->sum('cantidad_max');
        $ocupacionDorado = 0;
        foreach ($racksDorado as $rack) {
            $ocupacionDorado += $rack->productosCount()->where('cantidad', '>', 0)->count();
        }
        $porcentajeOcupacionDorado = $capacidadMaxDorado > 0 ? round(min(100, ($ocupacionDorado / $capacidadMaxDorado) * 100), 1) : 0;

        // Cálculos de productos vigentes y porcentajes por vencer
        $vigentesTapachula = $totalTapachula - $porVencerTapachula;
        $porcentajePorVencerTapachula = ($totalTapachula > 0) ? round(($porVencerTapachula / $totalTapachula) * 100, 1) : 0;
        $vigentesDorado = $totalDorado - $porVencerDorado;
        $porcentajePorVencerDorado = ($totalDorado > 0) ? round(($porVencerDorado / $totalDorado) * 100, 1) : 0;

        // Productos vencidos (fecha caducidad < hoy)
        $vencidosTapachula = 0;
        $vencidosDorado = 0;
        if ($esAdmin || $esTapachula) {
            $vencidosTapachula = Producto::where('rol', 'tapachula')
                ->where('fecha_caducidad', '<', $hoy)
                ->where('activo', true)
                ->sum('cantidad');
        }
        if ($esAdmin || $esDorado) {
            $vencidosDorado = Producto::where('rol', 'bodega_dorado')
                ->where('fecha_caducidad', '<', $hoy)
                ->where('activo', true)
                ->sum('cantidad');
        }

        // Sumar vencidos a por vencer para la barra
        $porVencerYVencidosTapachula = $porVencerTapachula + $vencidosTapachula;
        $porVencerYVencidosDorado = $porVencerDorado + $vencidosDorado;
        $porcentajePorVencerTapachulaBarra = ($totalTapachula > 0) ? round(($porVencerYVencidosTapachula / $totalTapachula) * 100, 1) : 0;
        $porcentajePorVencerDoradoBarra = ($totalDorado > 0) ? round(($porVencerYVencidosDorado / $totalDorado) * 100, 1) : 0;

        return view('module.dashboard.home', compact(
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
            'porcentajePorVencerDoradoBarra'
        ));
    }
}
