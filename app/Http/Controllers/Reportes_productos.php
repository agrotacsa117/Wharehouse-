<?php

namespace App\Http\Controllers;
use App\Models\Producto;
use App\Models\Categoria;
use App\Models\Models\Salida;
use Illuminate\Http\Request;

class Reportes_productos extends Controller
{
    public function index(Request $request)
    {
        $titulo = "Reporte de Productos";
        // Filtros para Listado General
        $fechaInicioListado = $request->input('fecha_inicio_listado');
        $fechaFinListado = $request->input('fecha_fin_listado');
        $bodegaListado = $request->input('bodega');
        // Filtros para Resumen
        $fechaInicioResumen = $request->input('fecha_inicio_resumen');
        $fechaFinResumen = $request->input('fecha_fin_resumen');

        // Query para Listado General
        $queryListado = Producto::select(
            'productos.*',
            'categorias.clave as clave_categorias',
            'categorias.nombre as nombre_categorias',
            'users.rol as rol_user',
            'proveedores.nombre as nombre_proveedores'
        )
        ->join('categorias', 'productos.categoria_id', '=', 'categorias.id')
        ->join('users', 'productos.user_id', '=', 'users.id')
        ->join('proveedores', 'productos.proveedor_id', '=', 'proveedores.id');

        if ($fechaInicioListado && $fechaFinListado) {
            $queryListado->whereBetween('productos.fecha_ingreso', [$fechaInicioListado, $fechaFinListado]);
        } elseif ($fechaInicioListado) {
            $queryListado->whereDate('productos.fecha_ingreso', '>=', $fechaInicioListado);
        } elseif ($fechaFinListado) {
            $queryListado->whereDate('productos.fecha_ingreso', '<=', $fechaFinListado);
        }
        if ($bodegaListado) {
            $queryListado->where('productos.rol', $bodegaListado);
        }
        $items = $queryListado->get();

        // Query para Resumen
        $queryResumen = Producto::select(
            'productos.*',
            'categorias.clave as clave_categorias',
            'categorias.nombre as nombre_categorias',
            'users.rol as rol_user',
            'proveedores.nombre as nombre_proveedores'
        )
        ->join('categorias', 'productos.categoria_id', '=', 'categorias.id')
        ->join('users', 'productos.user_id', '=', 'users.id')
        ->join('proveedores', 'productos.proveedor_id', '=', 'proveedores.id');

        if ($fechaInicioResumen && $fechaFinResumen) {
            $queryResumen->whereBetween('productos.fecha_ingreso', [$fechaInicioResumen, $fechaFinResumen]);
        } elseif ($fechaInicioResumen) {
            $queryResumen->whereDate('productos.fecha_ingreso', '>=', $fechaInicioResumen);
        } elseif ($fechaFinResumen) {
            $queryResumen->whereDate('productos.fecha_ingreso', '<=', $fechaFinResumen);
        }
        $itemsResumen = $queryResumen->get();

        // Suma de salidas por producto
        $salidasPorProducto = Salida::selectRaw('producto_id, SUM(cantidad) as total_salidas')
            ->groupBy('producto_id')
            ->pluck('total_salidas', 'producto_id');

        $todasCategorias = Categoria::select('clave', 'nombre')
            ->get()
            ->pluck('nombre', 'clave')
            ->toArray();

        return view('module.reportes_productos.index', compact('titulo', 'items', 'todasCategorias', 'salidasPorProducto', 'itemsResumen'));
    }
}
