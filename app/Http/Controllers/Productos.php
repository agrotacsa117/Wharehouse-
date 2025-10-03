<?php

namespace App\Http\Controllers;

use App\Models\Categoria;
use App\Models\Producto;    
use App\Models\Proveedor;
use App\Models\Rack;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class Productos extends Controller
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
            'users.name as nombre_usuario',
            'proveedores.nombre as nombre_proveedores'
        )
        ->join('categorias', 'productos.categoria_id', '=', 'categorias.id')
        ->join('users', 'productos.user_id', '=', 'users.id')
        ->join('proveedores', 'productos.proveedor_id', '=', 'proveedores.id');

        // Filtrar por rol de usuario
        $user = auth()->user();
        if ($user->rol === 'admin') {
            $query->where(function($q) use ($user) {
                $q->whereIn('productos.rol', ['tapachula', 'bodega_dorado'])
                  ->orWhere('productos.user_id', $user->id);
            });
        } else {
            $query->where('productos.rol', $user->rol);
        }

        $items = $query->where('productos.cantidad', '>', 0)->get();

        // Calcular totales por bodega y general
        $totalTapachula = Producto::where('rol', 'tapachula')->where('cantidad', '>', 0)->sum('precio_total');
        $totalDorado = Producto::where('rol', 'bodega_dorado')->where('cantidad', '>', 0)->sum('precio_total');
        $totalGeneral = Producto::where('cantidad', '>', 0)->sum('precio_total');

        return view('module.productos.index', compact('titulo', 'items', 'totalTapachula', 'totalDorado', 'totalGeneral'));
    }

    public function vencer()
    {
        $titulo = "Productos Vencidos";
        $user = auth()->user();
        
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
        // Mostrar productos vencidos (diferencia negativa) o que vencen en 7 días o menos
        ->whereRaw('DATEDIFF(fecha_caducidad, CURDATE()) <= 7')
        ->orderBy('fecha_caducidad', 'asc');

        // Filtrar por rol de usuario
        if ($user->rol === 'admin') {
            $query->where(function($q) use ($user) {
                $q->whereIn('productos.rol', ['tapachula', 'bodega_dorado'])
                  ->orWhere('productos.user_id', $user->id);
            });
        } else {
            $query->where('productos.rol', $user->rol);
        }
    
        $items = $query->where('productos.cantidad', '>', 0)->get();
        return view('module.productos.vencer', compact('titulo', 'items'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $titulo = 'Editar Producto';
        $categorias = Categoria::all();
        $proveedores = Proveedor::all();
        $item = Producto::findOrFail($id);
        if (auth()->user()->rol === 'tapachula' || auth()->user()->rol === 'bodega_dorado') {
            $racks = \App\Models\Rack::where('bodega', auth()->user()->rol)->get();
        } else {
            $racks = \App\Models\Rack::all();
        }
        // Verificar si el usuario tiene permiso para editar este producto
        if (auth()->user()->rol !== 'admin' && $item->rol !== auth()->user()->rol) {
            return redirect()->route('productos')->with('error', 'No tienes permiso para editar este producto');
        }
        return view('module.productos.edit', compact('titulo', 'item', 'categorias', 'proveedores', 'racks'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        try {
            $item = Producto::findOrFail($id);
            // Verificar si el usuario tiene permiso para actualizar este producto
            if (auth()->user()->rol !== 'admin' && $item->rol !== auth()->user()->rol) {
                return redirect()->back()->with('error', 'No tienes permiso para actualizar este producto');
            }
            // Validar cantidad de cajas (productos) en el rack al editar
            if ($request->rack_id) {
                $rack = Rack::find($request->rack_id);
                // Contar cajas (productos) con cantidad > 0 en el rack, excluyendo el actual
                $cajasOcupadas = Producto::where('rack_id', $rack->id)
                    ->where('id', '!=', $item->id)
                    ->where('cantidad', '>', 0)
                    ->count();
                if ($rack && ($cajasOcupadas >= $rack->cantidad_max)) {
                    return redirect()->back()->withInput()->with('error', 'No se puede asignar este rack: el rack seleccionado ya está lleno.');
                }
            }
            $item->categoria_id = $request->categoria_id;
            $item->proveedor_id = $request->proveedor_id;
            $item->colocado = $request->colocado;
            $item->cantidad = $request->cantidad;
            $item->fecha_ingreso = $request->fecha_ingreso;
            $item->fecha_caducidad = $request->fecha_caducidad;
            $item->precio = $request->precio;
            $item->precio_total = $request->precio_total;
            // Si hay rack_id, usar el nombre del rack para el campo colocado
            if ($request->rack_id) {
                $rack = \App\Models\Rack::find($request->rack_id);
                $item->colocado = $rack ? $rack->rack_aduana : null;
            } else {
                $item->colocado = null;
            }
            $item->rack_id = $request->rack_id;
            $item->save();
            return redirect()->route('productos')->with('success', 'Producto actualizado correctamente');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error al actualizar el producto: ' . $e->getMessage());
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $titulo = 'Crear Producto';
        $categorias = Categoria::all();
        $proveedores = Proveedor::all();
        if (auth()->user()->rol === 'tapachula' || auth()->user()->rol === 'bodega_dorado') {
            $racks = \App\Models\Rack::where('bodega', auth()->user()->rol)->get();
        } else {
            $racks = \App\Models\Rack::all();
        }
        // Ya no se filtran racks llenos, se muestran todos
        return view('module.productos.create', compact('titulo', 'categorias', 'proveedores', 'racks'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            // Validar cantidad de cajas (productos) en el rack
            if ($request->rack_id) {
                $rack = Rack::find($request->rack_id);
                // Contar cajas (productos) con cantidad > 0 en el rack
                $cajasOcupadas = Producto::where('rack_id', $rack->id)
                    ->where('cantidad', '>', 0)
                    ->count();
                if ($rack && ($cajasOcupadas >= $rack->cantidad_max)) {
                    return redirect()->back()->withInput()->with('error', 'No se puede agregar esta caja: el rack seleccionado ya está lleno.');
                }
            }
            $producto = new Producto();
            $producto->categoria_id = $request->categoria_id;
            $producto->proveedor_id = $request->proveedor_id;
            $producto->user_id = auth()->id();
            // Asignar el rol: si viene del formulario úsalo, si no, usa el del usuario autenticado
            $producto->rol = $request->bodega ?? auth()->user()->rol;
            // Si hay rack_id, usar el nombre del rack para el campo colocado
            if ($request->rack_id) {
                $rack = \App\Models\Rack::find($request->rack_id);
                $producto->colocado = $rack ? $rack->rack_aduana : null;
            } else {
                $producto->colocado = null;
            }
            $producto->rack_id = $request->rack_id;
            $producto->cantidad = $request->cantidad;
            $producto->fecha_ingreso = $request->fecha_ingreso;
            $producto->fecha_caducidad = $request->fecha_caducidad;
            $producto->precio = $request->precio;
            $producto->precio_total = $request->precio_total;
            $producto->save();

            return redirect()->route('productos')->with('success', 'Producto creado correctamente');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error al crear el producto: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $titulo = 'Detalles del Producto';
        $item = Producto::findOrFail($id);
        
        // Verificar si el usuario tiene permiso para ver este producto
        if (auth()->user()->rol !== 'admin' && $item->rol !== auth()->user()->rol) {
            return redirect()->route('productos')->with('error', 'No tienes permiso para ver este producto');
        }
        
        return view('module.productos.show', compact('titulo', 'item'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $item = Producto::findOrFail($id);
            
            // Verificar si el usuario tiene permiso para eliminar este producto
            if (auth()->user()->rol !== 'admin' && $item->rol !== auth()->user()->rol) {
                return redirect()->back()->with('error', 'No tienes permiso para eliminar este producto');
            }
            
            $item->delete();
            return redirect()->route('productos')->with('success', 'Producto eliminado correctamente');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error al eliminar el producto: ' . $e->getMessage());
        }
    }

    /**
     * Update the specified resource status.
     */
    public function estado($id, $estado)
    {
        try {
            $item = Producto::findOrFail($id);
            if (auth()->user()->rol !== 'admin' && $item->rol !== auth()->user()->rol) {
                return 0;
            }
            $item->activo = $estado;
            if ($item->save()) {
                return 1;
            } else {
                return 0;
            }
        } catch (\Exception $e) {
            return 0;
        }
    }
}
