<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Rack;
use App\Models\User;

class RackController extends Controller
{
    public function index()
    {
        // Traer todos los racks con su usuario relacionado
         $titulo = 'Administrar Racks';
        $items = Rack::all();
        return view('module.rack.index', compact('titulo','items'));
    }
    public function create()
    {
        $titulo = 'Crear Rack';
        $usuarios = User::whereIn('rol', ['tapachula', 'bodega_dorado'])->get();
        return view('module.rack.create', compact('titulo', 'usuarios'));
    }
    public function store(Request $request)
    {
        $request->validate([
            'rack_aduana' => 'required|string|max:255',
            'cantidad_max' => 'required|integer|min:1',
            'bodega' => 'required|in:tapachula,bodega_dorado',
        ]);

        Rack::create([
            'rack_aduana' => $request->rack_aduana,
            'cantidad_max' => $request->cantidad_max,
            'user_id' => null, // Ya no se usa user_id, pero la migración lo requiere. Puedes dejarlo null o ajustar la migración/modelo si quieres eliminarlo.
            'bodega' => $request->bodega,
        ]);

        return redirect()->route('rack.index')->with('success', 'Rack creado correctamente.');
    }
    public function edit($id)
    {
        $rack = Rack::findOrFail($id);
        $titulo = 'Editar Rack';
        $bodegas = [
            'tapachula' => 'Tapachula',
            'bodega_dorado' => 'Bodega Dorado',
        ];
        return view('module.rack.edit', compact('rack', 'titulo', 'bodegas'));
    }
    public function update(Request $request, $id)
    {
        $request->validate([
            'rack_aduana' => 'required|string|max:255',
            'cantidad_max' => 'required|integer|min:1',
            'bodega' => 'required|in:tapachula,bodega_dorado',
        ]);

        $rack = Rack::findOrFail($id);
        $rack->update([
            'rack_aduana' => $request->rack_aduana,
            'cantidad_max' => $request->cantidad_max,
            'bodega' => $request->bodega,
        ]);

        return redirect()->route('rack.index')->with('success', 'Rack actualizado correctamente.');
    }
    public function destroy($id)
    {
        $rack = Rack::findOrFail($id);
        $rack->delete();
        return redirect()->route('rack.index')->with('success', 'Rack eliminado correctamente.');
    }
}
