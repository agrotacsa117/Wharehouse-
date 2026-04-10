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
                <h1 class="h3 fw-bold mb-1">Salida de Almacenes</h1>
                <p class="text-secondary small">Gestione el retiro de materiales sin ventanas emergentes.</p>
            </div>
        </div>

        <div class="step-wizard">
            <div class="step-item active" id="step1-indicator">
                <div class="step-num">1</div>
                <div><span class="d-block small">Origen</span><strong>Almacén</strong></div>
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
                <h2 class="h5 m-0">Seleccione el almacén de origen</h2>
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

        <div id="panel-step2" class="tacsa-card" style="display:none;">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div class="section-header m-0">
                    <h2 class="h5 m-0">Productos en <span id="current-wh-name" class="text-danger"></span></h2>
                </div>
                <button class="btn-outline btn-sm" onclick="showStep(1)"><i class="bi bi-arrow-left"></i> Cambiar
                    Almacén</button>
            </div>

            <div class="table-responsive">
                <table class="tacsa-table">
                    <thead>
                        <tr>
                            <th>Código</th>
                            <th>Descripción</th>
                            <th>Ubicación(Rack/Level)</th>
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

        <div id="panel-step3" style="display:none;">
            <div class="row">
                <div class="col-md-4">
                    <div class="tacsa-card bg-light">
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
                                            <option value="TRANSFER">Traslado entre Sucursales</option>
                                            <option value="RELOCATION">Reubicación Interna</option>
                                        </select>
                                    </div>

                                    <div class="col-md-6">
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
                                                <label class="form-label small fw-bold">ID Cliente *</label>
                                                <input type="number" name="client_id" id="input-client-id"
                                                    class="form-control" min="1">
                                            </div>
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
                                                <label class="form-label small fw-bold">Sucursal Destino *</label>
                                                <select name="destination_warehouse_id"
                                                    id="input-destination-warehouse" class="form-select">
                                                    <option value="">Seleccione sucursal destino...</option>
                                                    @isset($allWarehouses)
                                                        @foreach ($allWarehouses as $wh)
                                                            <option value="{{ $wh->getId() }}">
                                                                {{ $wh->getWarehouseName() }}</option>
                                                        @endforeach
                                                    @endisset
                                                </select>
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
                                        </div>
                                    </div>

                                    <div class="col-12">
                                        <label class="form-label small fw-bold">Motivo / Observaciones</label>
                                        <input type="text" name="reason" id="input-reason" class="form-control"
                                            placeholder="Descripción del movimiento">
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


        function toggleMovementFields() {
            const movementType = document.getElementById('movementType').value;
            const fieldsSale = document.getElementById('fields-sale');
            const fieldsTransfer = document.getElementById('fields-transfer');
            const fieldsRelocation = document.getElementById('fields-relocation');
            const btnSubmit = document.getElementById('btn-submit');

            // Hide all fields first
            fieldsSale.classList.add('d-none');
            fieldsTransfer.classList.add('d-none');
            fieldsRelocation.classList.add('d-none');

            // Reset required attributes
            document.getElementById('input-client-id').required = false;
            document.getElementById('input-destination-warehouse').required = false;
            document.getElementById('input-new-rack').required = false;
            document.getElementById('input-new-level').required = false;

            // Show relevant fields and update button text

            switch (movementType) {
                case 'SALE':
                    fieldsSale.classList.remove('d-none');
                    document.getElementById('input-client-id').required = true;
                    btnSubmit.textContent = 'Registrar Venta';
                    break;
                case 'TRANSFER':
                    fieldsTransfer.classList.remove('d-none');
                    document.getElementById('input-destination-warehouse').required = true;
                    btnSubmit.textContent = 'Crear Traslado';
                    break;
                case 'RELOCATION':
                    fieldsRelocation.classList.remove('d-none');
                    document.getElementById('input-relocation-destination').required = true;
                    document.getElementById('input-new-rack').required = true;
                    document.getElementById('input-new-level').required = true;
                    btnSubmit.textContent = 'Registrar Reubicación';
                    loadRelocationWarehouses();
                    break;
                default:
                    btnSubmit.textContent = 'Registrar Salida';
            }
        }

        function loadRelocationWarehouses() {
            const select = document.getElementById('input-relocation-destination');
            select.innerHTML = '<option value="">Cargando...</option>';

            if (!state.warehouseLocationId) {
                select.innerHTML = '<option value="">Seleccione un almacén primero</option>';
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
                .then(response => response.json())
                .then(items => {
                    container.innerHTML = items.map(item => `
                <tr>
                    <td><span class="badge-info">${item.productCode}</span></td>
                    <td><strong>${item.productName}</strong></td>
                    <td>${item.rack}/${item.level}</td>
                    <td class="fw-bold">${item.quantity}</td>
                    <td class="fw-bold">${item.expirationDate.split(' ')[0]}</td>
                    <td>
                        <button class="btn btn-sm btn-outline-danger" onclick='prepSalida(${JSON.stringify(item)})'>
                            Seleccionar
                        </button>
                    </td>
                </tr>
            `).join('');
                });


        }

        function prepSalida(product) {

            state.product = product;
            //

            document.getElementById('form-prod-code').innerText = product.productCode;
            document.getElementById('form-prod-name').innerText = product.productName;
            document.getElementById('form-prod-lote').innerText = product.lotNumber;
            document.getElementById('form-prod-expiration-date').innerText = product.expirationDate.split(' ')[0];
            document.getElementById('form-prod-stock').innerText = product.quantity;
            document.getElementById('warehouseInventoryId').value = product.inventoryId;
            //document.getElementById('input-qty').max = product.stock;
            showStep(3);
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
    </script>
</body>

</html>
