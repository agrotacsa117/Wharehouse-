<?php

namespace App\Http\Controllers;

use App\Models\Categoria;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class Categorias extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $titulo = 'Administrar categorias';
        $items = Categoria::all();
        return view('module.categorias.index', compact('titulo','items'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $titulo = 'Crear Categoria';
        $items = Categoria::all();
        return view('module.categorias.create', compact('titulo', 'items'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'clave' => 'required|unique:categorias,clave',
            'nombre' => 'required',
        ], [
            'clave.required' => 'La clave de la categoría es obligatoria.',
            'clave.unique' => 'La clave de la categoría ya existe.',
            'nombre.required' => 'El nombre de la categoría es obligatorio.',
        ]);
        try{
            $item = new Categoria();
        $item->user_id = Auth::user()->id;
        $item ->clave =$request->clave;
        $item ->nombre =$request->nombre;
        $item->save(); 
        return redirect()->route('categorias.index')->with('success', 'Categoria agregada!');
        }catch(Exception $e){
            return redirect()->route('categorias.index')->with('error', 'No se pudo guardar!' . $e->getMessage());
        }
        
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        // En vez de mostrar la vista, redirige con un mensaje
        return redirect()->route('categorias.index')->with('info', 'Para eliminar una categoría, usa el botón de eliminar en la lista.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $titulo = 'Actualizar categoria';
        $items = Categoria::find($id);
        return view('module.categorias.edit', compact('items', 'titulo'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        try{
            $items = Categoria::find($id);
        $items ->clave =$request->clave;
        $items ->nombre =$request->nombre;
        $items->save();
        return redirect()->route('categorias.index')->with('success', 'Categoria Actualizada!');
        }catch(Exception $e){
            return redirect()->route('categorias.index')->with('error', 'No se pudo Actualizar!' . $e->getMessage());
        }
    }
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try{
            $items = Categoria::find($id);
        $items->delete();
         return redirect()->route('categorias.index')->with('success', 'Categoria Eliminada!');
        }catch(Exception $e){
            return redirect()->route('categorias.index')->with('error', 'No se pudo Eliminar!' . $e->getMessage());
        }
    }
}
