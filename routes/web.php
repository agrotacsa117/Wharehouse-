<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\Categorias;
use App\Http\Controllers\Dashboard;
use App\Http\Controllers\InventoryManagementController;
use App\Http\Controllers\LocationRegistrationController;
use App\Http\Controllers\MovementsController;
use App\Http\Controllers\OutputController;
use App\Http\Controllers\Productos;
use App\Http\Controllers\Proveedores;
use App\Http\Controllers\RackController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\Reportes_productos;
use App\Http\Controllers\Usuarios;
use App\Http\Controllers\Ventas;
use App\Http\Controllers\WarehouseManagmentController;
use App\Http\Controllers\WarehouseRegistrationController;
use App\Http\Controllers\WarehouseTypeController;
use App\Http\Controllers\WareouseInventoryController;
use Illuminate\Support\Facades\Route;

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

Route::post(
    '/register-warehouse',
    [WarehouseRegistrationController::class, 'registerWarehouse']
)->name('warehouses.store');

Route::post(
    '/register-location',
    [LocationRegistrationController::class, 'store']
)->name('locations.store');

Route::get(
    '/operation',
    [WareouseInventoryController::class, 'getView']
)->name('operation.get');

Route::post(
    '/operation',
    [WareouseInventoryController::class, 'store']
)->name('operation.get.store');

Route::get(
    '/inventory/search/{product}',
    [
        WareouseInventoryController::class,
        'saerchByProduct',
    ])->name('inventory.search.get');

Route::post(
    '/warehouse-type',
    [WarehouseTypeController::class, 'store']
)->name('warehouse-type.store');

Route::get(
    '/warehouse-managment',
    [WarehouseManagmentController::class, 'getView']
)->name('warehouse-managment.get');

Route::post(
    '/warehouse-movements/report-caducidad',
    [MovementsController::class, 'reportByCaducidad']
)->name('warehouse-movements.report-caducidad');

Route::post(
    '/warehouse-movements/movements/{reversalMovementId}/reason/{reason}',
    [MovementsController::class, 'reverseMovement']
)->name('warehouse-movements.reverse-movement');

Route::middleware('auth')->group(function () {
    Route::get(
        '/warehouse-movements',
        [MovementsController::class, 'getView']
    )->name('warehouse-movements.get');
    Route::get('/logout', [AuthController::class, 'logout'])->name('logout');
});

Route::post(
    '/warehouse-movements/report',
    [MovementsController::class, 'reportByPeriod']
)->name('warehouse-movements.report');

// expirationReport
Route::get(
    '/warehouse-movements/expiration-report',
    [MovementsController::class, 'expirationReport']
)->name('warehouse-movements.expiration-report');

Route::get('/', [AuthController::class, 'index'])->name('login');
Route::post('/logear', [AuthController::class, 'logear'])->name('logear');

Route::get(
    '/output',
    [OutputController::class,
        'getView']
)->name('output.get');

// Ruta para editar almacén
Route::put(
    '/warehouse-managment/{id}',
    [WarehouseManagmentController::class, 'update']
)->middleware('auth')->name('warehouse-managment.update');

Route::get(
    '/warehouses/by-location/{locationId}',
    [
        OutputController::class,
        'getWarehousesByLocation',
    ]
)->name(
    'warehouses.by-location'
);

Route::get('/output/{id}/inventory', [OutputController::class,
    'getInventory'])->name('output.inventory.get');

Route::post('/output/process', [
    OutputController::class,
    'processOutput'])->name('output.inventory.process');

Route::get('/reports/products/warehouse/{id}', [ReportController::class, 'getProductosByWarehouse'])
    ->name('reports.products.warehouse');

Route::get('/reports/products/warehouse/{id}/lots', [ReportController::class, 'getStocksByWarehouse'])
    ->name('reports.products.warehouse.lots');

Route::get(
    '/reports/filter',
    [ReportController::class,
        'getTransactionsByDateRange']
)->name('reports.filter.by.range');

Route::get(
    '/reports/products/stock/warehouses/{warehouseId}/products/{productId}',
    [ReportController::class, 'getStocksByProductAndWarehouse']
)->name('reports.filter.by.product.warehouse');

Route::middleware('auth')->group(function () {
    Route::get('/home', [Dashboard::class, 'index'])->name('home');
    Route::get('/logout', [AuthController::class, 'logout'])->name('logout');
});

Route::middleware('auth')->group(function () {
    Route::get('/reports', [ReportController::class, 'index'])->name('reports.get');
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

Route::prefix('managment')->middleware(['auth', 'role:admin'])->group(function () {
    Route::get(
        '/register-warehouse',
        [WarehouseRegistrationController::class, 'index']
    )->name('warehouses.create');

    Route::get(
        '/warehouse-type',
        [WarehouseTypeController::class, 'getView']
    )->name('warehouse-type.get');

    Route::get(
        '/inventory-management',
        [
            InventoryManagementController::class,
            'index']
    )->name('inventory.management');

    Route::get(
        '/register-location',
        [LocationRegistrationController::class, 'getView']
    )->name('location.store');
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
    Route::get('/', [RackController::class, 'index'])->name('rack.index');
    Route::get('/create', [RackController::class, 'create'])->name('rack.create');
    Route::post('/store', [RackController::class, 'store'])->name('rack.store');
    Route::get('/edit/{id}', [RackController::class, 'edit'])->name('rack.edit');
    Route::put('/update/{id}', [RackController::class, 'update'])->name('rack.update');
    Route::delete('/destroy/{id}', [RackController::class, 'destroy'])->name('rack.destroy');
});
Route::prefix('ventas')->middleware(['auth'])->group(function () {
    Route::get('/generar-ticket-salida', [Ventas::class, 'generarTicketSalida'])->name('ventas.generarTicketSalida');
});
Route::get('/salidas/ticket-grupal/{ticket_pdf}', [Ventas::class, 'mostrarTicketGrupal'])
    ->where('ticket_pdf', '.*')
    ->name('salidas.ticket_grupal');
Route::post('/salida-productos/actualizarGlobal', [Ventas::class, 'actualizarGlobal'])->name('salida-productos.actualizarGlobal');

Route::middleware(['auth'])->group(function () {
    Route::get('/inventory-management', [InventoryManagementController::class, 'index'])->name('inventory.management');
    Route::post('/inventory-management/update', [InventoryManagementController::class, 'update'])->name('inventory.update');
    Route::post('/inventory-management/transfer', [InventoryManagementController::class, 'transfer'])->name('inventory.transfer');
});

Route::post(
    '/reports/products/lots/export/pdf',
    [ReportController::class, 'exportProductLotsPdf']
)->name('reports.products.lots.export.pdf');

Route::post(
    '/reports/products/lots/export/excel',
    [ReportController::class, 'exportProductLotsExcel']
)->name('reports.products.lots.export.excel');

Route::post(
    '/reports/products/lots/export/pdf',
    [ReportController::class, 'exportProductLotsPdf']
)->name('reports.products.lots.export.pdf');

Route::post(
    '/reports/products/physical-count/export/excel',
    [ReportController::class, 'exportPhysicalCountExcel']
)->name('reports.products.physicalcount.export.excel');

Route::post(
    '/reports/products/physical-count/export/pdf',
    [ReportController::class, 'exportPhysicalCountPdf']
)->name('reports.products.physicalcount.export.pdf');
