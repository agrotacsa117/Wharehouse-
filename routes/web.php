<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\Categorias;
use App\Http\Controllers\Usuarios;
use App\Http\Controllers\Productos;
use App\Http\Controllers\Dashboard;
use App\Http\Controllers\Proveedores;
use App\Http\Controllers\Reportes_productos;
use App\Http\Controllers\Ventas;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\WarehouseRegistrationController;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/
// usuario admin solo una vez
Route::get('/crear-admin', [AuthController::class, 'crearAdmin']);
Route::get(
    '/register-warehouse',
    [WarehouseRegistrationController::class, 'index']
)->name('warehouses.create');

Route::post(
    '/register-warehouse',
    [WarehouseRegistrationController::class, 'registerWarehouse']
)->name('warehouses.store');

Route::get('/', [AuthController::class, 'index'])->name('login');
Route::post('/logear', [AuthController::class, 'logear'])->name('logear');

Route::middleware("auth")->group(function () {
    Route::get('/home', [Dashboard::class, 'index'])->name('home');
    Route::get('/logout', [AuthController::class, 'logout'])->name('logout');
});
Route::prefix('categorias')->middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/', [Categorias::class, 'index'])->name('categorias.index');
    Route::get('/create', [Categorias::class, 'create'])->name('categorias.create');
    Route::post('/store', [Categorias::class, 'store'])->name('categorias.store');
    Route::get('/show/{id}', [Categorias::class, 'show'])->name('categorias.show');
    Route::delete('/destroy/{id}', [Categorias::class, 'destroy'])->name('categorias.destroy');
    Route::get('/edit/{id}', [Categorias::class, 'edit'])->name('categorias.edit');
    Route::put('/update/{id}', [Categorias::class, 'update'])->name('categorias.update');
});
Route::prefix('reportes_productos')->middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/', [Reportes_productos::class, 'index'])->name('reportes_productos');
});
Route::prefix('productos')->middleware(['auth'])->group(function () {
    Route::get('/', [Productos::class, 'index'])->name(('productos'));
    Route::get('/create', [Productos::class, 'create'])->name(('productos.create'));
    Route::post('/store', [Productos::class, 'store'])->name('productos.store');
    Route::get('/edit/{id}', [Productos::class, 'edit'])->name('productos.edit');
    Route::put('/update/{id}', [Productos::class, 'update'])->name('productos.update');
    Route::get('/show/{id}', [Productos::class, 'show'])->name('productos.show');
    Route::delete('/destroy/{id}', [Productos::class, 'destroy'])->name('productos.destroy');
    Route::get('/cambiar-estado/{id}/{estado}', [Productos::class, 'estado'])->name('productos.estado');
    Route::get('/productos/vencer', [Productos::class, 'vencer'])->name('productos.vencer');
});
Route::prefix('proveedores')->middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/', [Proveedores::class, 'index'])->name(('proveedores'));
    Route::get('/create', [Proveedores::class, 'create'])->name(('proveedores.create'));
    Route::post('/store', [Proveedores::class, 'store'])->name('proveedores.store');
    Route::get('/edit/{id}', [Proveedores::class, 'edit'])->name('proveedores.edit');
    Route::put('/update/{id}', [Proveedores::class, 'update'])->name('proveedores.update');
    Route::get('/show/{id}', [Proveedores::class, 'show'])->name('proveedores.show');
    Route::delete('/destroy/{id}', [Proveedores::class, 'destroy'])->name('proveedores.destroy');
});
Route::prefix('usuarios')->middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/', [Usuarios::class, 'index'])->name(('usuarios'));
    Route::get('/create', [Usuarios::class, 'create'])->name(('usuarios.create'));
    Route::post('/store', [Usuarios::class, 'store'])->name('usuarios.store');
    Route::get('/edit/{id}', [Usuarios::class, 'edit'])->name('usuarios.edit');
    Route::put('/update/{id}', [Usuarios::class, 'update'])->name('usuarios.update');
    Route::get('/tbody', [Usuarios::class, 'tbody'])->name(('usuarios.tbody'));
    Route::get('/cambiar-estado/{id}/{estado}', [Usuarios::class, 'estado'])->name('usuarios.estado');
    Route::get('/cambiar-password/{id}/{password}', [Usuarios::class, 'cambio_password'])->name('usuarios.password');
});
Route::prefix('salida-productos')->middleware(['auth'])->group(function () {
    Route::get('/', [Ventas::class, 'index'])->name('salida-productos');
    Route::post('/agregar/{id}', [Ventas::class, 'agregarSalida'])->name('salida-productos.agregar');
    Route::post('/actualizar', [Ventas::class, 'actualizarSalida'])->name('salida-productos.actualizar');
    Route::post('/eliminar/{id}', [Ventas::class, 'eliminarSalida'])->name('salida-productos.eliminar');
    Route::get('/reporte', [Ventas::class, 'reporteSalidas'])->name('salida-productos.reporte');
    Route::get('/comprobante/{id}', [Ventas::class, 'comprobanteSalida'])->name('salida-productos.comprobante');
    Route::get('/salida-productos/ticket-carrito', [Ventas::class, 'ticketCarrito'])->name('salida-productos.ticket-carrito');
});
Route::post('/salida-productos/finalizar', [Ventas::class, 'finalizarSalida'])->name('salida-productos.finalizar');
Route::get('/reporte-salidas', [Ventas::class, 'reporteSalidas'])->name('reporte.salidas');
Route::delete('/salida-productos/{id}', [Ventas::class, 'destroySalida'])->name('salida-productos.destroy');
Route::prefix('racks')->middleware(['auth'])->group(function () {
    Route::get('/', [\App\Http\Controllers\RackController::class, 'index'])->name('rack.index');
    Route::get('/create', [\App\Http\Controllers\RackController::class, 'create'])->name('rack.create');
    Route::post('/store', [\App\Http\Controllers\RackController::class, 'store'])->name('rack.store');
    Route::get('/edit/{id}', [\App\Http\Controllers\RackController::class, 'edit'])->name('rack.edit');
    Route::put('/update/{id}', [\App\Http\Controllers\RackController::class, 'update'])->name('rack.update');
    Route::delete('/destroy/{id}', [\App\Http\Controllers\RackController::class, 'destroy'])->name('rack.destroy');
});
Route::prefix('ventas')->middleware(['auth'])->group(function () {
    Route::get('/generar-ticket-salida', [Ventas::class, 'generarTicketSalida'])->name('ventas.generarTicketSalida');
});
Route::get('/salidas/ticket-grupal/{ticket_pdf}', [Ventas::class, 'mostrarTicketGrupal'])
    ->where('ticket_pdf', '.*')
    ->name('salidas.ticket_grupal');
Route::post('/salida-productos/actualizarGlobal', [Ventas::class, 'actualizarGlobal'])->name('salida-productos.actualizarGlobal');
