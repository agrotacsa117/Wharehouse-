<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use App\Models\User;
use Exception;
use App\Contracts\RoleServiceI;

class Usuarios extends Controller
{
    private RoleServiceI $roleService;

    public function __construct(
        RoleServiceI $roleService
    ) {
        $this->roleService = $roleService;
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $titulo = "Usuarios";
        $items = User::all();
        return view('module.usuarios.index', compact('items', 'titulo'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $rols = $this->roleService->getRolesList();
        $titulo = 'Usuario Nuevo';
        return view('module.usuarios.create', compact(
            'titulo',
            'rols'
        ));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:users,name',
            'email' => 'required|email|unique:users,email',
            'password' => 'required',
            'rol' => 'required',
        ], [
            'name.required' => 'El usuario es obligatorio.',
            'name.unique' => 'Ya existe un usuario con ese nombre.',
            'email.required' => 'El correo es obligatorio.',
            'email.email' => 'El correo no es válido.',
            'email.unique' => 'Ya existe un usuario con ese correo.',
            'password.required' => 'La contraseña es obligatoria.',
            'rol.required' => 'El rol es obligatorio.',
        ]);

        //rol-name
        $rolJson = $request->input('rol');
        $rolData = json_decode($rolJson, true);

        // 3. Accedes a los datos (como es array, usas corchetes [])
        $id = $rolData['id'];
        $name = $rolData['name'];
        try {
            User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'activo' => true,
                'rol' => $name,
                'rol_id'  => $id
            ]);
            return redirect()->route('usuarios')->with('success', 'Usuario Guardado con Éxito!');
        } catch (Exception $e) {
            return redirect()->route('usuarios')->with('error', 'Error al guardar usuario: '. $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $items = User::find($id);
        $titulo = "Editar usuario";
        return view('module.usuarios.edit', compact('items', 'titulo'));

    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        try {
            $items = User::find($id);
            $items ->name = $request->name;
            $items ->email = $request->email;
            $items ->rol = $request->rol;
            $items->save();
            return redirect()->route('usuarios')->with('success', 'Usuario Actualizado con Exito!');
        } catch (Exception $e) {
            return redirect()->route('usuarios')->with('error', 'Error al actualizar Usiario!'. $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
    public function tbody()
    {
        $items = User::all();
        return view('modules.usuarios.tbody', compact('items'));
    }
    public function estado($id, $estado)
    {
        $usuario = User::find($id);
        if (!$usuario) {
            return 0;
        }
        $usuario->activo = $estado;
        if ($usuario->save()) {
            return 1;
        } else {
            return 0;
        }
    }
    public function cambio_password($id, $password)
    {
        $items = User::find($id);
        $items->password = Hash::make($password);
        return $items->save();
    }
}
