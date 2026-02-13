<?php

namespace App\Http\Controllers;

use App\Models\Categoria;
use App\Models\Producto;    
use App\Models\Proveedor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TemporaryProducts extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $titulo = "Productos";
        $query = Producto::select(
            'productos.*',
            'categorias.clave as clave_categorias',
            'categorias.nombre as nombre_categorias',
            'users.rol as rol_user',
            'proveedores.nombre as nombre_proveedores'
        )
        ->join('categorias', 'productos.categoria_id', '=', 'categorias.id')
        ->join('users', 'productos.user_id', '=', 'users.id')
        ->join('proveedores', 'productos.proveedor_id', '=', 'proveedores.id');

        // Si el usuario no es admin, filtrar por su rol
        if (auth()->user()->rol !== 'admin') {
            $query->where('productos.rol', auth()->user()->rol);
        } else {
            // El admin ve todos los productos de todos los roles
            $query->whereIn('productos.rol', ['tapachula', 'bodega_dorado']);
        }

        $items = $query->get();

        return view('module.productos.index', compact('titulo', 'items'));
    }

    public function vencer()
    {
        $titulo = "Productos Vencidos";
        $query = Producto::select(
            'productos.*',
            'categorias.clave as clave_categorias',
            'categorias.nombre as nombre_categorias',
            'users.rol as rol_user',
            'proveedores.nombre as nombre_proveedores'
        )
        ->join('categorias', 'productos.categoria_id', '=', 'categorias.id')
        ->join('users', 'productos.user_id', '=', 'users.id')
        ->join('proveedores', 'productos.proveedor_id', '=', 'proveedores.id')
        ->whereRaw('DATEDIFF(fecha_caducidad, CURDATE()) <= 7');

        // Si el usuario no es admin, filtrar por su rol
        if (auth()->user()->rol !== 'admin') {
            $query->where('productos.rol', auth()->user()->rol);
        } else {
            // El admin ve todos los productos de todos los roles
            $query->whereIn('productos.rol', ['tapachula', 'bodega_dorado']);
        }
    
        $items = $query->get();
        return view('module.productos.vencer', compact('titulo', 'items'));
    }

    // ... resto de los métodos del controlador ...
}
