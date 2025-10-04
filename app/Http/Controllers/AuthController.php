<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Contracts\AuthServiceInterface;

class AuthController extends Controller
{
    private AuthServiceInterface $authServiceInterface;

    public function __construct(AuthServiceInterface $authServiceInterface)
    {
        $this->authServiceInterface = $authServiceInterface;
    }
    
    public function index()
    {
        $titulo = "login de usuarios";
        return view("module.auth.login", compact("titulo"));
    }

    public function logear(Request $request)
    {
        // validar datos de las credenciales
        $credenciales = $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]); 
        //buscar el email
        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return back()->withErrors(['email' => 'Credencial incorrecta'])->withInput();
        }
        //ver si el usuario este activo
        if (!$user->activo) {
            return back()->withErrors(['email' => 'Tu cuenta esta inactiva!']);
        }
        // crear la sesion de usuario
        Auth::login($user);
        $request->session()->regenerate();

        return redirect()->route('home');
    }
    public function crearAdmin()
    {
        // crear directamente admin
        User::create([
            'name' => 'rafael',
            'email' => 'admin@admin.com',
            'password' => Hash::make('admin'),
            'activo' => true,
            'rol' => 'admin'
        ]);
        return "Admin creado con exito";
    }
    public function logout()
    {
        // Limpiar el carrito de salidas de la sesión si existe
        session()->forget('salidas');
        Auth::logout();
        return redirect()->route('login');
    }
}
