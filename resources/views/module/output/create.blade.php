<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TACSA - Gestión de Salidas</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --tacsa-red: #DC2626;
            --tacsa-red-dark: #B91C1C;
            --tacsa-red-light: rgba(220, 38, 38, 0.08);
            --tacsa-red-light2: rgba(220, 38, 38, 0.15);
            --tacsa-green: #16a34a;
            --tacsa-green-light: rgba(22, 163, 74, 0.1);
            --text-primary: #1a1a1a;
            --text-secondary: #6b7280;
            --border-color: #e5e7eb;
            --bg-body: #f8fafc;
            --bg-card: #ffffff;
        }

        body {
            font-family: 'Inter', sans-serif;
            background-color: var(--bg-body);
            color: var(--text-primary);
            padding-bottom: 5rem;
        }

        /* TOPBAR */
        .topbar {
            background: var(--tacsa-red);
            padding: 0.75rem 2rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .topbar-brand {
            color: #fff;
            font-weight: 700;
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        /* STEP WIZARD */
        .step-wizard {
            display: flex;
            background: #fff;
            border: 1px solid var(--border-color);
            border-radius: 12px;
            margin: 2rem 0;
            overflow: hidden;
        }

        .step-item {
            flex: 1;
            padding: 1.25rem;
            display: flex;
            align-items: center;
            gap: 1rem;
            border-right: 1px solid var(--border-color);
            opacity: 0.5;
            transition: all 0.3s;
        }

        .step-item.active {
            opacity: 1;
            background: var(--tacsa-red-light);
        }

        .step-item.done {
            opacity: 1;
        }

        .step-item.done i {
            color: var(--tacsa-green);
        }

        .step-num {
            width: 30px;
            height: 30px;
            border-radius: 50%;
            border: 2px solid currentColor;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
        }

        /* CARDS */
        .tacsa-card {
            background: #fff;
            border: 1px solid var(--border-color);
            border-radius: 12px;
            padding: 1.5rem;
            margin-bottom: 1.5rem;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
        }

        .section-header {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            margin-bottom: 1.5rem;
            border-left: 4px solid var(--tacsa-red);
            padding-left: 1rem;
        }

        /* GRID SELECCION ALMACEN */
        .warehouse-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 1.25rem;
        }

        .warehouse-btn {
            border: 2px solid var(--border-color);
            border-radius: 10px;
            padding: 1.5rem;
            cursor: pointer;
            transition: all 0.2s;
            background: #fff;
            text-align: left;
        }

        .warehouse-btn:hover {
            border-color: var(--tacsa-red);
            background: var(--tacsa-red-light);
        }

        .warehouse-btn.selected {
            border-color: var(--tacsa-red);
            background: var(--tacsa-red-light2);
        }

        /* TABLAS */
        .tacsa-table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
        }

        .tacsa-table th {
            background: #f9fafb;
            padding: 1rem;
            font-size: 0.75rem;
            text-transform: uppercase;
            color: var(--text-secondary);
            border-bottom: 1px solid var(--border-color);
        }

        .tacsa-table td {
            padding: 1rem;
            border-bottom: 1px solid #f3f4f6;
            font-size: 0.875rem;
        }

        /* UI ELEMENTS */
        .btn-tacsa {
            background: var(--tacsa-red);
            color: #fff;
            border: none;
            padding: 0.6rem 1.5rem;
            border-radius: 8px;
            font-weight: 600;
            transition: 0.2s;
        }

        .btn-tacsa:hover {
            background: var(--tacsa-red-dark);
            color: #fff;
        }

        .btn-tacsa:disabled {
            background: #d1d5db;
        }

        .btn-outline {
            border: 1px solid var(--border-color);
            background: #fff;
            padding: 0.5rem 1rem;
            border-radius: 8px;
            transition: 0.2s;
        }

        .btn-outline:hover {
            background: #f9fafb;
        }

        /* PANEL DE DETALLE INTEGRADO */
        #detail-panel {
            background: #f1f5f9;
            border-radius: 8px;
            padding: 1.5rem;
            margin-top: 1rem;
            display: none;
            border: 1px solid #cbd5e1;
        }

        .badge-info {
            background: #e2e8f0;
            color: #475569;
            padding: 0.2rem 0.6rem;
            border-radius: 6px;
            font-size: 0.75rem;
            font-weight: 600;
        }

        .toast-container {
            position: fixed;
            bottom: 2rem;
            right: 2rem;
            z-index: 9999;
        }

        .topbar-nav {
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .topbar-nav a {
            color: rgba(255, 255, 255, 0.8);
            text-decoration: none;
            font-size: 0.8125rem;
            font-weight: 500;
            padding: 0.375rem 0.75rem;
            border-radius: 6px;
            transition: all 0.15s;
        }

        .topbar-nav a:hover,
        .topbar-nav a.active {
            color: #ffffff;
            background: rgba(255, 255, 255, 0.15);
        }

        /* CARRITO MULTI-PRODUCTO */
        .cart-empty {
            text-align: center;
            padding: 2rem 1rem;
            color: var(--text-secondary);
            font-size: 0.875rem;
        }

        .cart-item {
            border: 1px solid var(--border-color);
            border-radius: 8px;
            padding: 0.75rem;
            margin-bottom: 0.75rem;
        }

        .cart-item-flash {
            border-color: var(--tacsa-red);
            background: var(--tacsa-red-light);
        }

        .cart-qty-input:invalid,
        .cart-qty-input.is-invalid {
            border-color: #dc2626;
        }

        .btn-outline.confirming {
            background: var(--tacsa-red);
            border-color: var(--tacsa-red);
            color: #fff;
        }

        #btn-confirm-cart:disabled {
            background: #d1d5db;
            cursor: not-allowed;
        }
    </style>
</head>

<body>

    <nav class="topbar">
        <a href="#" class="topbar-brand">
            <i class="bi bi-box-seam"></i> TACSA INVENTARIOS
        </a>
        <div class="topbar-nav">
            <a href="{{ route('home') }}">Inicio</a>
            <a href="{{ route('warehouse-movements.get') }}" class="active">Movimientos</a>
        </div>

    </nav>

    <div class="container mt-4">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                @if (session('success'))
                    <div class="alert alert-success">
                        {{ session('success') }}
                    </div>
                @endif
                <h1 class="h3 fw-bold mb-1">Salida de las Bodegas</h1>
                <p class="text-secondary small">Gestione el retiro de materiales sin ventanas emergentes.</p>
            </div>
        </div>

        <div class="step-wizard">
            <div class="step-item active" id="step1-indicator">
                <div class="step-num">1</div>
                <div><span class="d-block small">Origen</span><strong>Bodega</strong></div>
            </div>
            <div class="step-item" id="step2-indicator">
                <div class="step-num">2</div>
                <div><span class="d-block small">Selección</span><strong>Producto</strong></div>
            </div>
            <div class="step-item" id="step3-indicator">
                <div class="step-num">3</div>
                <div><span class="d-block small">Registro</span><strong>Confirmar</strong></div>
            </div>
        </div>

        <div id="panel-step1" class="tacsa-card">
            <div class="section-header">
                <h2 class="h5 m-0">Seleccione la bodega de origen</h2>
            </div>

            <div class="warehouse-grid">
                @foreach ($warehousesWithLocationsDTO as $warehouse)
                    <div class="warehouse-btn"
                        onclick='selectWarehouse(
                    {{ $warehouse->getId() }},
                    @json($warehouse->getWarehouseName() . ' - ' . $warehouse->getHeadquartersName()),
                     {{ $warehouse->getLocationId() ?? 'null' }}
                )'>

                        <i class="bi bi-building h3 text-danger"></i>

                        <h3 class="h6 mt-2 mb-1">
                            {{ $warehouse->getWarehouseName() }}
                        </h3>

                        <p class="small text-secondary m-0">
                            {{ $warehouse->getHeadquartersName() }}
                        </p>
                    </div>
                @endforeach
            </div>
        </div>

        <div id="panel-step2" style="display:none;">
            <div class="row g-3">
                <div class="col-md-8">
                    <div class="tacsa-card h-100">
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <div class="section-header m-0">
                                <h2 class="h5 m-0">Productos en <span id="current-wh-name" class="text-danger"></span></h2>
                            </div>
                            <button class="btn-outline btn-sm" onclick="changeWarehouse(this)"><i class="bi bi-arrow-left"></i> Cambiar
                                Bodega</button>
                        </div>

                        <div class="table-responsive">
                            <table class="tacsa-table">
                                <thead>
                                    <tr>
                                        <th>Código</th>
                                        <th>Descripción</th>
                                        <th>Ubicación</th>
                                        <th>Stock</th>
                                        <th>Fecha de caducidad</th>
                                        <th>Acción</th>
                                    </tr>
                                </thead>
                                <tbody id="inventory-body">
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="tacsa-card h-100">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h2 class="h5 m-0">Lote de salida</h2>
                            <span class="badge-info" id="cart-count">0</span>
                        </div>

                        <div id="cart-empty" class="cart-empty">
                            Selecciona un producto de la tabla para agregarlo aquí.
                        </div>

                        <div id="cart-items"></div>

                        <button type="button" class="btn-tacsa w-100 mt-2" id="btn-confirm-cart" disabled
                            onclick="confirmCart()">Confirmar (<span id="cart-confirm-count">0</span>) →</button>
                    </div>
                </div>
            </div>
        </div>

        <div id="panel-step3" style="display:none;">
            <div class="row">
                <div class="col-md-4">
                    <div class="tacsa-card bg-light" id="summary-single">
                        <h3 class="h6 fw-bold border-bottom pb-2 mb-3">Resumen de Selección</h3>
                        <div class="mb-3">
                            <label class="d-block small text-secondary">Código:</label>
                            <strong id="form-prod-code">---</strong>
                        </div>
                        <div class="mb-3">
                            <label class="d-block small text-secondary">Producto:</label>
                            <strong id="form-prod-name">---</strong>
                        </div>
                        <div class="mb-3">
                            <label class="d-block small text-secondary">Lote actual:</label>
                            <span class="badge-info" id="form-prod-lote">---</span>
                        </div>
                        <div class="mb-3">
                            <label class="d-block small text-secondary">Fecha de caducidad:</label>
                            <span class="badge-info" id="form-prod-expiration-date">---</span>
                        </div>
                        <div class="mb-3">
                            <label class="d-block small text-secondary">Stock disponible:</label>
                            <strong class="text-success h5" id="form-prod-stock">0</strong>
                        </div>
                    </div>

                    <div class="tacsa-card bg-light" id="summary-cart" style="display:none;">
                        <h3 class="h6 fw-bold border-bottom pb-2 mb-3">Resumen del Lote de Salida
                            (<span id="cart-summary-count">0</span>)</h3>
                        <div id="cart-summary-list"></div>
                    </div>
                </div>
                <div class="col-md-8">
                    <div class="tacsa-card">
                        <div class="section-header">
                            <h2 class="h5 m-0">Datos de la Salida</h2>
                        </div>
                        <form method="POST" id="formSalida" action="{{ route('output.inventory.process') }}">
                            @csrf

                            <input type="hidden" name="warehouseInventoryId" id="warehouseInventoryId">
                            <input type="hidden" name="warehouse_id" id="warehouseId">

                            <div class="row g-3">

                                <div class="row g-3">
                                    <div class="col-12">
                                        <label class="form-label small fw-bold">Tipo de Movimiento</label>
                                        <select name="movement_type" id="movementType" class="form-select">
                                            <option value="OUT">Salida Simple</option>
                                            <option value="SALE">Venta</option>
                                            <option value="TRANSFER">Traslado</option>
                                            <option value="RELOCATION">Reubicación de Bodega</option>
                                            <option value="LOCATION_UPDATE">Reubicación Interna</option>
                                        </select>
                                    </div>

                                    <div class="col-md-6" id="field-quantity">
                                        <label class="form-label small fw-bold">Cantidad</label>
                                        <input type="number" name="quantity" id="input-quantity"
                                            class="form-control" required min="1">
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label small fw-bold">Fecha de Operación</label>
                                        <input type="date" name="operation_date" id="input-operation-date"
                                            class="form-control">
                                    </div>

                                    <!-- Campos para VENTA -->
                                    <div id="fields-sale" class="d-none col-12">
                                        <div class="alert alert-info">
                                            <i class="bi bi-info-circle"></i> Datos de Venta
                                        </div>
                                        <div class="row g-3">

                                            <div class="col-md-6">
                                                <label class="form-label small fw-bold">Factura SAP</label>
                                                <input type="text" name="invoice_sap" id="input-invoice-sap"
                                                    class="form-control" placeholder="FAC-000001">
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Campos para TRASLADO -->
                                    <div id="fields-transfer" class="d-none col-12">
                                        <div class="alert alert-warning">
                                            <i class="bi bi-truck"></i> Traslado entre Sucursales
                                        </div>
                                        <div class="row g-3">
                                            <div class="col-12">
                                                <label class="form-label small fw-bold">Sucursal *</label>
                                                <select name="destination_warehouse" id="input-destination-warehouse"
                                                    class="form-select">
                                                    <option value="">Seleccione sucursal destino...</option>
                                                    <option value="mapastepec">Mapastepec</option>
                                                    <option value="tuxtla">Tuxtla</option>
                                                    <option value="cardenas">Cárdenas</option>
                                                    <option value="zapata">Zapata</option>
                                                    <option value="campeche">Campeche</option>
                                                    <option value="Mostrador Matriz">Mostrador Matriz</option>
                                                    <option value="Agrotacsa Tapachula">Agrotacsa Tapachula</option>
                                                </select>
                                            </div>


                                            <div class="col-md-6">
                                                <label class="form-label small fw-bold">Folio Traslado *</label>
                                                <input type="number" name="folio_transfer" id="input-folio-transfer"
                                                    class="form-control" min="1" placeholder="Ej: 1">
                                            </div>


                                        </div>
                                    </div>

                                    <!-- Campos para REUBICACIÓN -->
                                    <div id="fields-relocation" class="d-none col-12">
                                        <div class="alert alert-primary">
                                            <i class="bi bi-arrows-move"></i> Reubicación Interna
                                        </div>

                                        <div class="row g-3">
                                            <div class="col-12">
                                                <label class="form-label small fw-bold">Bodega Destino *</label>
                                                <select name="destination_warehouse_id"
                                                    id="input-relocation-destination" class="form-select">
                                                    <option value="">Seleccione bodega destino...</option>
                                                </select>
                                                <small class="text-muted">Solo bodegas del mismo location</small>
                                            </div>

                                            <!-- Campos para REUBICACIÓN — agregar al final del div row g-3 existente -->
                                            <div class="col-12 mt-2">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox"
                                                        id="checkRehabilitar" onchange="toggleRehabilitar()">
                                                    <label class="form-check-label fw-bold" for="checkRehabilitar">
                                                        <i class="bi bi-arrow-clockwise me-1 text-warning"></i>
                                                        ¿Rehabilitar producto? (actualizar fecha)
                                                    </label>
                                                </div>
                                            </div>

                                            <!-- Campos de rehabilitación — ocultos por defecto -->
                                            <div id="fields-rehabilitar" class="col-12 d-none">
                                                <div class="p-3 mt-2"
                                                    style="background:#fffbeb; border-radius:8px; border:1px solid #f59e0b;">
                                                    <p class="small fw-bold mb-3" style="color:#92400e;">
                                                        <i class="bi bi-exclamation-triangle me-1"></i>
                                                        Rehabilitación — se actualizará la fecha del lote
                                                    </p>
                                                    <div class="row g-3">
                                                        
                                                        <div class="col-md-6">
                                                            <label class="form-label small fw-bold">
                                                                Fecha de manufactura <span
                                                                    class="text-muted fw-normal">(opcional)</span>
                                                            </label>
                                                            <input type="date" name="new_manufacturing_date"
                                                                id="input-new-manufacturing-date"
                                                                class="form-control">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <label class="form-label small fw-bold">Nuevo Rack *</label>
                                                <input type="text" name="new_rack" id="input-new-rack"
                                                    class="form-control" placeholder="Ej: A-01">
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label small fw-bold">Nuevo Nivel *</label>
                                                <input type="number" name="new_level" id="input-new-level"
                                                    class="form-control" min="1" placeholder="Ej: 1">
                                            </div>

                                            <div class="col-md-6">
                                                <label class="form-label small fw-bold">Modulo *</label>
                                                <input type="number" name="module" id="module"
                                                    class="form-control" min="1" placeholder="Ej: 1">
                                            </div>

                                            <div class="col-md-6">
                                                <label class="form-label small fw-bold">Bahía *</label>
                                                <input type="number" name="bay" id="bay"
                                                    class="form-control" min="1" placeholder="Ej: 1">
                                            </div>

                                            <div class="col-md-6">
                                                <label class="form-label small fw-bold">Taríma *</label>
                                                <input type="number" name="platform" id="platform"
                                                    class="form-control" min="1" placeholder="Ej: 1">
                                            </div>


                                        </div>
                                    </div>

                                    <!-- Campos para ACTUALIZAR UBICACIÓN (misma bodega) ✅ NUEVO -->
                                    <div id="fields-location-update" class="d-none col-12">
                                        <div class="alert alert-primary">
                                            <i class="bi bi-pin-map"></i> Actualizar ubicación del lote en la misma
                                            bodega
                                        </div>

                                        <div class="d-flex justify-content-between align-items-center mb-3">
                                            <strong class="small" id="location-update-position">Producto 1 de 1</strong>
                                            <div class="btn-group btn-group-sm">
                                                <button type="button" class="btn btn-outline-secondary" id="btn-location-prev"
                                                    onclick="goToLocationUpdateItem(-1)">&larr; Anterior</button>
                                                <button type="button" class="btn btn-outline-secondary" id="btn-location-next"
                                                    onclick="goToLocationUpdateItem(1)">Siguiente &rarr;</button>
                                            </div>
                                        </div>

                                        {{-- Ubicación actual (referencia) --}}
                                        <div class="mb-3 p-3"
                                            style="background:#f8fafc; border-radius:8px; border:1px solid #e2e8f0;">
                                            <p class="small fw-bold text-secondary mb-2">
                                                <i class="bi bi-geo-alt me-1"></i>Ubicación actual del lote:
                                            </p>
                                            <div class="d-flex gap-3 flex-wrap">
                                                <span class="badge-info">Rack: <strong
                                                        id="current-rack">-</strong></span>
                                                <span class="badge-info">Nivel: <strong
                                                        id="current-level">-</strong></span>
                                                <span class="badge-info">Módulo: <strong
                                                        id="current-module">-</strong></span>
                                                <span class="badge-info">Bahía: <strong
                                                        id="current-bay">-</strong></span>
                                                <span class="badge-info">Tarima: <strong
                                                        id="current-platform">-</strong></span>
                                            </div>
                                        </div>

                                        {{-- Nueva ubicación (por producto — ver navegador arriba) --}}
                                        <p class="small fw-bold text-secondary mb-2">Nueva ubicación:</p>
                                        <div class="row g-3">
                                            <div class="col-md-6">
                                                <label class="form-label small fw-bold">Nuevo Rack</label>
                                                <input type="text" id="input-lu-rack"
                                                    class="form-control" placeholder="Ej: A-01"
                                                    onchange="saveCurrentLocationUpdateItem()">
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label small fw-bold">Nuevo Nivel</label>
                                                <input type="number" id="input-lu-level"
                                                    class="form-control" min="1" placeholder="Ej: 1"
                                                    onchange="saveCurrentLocationUpdateItem()">
                                            </div>
                                            <div class="col-md-4">
                                                <label class="form-label small fw-bold">Módulo</label>
                                                <input type="number" id="input-lu-module"
                                                    class="form-control" min="1" placeholder="Ej: 1"
                                                    onchange="saveCurrentLocationUpdateItem()">
                                            </div>
                                            <div class="col-md-4">
                                                <label class="form-label small fw-bold">Bahía</label>
                                                <input type="number" id="input-lu-bay"
                                                    class="form-control" min="1" placeholder="Ej: 1"
                                                    onchange="saveCurrentLocationUpdateItem()">
                                            </div>
                                            <div class="col-md-4">
                                                <label class="form-label small fw-bold">Tarima</label>
                                                <input type="number" id="input-lu-platform"
                                                    class="form-control" min="1" placeholder="Ej: 1"
                                                    onchange="saveCurrentLocationUpdateItem()">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-12">
                                        <label class="form-label small fw-bold" for="input-reason">Motivo /
                                            Observaciones</label>
                                        <input type="text" name="reason" id="input-reason" class="form-control"
                                            placeholder="Descripción del movimiento...">
                                    </div>

                                    <div class="col-12 d-flex gap-2 justify-content-end mt-4">
                                        <button type="button" class="btn-outline"
                                            onclick="showStep(2)">Cancelar</button>
                                        <button type="submit" class="btn-tacsa" id="btn-submit">Registrar
                                            Salida</button>
                                    </div>
                                </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <div class="mt-5">
            <h3 class="h5 fw-bold mb-3"><i class="bi bi-clock-history"></i> Movimientos Recientes</h3>
            <div class="tacsa-card p-0 overflow-hidden">
                <table class="tacsa-table m-0">
                    <thead class="bg-light">
                        <tr>
                            <th>Folio</th>
                            <th>Fecha</th>
                            <th>Producto</th>
                            <th>Cant.</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody id="history-body">
                        <tr>
                            <td colspan="5" class="text-center py-4 text-secondary">No hay salidas registradas en
                                esta sesión.</td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div id="detail-panel">
                <div class="d-flex justify-content-between align-items-start mb-3">
                    <h4 class="h6 fw-bold m-0 text-uppercase text-secondary">Detalle de Movimiento</h4>
                    <button class="btn-close btn-sm"
                        onclick="document.getElementById('detail-panel').style.display='none'"></button>
                </div>
                <div id="detail-content" class="row small">
                </div>
            </div>
        </div>
    </div>

    <div class="toast-container" id="toast-area"></div>

    <script>
        const selectMovementType = document.getElementById(
            "movementType");


        selectMovementType.addEventListener("change", function(event) {
            toggleMovementFields();
        });

        document.getElementById('formSalida').addEventListener('submit', function(event) {
            if (mode === 'cart') {
                event.preventDefault();
                submitCart();
            }
        });


        function toggleMovementFields() {
            const movementType = document.getElementById('movementType').value;
            const fieldsSale = document.getElementById('fields-sale');
            const fieldsTransfer = document.getElementById('fields-transfer');
            const fieldsRelocation = document.getElementById('fields-relocation');
            const fieldsLocationUpdate = document.getElementById('fields-location-update');
            const btnSubmit = document.getElementById('btn-submit');

            // Hide all fields first
            fieldsSale.classList.add('d-none');
            fieldsTransfer.classList.add('d-none');
            fieldsRelocation.classList.add('d-none');
            fieldsLocationUpdate.classList.add('d-none');

            // Reset required attributes
            document.getElementById('input-destination-warehouse').required = false;
            document.getElementById('input-relocation-destination').required = false;
            // Show relevant fields and update button text

            // Reset cantidad
            document.getElementById('input-quantity').disabled = false;
            document.getElementById('input-quantity').value = '';
            switch (movementType) {
                case 'SALE':
                    fieldsSale.classList.remove('d-none');
                    document.getElementById('input-quantity').disabled = false;
                    btnSubmit.textContent = 'Registrar Venta';
                    break;
                case 'TRANSFER':
                    fieldsTransfer.style.display = 'block';
                    document.getElementById('input-quantity').disabled = false;
                    fieldsTransfer.classList.remove('d-none');
                    //document.getElementById('input-destination-warehouse').required = true;
                    btnSubmit.textContent = 'Crear Traslado';
                    break;
                case 'RELOCATION':
                    fieldsRelocation.classList.remove('d-none');
                    document.getElementById('input-quantity').disabled = false;
                    document.getElementById('input-relocation-destination').required = true;
                    document.getElementById('input-new-rack').required = true;
                    document.getElementById('input-new-level').required = true;
                    document.getElementById('module').required = true;
                    document.getElementById('bay').required = true;
                    document.getElementById('platform').required = true;

                    btnSubmit.textContent = 'Registrar Reubicación';
                    loadRelocationWarehouses();
                    break;

                case 'LOCATION_UPDATE': // ✅ NUEVO
                    fieldsLocationUpdate.classList.remove('d-none');
                    document.getElementById('input-quantity').disabled = false;
                    document.getElementById('input-lu-module').required = true;
                    document.getElementById('input-lu-bay').required = true;
                    document.getElementById('input-lu-platform').required = true;
                    locationUpdateIndex = 0;
                    renderLocationUpdateItem();
                    btnSubmit.textContent = 'Actualizar Ubicación';
                    break;
                default:
                    btnSubmit.textContent = 'Registrar Salida';
            }
        }

        function loadRelocationWarehouses() {
            const select = document.getElementById('input-relocation-destination');
            select.innerHTML = '<option value="">Cargando...</option>';

            if (!state.warehouseLocationId) {
                select.innerHTML = '<option value="">Seleccione una bodega primero</option>';
                return;
            }


            fetch('/warehouses/by-location/' + state.warehouseLocationId)
                .then(response => response.json())
                .then(warehouses => {
                    // Filtrar excluyendo el warehouse origen
                    const filtered = warehouses.filter(wh => wh.id !== state.warehouseId);

                    if (filtered.length === 0) {
                        select.innerHTML = '<option value="">No hay otras bodegas en este location</option>';
                        return;
                    }

                    select.innerHTML = '<option value="">Seleccione bodega destino...</option>';
                    filtered.forEach(function(wh) {
                        const option = document.createElement('option');
                        option.value = wh.id;
                        option.textContent = wh.warehouseName;
                        select.appendChild(option);
                    });
                })
                .catch(error => {
                    console.error('Error cargando warehouses:', error);
                    select.innerHTML = '<option value="">Error al cargar</option>';
                });
        }
        /**
                document.getElementById('btn-submit').addEventListener('click', function(event) {
                    event.preventDefault();

                    alert("Less aura!");
                });
        **/
        // Datos de ejemplo
        const db = {
            13: [{
                    id: 101,
                    code: 'T-900',
                    desc: 'Tacsa Power 50kg',
                    stock: 150,
                    lote: 'L-2026-A'
                },
                {
                    id: 102,
                    code: 'A-100',
                    desc: 'Anibac Plus 20L',
                    stock: 45,
                    lote: 'L-2025-B'
                }
            ],
            14: [{
                id: 201,
                code: 'F-500',
                desc: 'Fertimex Premium',
                stock: 80,
                lote: 'L-NORTH-1'
            }]
        };

        let state = {
            warehouseId: null,
            product: null,
            history: []
        };

        function showStep(n) {
            document.getElementById('panel-step1').style.display = n === 1 ? 'block' : 'none';
            document.getElementById('panel-step2').style.display = n === 2 ? 'block' : 'none';
            document.getElementById('panel-step3').style.display = n === 3 ? 'block' : 'none';

            // Actualizar indicadores
            document.querySelectorAll('.step-item').forEach((el, idx) => {
                el.classList.remove('active', 'done');
                if (idx + 1 === n) el.classList.add('active');
                if (idx + 1 < n) el.classList.add('done');
            });
        }

        function selectWarehouse(id, name, locationId) {
            state.warehouseId = id;
            state.warehouseLocationId = locationId;
            document.getElementById('current-wh-name').innerText = name;
            renderInventory(id);
            showStep(2);
        }

        function renderInventory(whId) {
            const container = document.getElementById('inventory-body');

            fetch(`/output/${whId}/inventory`)
                .then(response => {
                    if (!response.ok) throw new Error('HTTP ' + response.status);
                    return response.json();
                })
                .then(items => {
                    container.innerHTML = items.map(item => `
                <tr>
                    <td><span class="badge-info">${item.productCode}</span></td>
                    <td><strong>${item.productName}</strong></td>
                    <td>${`R:${item.rack ?? 'SR'} / N:${item.level ?? 'SN'} / M:${item.module ?? 'SM'} / B:${item.bay ?? 'SB'} / T:${item.platform ?? 'ST'}`
        }</td>
                    <td class="fw-bold">${item.quantity}</td>
                    <td class="fw-bold">${item.expirationDate.split(' ')[0]}</td>
                    <td>
                        <button class="btn btn-sm btn-outline-danger" onclick='addToCart(${JSON.stringify(item)})'>
                            Seleccionar
                        </button>
                    </td>
                </tr>
            `).join('');
                })
                .catch(error => {
                    console.error('Error cargando inventario:', error);
                    alert(`Error cargando inventario:\n\nMensaje: ${error.message}\n\nDetalles:\n${error.stack}`);
                    container.innerHTML =
                        '<tr><td colspan="6" class="text-center text-danger">Error al cargar inventario</td></tr>';
                });


        }

        function prepSalida(product) {

            mode = 'single';
            document.getElementById('summary-single').style.display = 'block';
            document.getElementById('summary-cart').style.display = 'none';
            document.getElementById('movementType').disabled = false;
            document.getElementById('field-quantity').style.display = 'block';
            document.getElementById('input-quantity').required = true;

            state.product = product;
            //

            document.getElementById('form-prod-code').innerText = product.productCode;
            document.getElementById('form-prod-name').innerText = product.productName;
            document.getElementById('form-prod-lote').innerText = product.lotNumber;
            document.getElementById('form-prod-expiration-date').innerText = product.expirationDate.split(' ')[0];
            document.getElementById('form-prod-stock').innerText = product.quantity;
            document.getElementById('warehouseInventoryId').value = product.inventoryId;
            //document.getElementById('input-qty').max = product.stock;

            // ✅ NUEVO — poblar ubicación actual para LOCATION_UPDATE
            document.getElementById('current-rack').textContent = product.rack ?? 'SR';
            document.getElementById('current-level').textContent = product.level ?? 'SN';
            document.getElementById('current-module').textContent = product.module ?? 'SM';
            document.getElementById('current-bay').textContent = product.bay ?? 'SB';
            document.getElementById('current-platform').textContent = product.platform ?? 'ST';

            // Reset checkbox rehabilitar al seleccionar nuevo producto
            document.getElementById('checkRehabilitar').checked = false;
            toggleRehabilitar();

            showStep(3);
        }

        // ═══════════════════════════════════════════════════════════
        // CARRITO MULTI-PRODUCTO (Salida Simple / Venta)
        // ═══════════════════════════════════════════════════════════
        let cart = [];
        let mode = 'single';

        function addToCart(item) {
            const existing = cart.find(c => c.inventoryId === item.inventoryId);
            if (existing) {
                const row = document.getElementById('cart-item-' + item.inventoryId);
                if (row) {
                    row.scrollIntoView({ behavior: 'smooth', block: 'center' });
                    row.classList.add('cart-item-flash');
                    setTimeout(() => row.classList.remove('cart-item-flash'), 600);
                }
                return;
            }

            cart.push({
                inventoryId: item.inventoryId,
                productCode: item.productCode,
                productName: item.productName,
                quantity: 1,
                maxStock: item.quantity,
                lotNumber: item.lotNumber,
                expirationDate: item.expirationDate,
                rack: item.rack,
                level: item.level,
                module: item.module,
                bay: item.bay,
                platform: item.platform,
                newLocation: null
            });

            renderCart();
        }

        function removeFromCart(inventoryId) {
            cart = cart.filter(c => c.inventoryId !== inventoryId);
            renderCart();

            if (locationUpdateIndex >= cart.length) {
                locationUpdateIndex = Math.max(0, cart.length - 1);
            }
        }

        // El carrito pertenece implícitamente a la bodega elegida en el
        // paso 1 (los items no cargan su propio warehouseId). Si el
        // operador regresa a "Cambiar Bodega" con productos ya encolados,
        // hay que descartarlos antes de mostrar el inventario de otra
        // bodega — de lo contrario el carrito mezclaría inventario de dos
        // bodegas distintas sin que el operador lo note.
        function changeWarehouse(btn) {
            if (cart.length === 0) {
                showStep(1);
                return;
            }

            if (btn.classList.contains('confirming')) {
                cart = [];
                mode = 'single';
                locationUpdateIndex = 0;
                renderCart();

                btn.innerHTML = '<i class="bi bi-arrow-left"></i> Cambiar Bodega';
                btn.classList.remove('confirming');

                showStep(1);
            } else {
                btn.innerHTML = `¿Confirmar? Se perderá el lote de salida (${cart.length})`;
                btn.classList.add('confirming');
                setTimeout(() => {
                    btn.innerHTML = '<i class="bi bi-arrow-left"></i> Cambiar Bodega';
                    btn.classList.remove('confirming');
                }, 3000);
            }
        }

        // ═══════════════════════════════════════════════════════════
        // REUBICACIÓN INTERNA — navegar la ubicación actual/nueva
        // por cada producto del carrito, uno a la vez
        // ═══════════════════════════════════════════════════════════
        let locationUpdateIndex = 0;

        function renderLocationUpdateItem() {
            if (cart.length === 0) return;

            if (locationUpdateIndex >= cart.length) locationUpdateIndex = cart.length - 1;
            if (locationUpdateIndex < 0) locationUpdateIndex = 0;

            const item = cart[locationUpdateIndex];

            document.getElementById('location-update-position').textContent =
                `Producto ${locationUpdateIndex + 1} de ${cart.length}: ${item.productCode} — ${item.productName}`;

            document.getElementById('current-rack').textContent = item.rack ?? 'SR';
            document.getElementById('current-level').textContent = item.level ?? 'SN';
            document.getElementById('current-module').textContent = item.module ?? 'SM';
            document.getElementById('current-bay').textContent = item.bay ?? 'SB';
            document.getElementById('current-platform').textContent = item.platform ?? 'ST';

            const newLocation = item.newLocation || {};
            document.getElementById('input-lu-rack').value = newLocation.rack ?? '';
            document.getElementById('input-lu-level').value = newLocation.level ?? '';
            document.getElementById('input-lu-module').value = newLocation.module ?? '';
            document.getElementById('input-lu-bay').value = newLocation.bay ?? '';
            document.getElementById('input-lu-platform').value = newLocation.platform ?? '';

            document.getElementById('btn-location-prev').disabled = locationUpdateIndex === 0;
            document.getElementById('btn-location-next').disabled = locationUpdateIndex === cart.length - 1;
        }

        function saveCurrentLocationUpdateItem() {
            if (cart.length === 0) return;

            cart[locationUpdateIndex].newLocation = {
                rack: document.getElementById('input-lu-rack').value,
                level: document.getElementById('input-lu-level').value,
                module: document.getElementById('input-lu-module').value,
                bay: document.getElementById('input-lu-bay').value,
                platform: document.getElementById('input-lu-platform').value
            };
        }

        function goToLocationUpdateItem(delta) {
            saveCurrentLocationUpdateItem();
            locationUpdateIndex += delta;
            renderLocationUpdateItem();
        }

        function clampQuantity(inventoryId, inputEl) {
            const item = cart.find(c => c.inventoryId === inventoryId);
            if (!item) return;

            let value = parseInt(inputEl.value, 10);
            if (isNaN(value) || value < 1) value = 1;
            if (value > item.maxStock) value = item.maxStock;

            inputEl.value = value;
            item.quantity = value;
        }

        function renderCart() {
            document.getElementById('cart-count').textContent = cart.length;
            document.getElementById('cart-confirm-count').textContent = cart.length;
            document.getElementById('btn-confirm-cart').disabled = cart.length === 0;

            const emptyEl = document.getElementById('cart-empty');
            const container = document.getElementById('cart-items');

            if (cart.length === 0) {
                emptyEl.style.display = 'block';
                container.innerHTML = '';
                return;
            }

            emptyEl.style.display = 'none';
            container.innerHTML = cart.map(item => `
                <div class="cart-item" id="cart-item-${item.inventoryId}">
                    <div class="d-flex justify-content-between align-items-start gap-2">
                        <div>
                            <span class="badge-info">${item.productCode}</span>
                            <div class="small fw-bold mt-1">${item.productName}</div>
                        </div>
                        <button type="button" class="btn-close" aria-label="Quitar"
                            onclick="removeFromCart(${item.inventoryId})"></button>
                    </div>
                    <div class="d-flex align-items-center gap-2 mt-2">
                        <label class="small text-secondary m-0">Cant:</label>
                        <input type="number" class="form-control form-control-sm cart-qty-input" style="width:80px"
                            value="${item.quantity}" min="1" max="${item.maxStock}"
                            oninput="clampQuantity(${item.inventoryId}, this)"
                            onblur="clampQuantity(${item.inventoryId}, this)">
                        <span class="small text-secondary">máx ${item.maxStock}</span>
                    </div>
                </div>
            `).join('');
        }

        function confirmCart() {
            if (cart.length === 0) return;

            mode = 'cart';
            toggleMovementFields();
            document.getElementById('field-quantity').style.display = 'none';
            document.getElementById('input-quantity').required = false;
            document.getElementById('input-quantity').value = '1';

            document.getElementById('summary-single').style.display = 'none';
            document.getElementById('summary-cart').style.display = 'block';
            document.getElementById('cart-summary-count').textContent = cart.length;
            document.getElementById('cart-summary-list').innerHTML = cart.map(item => `
                <div class="d-flex justify-content-between align-items-center small border-bottom py-2">
                    <span><span class="badge-info">${item.productCode}</span> ${item.productName}</span>
                    <strong>${item.quantity}</strong>
                </div>
            `).join('');

            showStep(3);
        }

        const processCartUrl = "{{ route('output.inventory.process.cart') }}";

        async function submitCart() {
            const form = document.getElementById('formSalida');
            const btn = document.getElementById('btn-submit');
            const movementType = selectMovementType.value;

            // Reubicación Interna: la ubicación nueva es por producto, no
            // compartida — guarda lo que esté en pantalla del item actual
            // antes de armar los envíos.
            if (movementType === 'LOCATION_UPDATE') {
                saveCurrentLocationUpdateItem();
            }

            const sharedFields = new FormData(form);
            // El select de Tipo de Movimiento queda editable (igual que en el
            // flujo de un solo producto), así que se respeta lo que el
            // operador haya elegido ahí al momento de confirmar.
            const payload = {
                movement_type: movementType,
                reason: sharedFields.get('reason'),
                operation_date: sharedFields.get('operation_date'),
                invoice_sap: sharedFields.get('invoice_sap'),
                destination_warehouse: sharedFields.get('destination_warehouse'),
                destination_warehouse_id: sharedFields.get('destination_warehouse_id'),
                folio_transfer: sharedFields.get('folio_transfer'),
                items: cart.map(item => {
                    const newLocation = item.newLocation || {};
                    return {
                        warehouseInventoryId: item.inventoryId,
                        quantity: item.quantity,
                        new_rack_r: newLocation.rack || null,
                        new_level_r: newLocation.level || null,
                        new_module_r: newLocation.module || null,
                        new_bay_r: newLocation.bay || null,
                        new_platform_r: newLocation.platform || null
                    };
                })
            };

            const total = cart.length;
            let successCount = 0;
            let failCount = total;

            btn.disabled = true;
            btn.textContent = `Enviando... (${total})`;

            try {
                const response = await fetch(processCartUrl, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': sharedFields.get('_token')
                    },
                    body: JSON.stringify(payload)
                });

                const data = await response.json();
                const results = data.results || [];
                successCount = results.filter(r => r.ok).length;
                failCount = results.length - successCount;
            } catch (error) {
                console.error('Error enviando el carrito:', error);
            }

            btn.disabled = false;
            btn.textContent = 'Registrar Salida';

            cart = [];
            renderCart();

            notify(`Lote de salida procesado: ${successCount} de ${total} registrados correctamente` +
                (failCount ? `, ${failCount} con error.` : '.'));

            showStep(2);
            renderInventory(state.warehouseId);
        }

        function processSalida(e) {
            alert(JSON.stringify(state.product));

            e.preventDefault();
            const qty = parseInt(document.getElementById('input-qty').value);



            const movimiento = {
                folio: 'SAL-' + Math.floor(Math.random() * 9000 + 1000),
                fecha: document.getElementById('input-date').value,
                producto: state.product.desc,
                codigo: state.product.code,
                cant: qty,
                motivo: document.getElementById('input-reason').value,
                notas: document.getElementById('input-notes').value,
                lote: state.product.lote
            };

            // Actualizar Stock Real (Simulado)
            state.product.stock -= qty;
            state.history.unshift(movimiento);

            renderHistory();
            notify(`Salida ${movimiento.folio} registrada.`);
            showStep(2);
            renderInventory(state.warehouseId);
        }

        function renderHistory() {
            const container = document.getElementById('history-body');
            container.innerHTML = state.history.map(m => `
                <tr>
                    <td><small class="fw-bold">${m.folio}</small></td>
                    <td>${m.fecha}</td>
                    <td>${m.producto}</td>
                    <td class="text-danger fw-bold">-${m.cant}</td>
                    <td>
                        <button class="btn btn-sm btn-light border" onclick="showDetail('${m.folio}')">
                            <i class="bi bi-eye"></i>
                        </button>
                        <button class="btn btn-sm btn-light border text-danger" onclick="confirmDeleteInline(this, '${m.folio}')">
                            <i class="bi bi-trash"></i>
                        </button>
                    </td>
                </tr>
            `).join('');
        }

        // Sustituto del Modal de Detalle
        function showDetail(folio) {
            const m = state.history.find(x => x.folio === folio);
            const panel = document.getElementById('detail-panel');
            const content = document.getElementById('detail-content');

            content.innerHTML = `
                <div class="col-md-3"><strong>Lote:</strong> ${m.lote}</div>
                <div class="col-md-3"><strong>Motivo:</strong> ${m.motivo}</div>
                <div class="col-md-6"><strong>Notas:</strong> ${m.notas || 'Sin notas'}</div>
            `;
            panel.style.display = 'block';
            panel.scrollIntoView({
                behavior: 'smooth'
            });
        }

        // Sustituto del Modal de Eliminación (Confirmación en el botón)
        function confirmDeleteInline(btn, folio) {
            if (btn.classList.contains('confirming')) {
                state.history = state.history.filter(x => x.folio !== folio);
                renderHistory();
                notify("Registro eliminado", "error");
            } else {
                btn.innerHTML = "Confirmar?";
                btn.classList.add('confirming', 'btn-danger', 'text-white');
                setTimeout(() => {
                    btn.innerHTML = '<i class="bi bi-trash"></i>';
                    btn.classList.remove('confirming', 'btn-danger', 'text-white');
                }, 3000);
            }
        }

        function notify(msg) {
            const area = document.getElementById('toast-area');
            const id = Date.now();
            area.innerHTML += `
                <div id="${id}" class="tacsa-card mb-2 shadow-lg border-danger" style="min-width: 250px; background: #fff">
                    <div class="d-flex align-items-center gap-2">
                        <i class="bi bi-check-circle-fill text-success"></i>
                        <span>${msg}</span>
                    </div>
                </div>
            `;
            setTimeout(() => document.getElementById(id)?.remove(), 3000);
        }

        function toggleRehabilitar() {
            const checked = document.getElementById('checkRehabilitar').checked;
            const fields = document.getElementById('fields-rehabilitar');

            if (checked) {
                fields.classList.remove('d-none');
                document.getElementById('input-new-manufacturing-date').required = true;
            } else {
                fields.classList.add('d-none');
                document.getElementById('input-new-manufacturing-date').value = '';
            }
        }
    </script>
</body>

</html>
