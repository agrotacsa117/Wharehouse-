<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Support\Facades\DB;

class ProductController extends Controller
{
    
    public function index()
    {
        $products = Product::with('category')
            ->select('products.*', 
                DB::raw('products.precio * products.cantidad as precio_total'))
            ->paginate(10);
        return view('module.productos.index', compact('products'));
    }

    /**
     * Muestra el formulario para crear un nuevo producto
     */
    public function create()
    {
        $categories = Category::all();
        return view('module.productos.create', compact('categories'));
    }

    /**
     * Almacena un nuevo producto
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'categoria_id' => 'required|exists:categorias,id',
            'proveedor_id' => 'required|exists:proveedores,id',
            'colocado' => 'required|string',
            'cantidad' => 'required|integer|min:0',
            'precio' => 'required|numeric|min:0',
            'fecha_ingreso' => 'required|date',
            'fecha_caducidad' => 'required|date',
            'activo' => 'boolean',
            'precio_total' => 'numeric|min:0'
        ]);

        $data = $request->all();
        // Calcular precio total si no se proporciona
        if (!isset($data['precio_total'])) {
            $data['precio_total'] = $data['precio'] * $data['cantidad'];
        }

        Product::create($data);
        return redirect()->route('productos.index')->with('success', 'Producto creado exitosamente');
    }

    /**
     * Muestra el formulario para editar un producto
     */
    public function edit(Product $product)
    {
        $categories = Category::all();
        return view('module.productos.edit', compact('product', 'categories'));
    }

    /**
     * Actualiza un producto
     */
    public function update(Request $request, Product $product)
    {
        $this->validate($request, [
            'categoria_id' => 'required|exists:categorias,id',
            'proveedor_id' => 'required|exists:proveedores,id',
            'colocado' => 'required|string',
            'cantidad' => 'required|integer|min:0',
            'precio' => 'required|numeric|min:0',
            'fecha_ingreso' => 'required|date',
            'fecha_caducidad' => 'required|date',
            'activo' => 'boolean',
            'precio_total' => 'numeric|min:0'
        ]);

        $data = $request->all();
        // Siempre recalcular el precio total con la cantidad y precio actualizados
        $data['precio_total'] = $data['precio'] * $data['cantidad'];

        $product->update($data);
        return redirect()->route('productos.index')->with('success', 'Producto actualizado exitosamente');
    }

    /**
     * Muestra productos a punto de vencer
     */
   
}
