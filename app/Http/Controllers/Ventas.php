<?php

namespace App\Http\Controllers;

use App\Models\Models\Salida;
use App\Models\Producto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use PDF;
use Illuminate\Support\Facades\Storage;

class Ventas extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware(function ($request, $next) {
            if (!in_array(auth()->user()->rol, ['admin', 'tapachula', 'bodega_dorado'])) {
                abort(403, 'No tienes permiso para acceder a esta sección');
            }
            return $next($request);
        });
    }
    public function index(){
        $titulo = "Salidas de Productos";
        
        // Obtener el rol del usuario autenticado
        $userRol = auth()->user()->rol;
        
        // Construir la consulta base
        $query = Producto::select(
            'productos.*',
            'categorias.clave as clave_categorias',
            'categorias.nombre as nombre_categorias',
            'users.rol as rol_usuario',
            'productos.rol as rol_producto'
        )
        ->join('categorias', 'productos.categoria_id', '=', 'categorias.id')
        ->join('users', 'productos.user_id', '=', 'users.id')
        ->where('productos.cantidad', '>', 0); // Solo productos con stock disponible
        
        // Filtrar por rol del usuario si no es admin
        if ($userRol !== 'admin' && in_array($userRol, ['tapachula', 'bodega_dorado'])) {
            $query->where('productos.rol', $userRol);
        }
        
        $items = $query->get();
        
        // Obtener el carrito de salidas de la sesión
        $salidas = session('salidas', []);
        
        return view('module.ventas.index', compact('titulo', 'items', 'salidas'));
    }

    public function agregarSalida(Request $request, $id)
    {
        $producto = Producto::select(
            'productos.*',
            'categorias.clave as clave_categorias',
            'categorias.nombre as nombre_categorias'
        )
        ->join('categorias', 'productos.categoria_id', '=', 'categorias.id')
        ->where('productos.id', $id)
        ->first();

        if (!$producto) {
            return redirect()->back()->with('error', 'Producto no encontrado');
        }

        // Obtener la cantidad del formulario
        $cantidad = $request->input('cantidad', 1);

        // Validar que no se supere el stock disponible
        if ($cantidad > $producto->cantidad) {
            return redirect()->back()->with('error', 'Cantidad excede el stock disponible');
        }

        // Obtener las salidas actuales de la sesión
        $salidas = session('salidas', []);

        // Si el producto ya está en las salidas, sumar la cantidad
        if (isset($salidas[$id])) {
            $salidas[$id]['cantidad'] += $cantidad;
        } else {
            $salidas[$id] = [
                'id' => $producto->id,
                'clave' => $producto->clave_categorias,
                'nombre' => $producto->nombre_categorias,
                'cantidad' => $cantidad,
                'stock' => $producto->cantidad,
                'rol' => $producto->rol, // Usar el rol del producto original
                'colocado' => $producto->colocado, // <-- Aseguramos que se guarde el rack/aduana
                'fecha_caducidad' => $producto->fecha_caducidad, // Para el ticket
                'observaciones' => $request->input('observaciones', '')
            ];
        }

        // Guardar las salidas actualizadas en la sesión
        session(['salidas' => $salidas]);

        return redirect()->back()->with('success', 'Producto agregado a las salidas');
    }

    public function eliminarSalida($id)
    {
        // Obtener las salidas actuales de la sesión
        $salidas = session('salidas', []);
        
        // Verificar si el producto está en el carrito
        if (isset($salidas[$id])) {
            // Eliminar el producto del carrito
            unset($salidas[$id]);
            
            // Si el carrito está vacío después de eliminar, limpiar la sesión
            if (empty($salidas)) {
                session()->forget('salidas');
            } else {
                // Guardar las salidas actualizadas
                session(['salidas' => $salidas]);
            }
            
            // Redirigir con mensaje de éxito
            return redirect()->back()->with('success', 'Producto eliminado del carrito');
        }
        
        // Si el producto no está en el carrito
        return redirect()->back()->with('error', 'El producto no está en el carrito');
    }

    public function actualizarSalida(Request $request)
    {
        $salidas = session('salidas', []);
        $cantidades = $request->input('cantidades', []);
        foreach ($cantidades as $id => $cantidad) {
            if (isset($salidas[$id])) {
                $salidas[$id]['cantidad'] = $cantidad;
            }
        }
        session(['salidas' => $salidas]);
        return redirect()->back()->with('success', 'Salidas actualizadas');
    }

    public function finalizarSalida(Request $request)
    {
        $salidas = session('salidas', []);
        if (empty($salidas)) {
            return redirect()->back()->with('error', 'No hay salidas registradas');
        }

        // Guardar la bodega destino seleccionada en la sesión
        $bodegaDestino = $request->input('bodega_destino');
        session(['bodega_destino' => $bodegaDestino]);

        // Generar el ticket PDF grupal antes de crear las salidas
        $usuario = auth()->user();
        $donadoA = $request->input('donado_a');
        $pdf = PDF::loadView('module.salidas.comprobante_carrito_pdf', [
            'carrito' => $salidas,
            'usuario' => $usuario,
            'bodegaDestino' => $bodegaDestino,
            'donadoA' => $donadoA
        ]);
        $fileName = 'ticket_salida_' . now()->format('Ymd_His') . '.pdf';
        $filePath = 'tickets/' . $fileName;
        Storage::disk('public')->put($filePath, $pdf->output());

        // Procesar cada salida y guardar la ruta del ticket
        foreach ($salidas as $id => $salida) {
            // Validar que el producto aún existe y tiene stock suficiente
            $producto = Producto::find($id);
            if (!$producto || $salida['cantidad'] > $producto->cantidad) {
                return redirect()->back()->with('error', 'Error al procesar las salidas: stock insuficiente');
            }

            $donadoA = $request->input('donado_a');

            // Crear el registro de salida con la ruta del ticket
            $nuevaSalida = Salida::create([
                'producto_id' => $id,
                'cantidad' => $salida['cantidad'],
                'fecha_salida' => now(),
                'usuario_id' => Auth::id(),
                'observaciones' => $salida['observaciones'],
                'ticket_pdf' => $filePath,
                'bodega_destino' => $bodegaDestino,
                'donado_a' => $donadoA
            ]);
            \Log::info('Salida creada', $nuevaSalida->toArray());

            // Actualizar el stock del producto
            $producto->update([
                'cantidad' => $producto->cantidad - $salida['cantidad'],
                'precio_total' => ($producto->cantidad - $salida['cantidad']) * $producto->precio
            ]);
        }

        // Limpiar el carrito de salidas
        session(['salidas' => []]);
        
        return redirect()->back()->with('success', 'Salidas procesadas exitosamente. Ticket generado.');
    }

    public function reporteSalidas()
    {
        $titulo = "Reporte de Salidas de Productos";
        date_default_timezone_set('America/Mexico_City');
        $fechaGeneracion = now()->format('d-m-Y H:i:s');
        $userRol = auth()->user()->rol;

        $query = Salida::select(
            'salidas.*',
            'productos.colocado as colocado',
            'productos.fecha_caducidad as fecha_caducidad',
            'categorias.clave as clave_categorias',
            'categorias.nombre as nombre_categoria',
            'users.name as nombre_usuario',
            'productos.rol as rol_producto',
            DB::raw("DATE_FORMAT(CONVERT_TZ(salidas.fecha_salida, '+00:00', '-06:00'), '%d-%m-%Y') as fecha_salida_formateada")
        )
        ->join('productos', 'salidas.producto_id', '=', 'productos.id')
        ->join('categorias', 'productos.categoria_id', '=', 'categorias.id')
        ->join('users', 'salidas.usuario_id', '=', 'users.id')
        ->orderBy('salidas.fecha_salida', 'desc');

        if (in_array($userRol, ['tapachula', 'bodega_dorado'])) {
            $query->where('productos.rol', $userRol);
        }

        // Filtro por fechas
        if (request('fecha_inicio')) {
            $query->whereDate('salidas.fecha_salida', '>=', request('fecha_inicio'));
        }
        if (request('fecha_fin')) {
            $query->whereDate('salidas.fecha_salida', '<=', request('fecha_fin'));
        }
        // Filtro por usuario (nombre)
        if (request('usuario')) {
            $usuario = trim(request('usuario'));
            $query->where('users.name', 'like', "%$usuario%");
        }

        $salidas = $query->get();

        // Agrupar por ticket_pdf para mostrar solo un ticket por grupo
        $tickets = $salidas->groupBy('ticket_pdf');

        return view('module.ventas.reporte_salidas_prodcutos', compact('titulo', 'tickets', 'fechaGeneracion'));
    }



    public function destroySalida($id)
    {
        try {
            DB::beginTransaction();

            $salida = Salida::findOrFail($id);

            // Restore product stock
            $producto = Producto::find($salida->producto_id);
            if ($producto) {
                $producto->cantidad += $salida->cantidad;
                $producto->save();
            } else {
                // Optionally log or handle if product not found
                // For now, proceeding with salida deletion
            }

            $salida->delete();

            DB::commit();

            return redirect()->route('reporte.salidas')->with('success', 'Salida eliminada y stock restaurado exitosamente.');

        } catch (\Exception $e) {
            DB::rollBack();
            // It's good practice to log the error
            // \Log::error('Error deleting salida: ' . $e->getMessage());
            return redirect()->route('reporte.salidas')->with('error', 'Error al eliminar la salida.');
        }
    }

    /**
     * Mostrar comprobante de salida individual
     */
    public function comprobanteSalida($id)
    {
        $salida = \App\Models\Models\Salida::select(
            'salidas.*',
            'productos.colocado as colocado',
            'productos.fecha_caducidad as fecha_caducidad',
            'categorias.clave as clave_categorias',
            'categorias.nombre as nombre_categoria',
            'users.name as nombre_usuario',
            'productos.rol as rol_producto'
        )
        ->join('productos', 'salidas.producto_id', '=', 'productos.id')
        ->join('categorias', 'productos.categoria_id', '=', 'categorias.id')
        ->join('users', 'salidas.usuario_id', '=', 'users.id')
        ->where('salidas.id', $id)
        ->firstOrFail();

        return view('module.salidas.comprobante_salida', compact('salida'));
    }

    /**
     * Mostrar ticket grupal del carrito de salida
     */
    public function ticketCarrito()
    {
        $carrito = session('salidas', []);
        $usuario = auth()->user();
        $bodegaDestino = session('bodega_destino');
        return view('module.salidas.comprobante_carrito', compact('carrito', 'usuario', 'bodegaDestino'));
    }

    /**
     * Generar y guardar ticket PDF de salida grupal
     */
    public function generarTicketSalida()
    {
        $usuario = auth()->user();
        $carrito = session('salidas', []);
        $bodegaDestino = session('bodega_destino', null);

        $pdf = PDF::loadView('module.salidas.comprobante_carrito', compact('usuario', 'carrito', 'bodegaDestino'));

        $fileName = 'ticket_salida_' . now()->format('Ymd_His') . '.pdf';
        $filePath = 'tickets/' . $fileName;

        Storage::disk('public')->put($filePath, $pdf->output());

        // Puedes guardar la ruta en la base de datos si lo deseas

        // Redirigir a la descarga directa
        return response()->download(storage_path('app/public/' . $filePath));
        // O mostrar enlace en una vista:
        // return view('module.salidas.ticket_descargado', compact('filePath'));
    }

    /**
     * Mostrar ticket PDF grupal desde el reporte
     */
    public function mostrarTicketGrupal($ticket_pdf)
    {
        // Buscar todas las salidas asociadas a ese ticket
        $salidas = Salida::where('ticket_pdf', $ticket_pdf)
            ->join('productos', 'salidas.producto_id', '=', 'productos.id')
            ->join('categorias', 'productos.categoria_id', '=', 'categorias.id')
            ->select(
                'salidas.*',
                'salidas.bodega_destino',
                'productos.colocado as colocado',
                'productos.fecha_caducidad as fecha_caducidad',
                'categorias.clave as clave_categorias',
                'categorias.nombre as nombre_categoria',
                'productos.rol as rol_producto'
            )
            ->get();

        if ($salidas->isEmpty()) {
            abort(404, 'Ticket no encontrado');
        }

        $usuario_id = $salidas->first()->usuario_id ?? null;
        $usuario = $usuario_id ? \App\Models\User::find($usuario_id) : null;
        $bodegaDestino = $salidas->first()->bodega_destino ?? null; // Si guardas este dato, recupéralo aquí
        $donadoA = $salidas->first()->donado_a ?? null;

        // Reconstruir el carrito para la vista
        $carrito = $salidas->map(function($salida) {
            return [
                'clave' => $salida->clave_categorias,
                'nombre' => $salida->nombre_categoria,
                'cantidad' => $salida->cantidad,
                'colocado' => $salida->colocado,
                'rol' => $salida->rol_producto,
                'fecha_caducidad' => $salida->fecha_caducidad,
                'observaciones' => $salida->observaciones,
                'fecha_salida' => $salida->fecha_salida // <-- Agregado
            ];
        });

        return view('module.salidas.comprobante_carrito', [
            'carrito' => $carrito,
            'usuario' => $usuario,
            'bodegaDestino' => $bodegaDestino,
            'donadoA' => $donadoA
        ]);
    }

    public function actualizarGlobal(Request $request)
    {
        $salidas = session('salidas', []);
        $cantidades = $request->input('cantidades', []);
        $errores = [];
        foreach ($cantidades as $id => $cantidad) {
            if (isset($salidas[$id])) {
                // Validar que la cantidad sea mayor a 0 y no supere el stock
                $max = $salidas[$id]['stock'] + $salidas[$id]['cantidad'];
                if ($cantidad < 1 || $cantidad > $max) {
                    $errores[] = "Cantidad inválida para el producto {$salidas[$id]['nombre']} (máx: $max)";
                } else {
                    $salidas[$id]['cantidad'] = $cantidad;
                }
            }
        }
        session(['salidas' => $salidas]);
        if (count($errores) > 0) {
            return redirect()->back()->with('error', implode(' | ', $errores));
        }
        return redirect()->back()->with('success', 'Cantidades actualizadas correctamente');
    }
}
