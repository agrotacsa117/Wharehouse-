<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\Proveedor;
class Proveedores extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $titulo = 'Proveedores';
        $items = Proveedor::all();
        return view('module.proveedores.index',compact('items', 'titulo'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $titulo ='Agregar proveedor';
        return view('module.proveedores.create', compact('titulo'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $items = new Proveedor();
            $items->nombre = $request->nombre;
            $items->comp_domicilio = $request->comp_domicilio;
            $items->ine = $request->ine;
            $items->acta_constitutiva = $request->acta_constitutiva;
            $items->rfc = $request->rfc;    
            $items->direccion = $request->direccion;
            $items->save();
            return redirect()->route('proveedores')->with("success", "Proveedor agregado con exito!!");
        } catch (\Throwable $th) {
            return redirect()->route('proveedores')->with("error", "Fallo al agregar Proveedor !!".$th->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $titulo = "Eliminar un Proveedor";
        $items = Proveedor::find($id);
        return view('module.proveedores.show', compact('items', 'titulo'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $items = Proveedor::find($id);
        $titulo = 'Editar proveedor';
        return view('module.proveedores.edit', compact('items', 'titulo'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        try {
            $items = Proveedor::find($id);
            $items->nombre = $request->nombre;
            $items->comp_domicilio = $request->comp_domicilio;
            $items->ine = $request->ine;
            $items->acta_constitutiva = $request->acta_constitutiva;
            $items->rfc = $request->rfc;
            $items->direccion = $request->direccion;    
            $items->save();
        return redirect()->route('proveedores')->with("success", "Actualizado con exito!!");
        } catch (\Throwable $th) {
            return redirect()->route('proveedores')->with("error", "Fallo no se puedo Actualizar !!".$th->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try{
            $items = Proveedor::find($id);
        $items->delete();
         return redirect()->route('proveedores')->with('success', 'Proveedor Eliminado!');
        }catch(\Throwable $th){
            return redirect()->route('proveedores')->with('error', 'FALLO AL ELIMINAR!' . $th->getMessage());
        }
    }
}
