@extends('layouts.main')
@section('titulo', $titulo)

@section('contenido')
    @php
        $user = auth()->user();
        $esAdmin = $user->rol === 'admin';
        $esTapachula = $user->rol === 'tapachula';
        $esDorado = $user->rol === 'bodega_dorado';
    @endphp

    <style>
        :root {
            --tacsa-red: #DC2626;
            --tacsa-red-dark: #B91C1C;
            --tacsa-red-light: rgba(220, 38, 38, 0.08);
            --color-critical: #dc2626;
            --color-attention: #ca8a04;
            --color-ok: #16a34a;
        }

        .page-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 1rem;
            margin-bottom: 1.5rem;
        }

        .page-title {
            font-size: 1.75rem;
            font-weight: 700;
            color: #1e293b;
            margin: 0;
        }

        .breadcrumb-custom {
            font-size: 0.875rem;
            color: #64748b;
            margin-top: 0.25rem;
        }

        .breadcrumb-custom a {
            color: var(--tacsa-red);
            text-decoration: none;
        }

        .badge-critical-alert {
            background: linear-gradient(135deg, #dc2626, #b91c1c);
            color: white;
            padding: 0.5rem 1rem;
            border-radius: 50px;
            font-weight: 600;
            font-size: 0.875rem;
            animation: pulse-alert 2s infinite;
            display: inline-flex;
            align-items: center;
            gap: 0.4rem;
        }

        @keyframes pulse-alert {

            0%,
            100% {
                box-shadow: 0 0 0 0 rgba(220, 38, 38, 0.4);
            }

            50% {
                box-shadow: 0 0 0 8px rgba(220, 38, 38, 0);
            }
        }

        .date-badge {
            background: white;
            border: 1px solid #e2e8f0;
            padding: 0.5rem 1rem;
            border-radius: 8px;
            font-size: 0.875rem;
            color: #475569;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
        }

        /* Semáforo Cards */
        .semaforo-card {
            border-radius: 16px;
            padding: 1.5rem;
            position: relative;
            overflow: hidden;
            transition: transform 0.2s ease, box-shadow 0.2s ease;
            cursor: pointer;
            height: 100%;
            border: none !important;
        }

        .semaforo-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 12px 30px rgba(0, 0, 0, 0.12) !important;
        }

        .semaforo-card.critical {
            background: linear-gradient(135deg, #fee2e2 0%, #fecaca 100%);
            border-left: 5px solid var(--color-critical) !important;
        }

        .semaforo-card.attention {
            background: linear-gradient(135deg, #fef9c3 0%, #fef08a 100%);
            border-left: 5px solid var(--color-attention) !important;
        }

        .semaforo-card.ok {
            background: linear-gradient(135deg, #dcfce7 0%, #bbf7d0 100%);
            border-left: 5px solid var(--color-ok) !important;
        }

        .semaforo-icon-bg {
            position: absolute;
            top: 1rem;
            right: 1rem;
            font-size: 4rem;
            opacity: 0.1;
        }

        .dot-indicator {
            width: 12px;
            height: 12px;
            border-radius: 50%;
            display: inline-block;
            flex-shrink: 0;
        }

        .dot-critical {
            background-color: var(--color-critical);
            animation: blink 1.5s infinite;
        }

        .dot-attention {
            background-color: var(--color-attention);
        }

        .dot-ok {
            background-color: var(--color-ok);
        }

        @keyframes blink {

            0%,
            100% {
                opacity: 1;
            }

            50% {
                opacity: 0.4;
            }
        }

        .semaforo-number {
            font-size: 2.75rem;
            font-weight: 700;
            line-height: 1.1;
            margin: 0.75rem 0;
        }

        .semaforo-subtitle {
            font-size: 0.8rem;
            opacity: 0.8;
            margin-bottom: 1rem;
        }

        .warehouse-list {
            border-top: 1px solid rgba(0, 0, 0, 0.1);
            padding-top: 1rem;
        }

        .warehouse-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-size: 0.85rem;
            padding: 0.2rem 0;
        }

        /* Chart y Resumen */
        .chart-card,
        .resumen-card {
            background: white;
            border-radius: 16px;
            padding: 1.5rem;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.08);
            border: 1px solid #e2e8f0;
            height: 100%;
        }

        .chart-title {
            font-size: 1rem;
            font-weight: 600;
            color: #1e293b;
            margin-bottom: 1.25rem;
        }

        .chart-container {
            position: relative;
            max-width: 260px;
            margin: 0 auto;
        }

        .chart-legend {
            display: flex;
            justify-content: center;
            gap: 1.25rem;
            margin-top: 1rem;
            flex-wrap: wrap;
        }

        .legend-item {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            font-size: 0.8rem;
            color: #475569;
        }

        .legend-color {
            width: 12px;
            height: 12px;
            border-radius: 3px;
            flex-shrink: 0;
        }

        /* Mini Cards Apiladas */
        .semaforo-mini-card {
            cursor: pointer;
            transition: transform 0.15s ease, box-shadow 0.15s ease;
            border-radius: 10px;
            padding: 1rem 1.25rem;
            margin-bottom: 0.75rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            border: 1px solid transparent;
        }

        .semaforo-mini-card:last-child {
            margin-bottom: 0;
        }

        .semaforo-mini-card:hover {
            transform: translateX(4px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }

        .semaforo-mini-card.critical {
            background: linear-gradient(135deg, #fef2f2 0%, #fee2e2 100%);
            border-left: 4px solid var(--color-critical) !important;
        }

        .semaforo-mini-card.attention {
            background: linear-gradient(135deg, #fefce8 0%, #fef9c3 100%);
            border-left: 4px solid var(--color-attention) !important;
        }

        .semaforo-mini-card.ok {
            background: linear-gradient(135deg, #f0fdf4 0%, #dcfce7 100%);
            border-left: 4px solid var(--color-ok) !important;
        }

        .mini-card-label {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            font-size: 0.9rem;
            font-weight: 500;
        }

        .mini-card-value {
            font-size: 1.6rem;
            font-weight: 700;
        }

        .mini-card-value.critical {
            color: var(--color-critical);
        }

        .mini-card-value.attention {
            color: var(--color-attention);
        }

        .mini-card-value.ok {
            color: var(--color-ok);
        }

        /* Metric Cards */
        .metric-card {
            background: white;
            border-radius: 14px;
            padding: 1.25rem;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.08);
            border: 1px solid #e2e8f0;
            transition: transform 0.2s ease, box-shadow 0.2s ease;
            height: 100%;
        }

        .metric-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.1);
        }

        .metric-icon {
            width: 48px;
            height: 48px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.25rem;
            flex-shrink: 0;
        }

        .metric-icon.red {
            background: var(--tacsa-red-light);
            color: var(--tacsa-red);
        }

        .metric-icon.green {
            background: rgba(22, 163, 74, 0.1);
            color: #16a34a;
        }

        .metric-icon.blue {
            background: rgba(59, 130, 246, 0.1);
            color: #3b82f6;
        }

        .metric-icon.orange {
            background: rgba(234, 88, 12, 0.1);
            color: #ea580c;
        }

        .metric-label {
            font-size: 0.8rem;
            color: #64748b;
            margin-bottom: 0.25rem;
        }

        .metric-value {
            font-size: 1.5rem;
            font-weight: 700;
            color: #1e293b;
        }

        .metric-change {
            font-size: 0.75rem;
            display: inline-flex;
            align-items: center;
            gap: 0.25rem;
            padding: 0.125rem 0.5rem;
            border-radius: 50px;
            margin-top: 0.25rem;
        }

        .metric-change.positive {
            background: rgba(22, 163, 74, 0.1);
            color: #16a34a;
        }

        .metric-change.negative {
            background: rgba(220, 38, 38, 0.1);
            color: #dc2626;
        }

        .metric-change.neutral {
            background: #f1f5f9;
            color: #64748b;
        }

        .progress-custom {
            height: 8px;
            border-radius: 4px;
            background: #e2e8f0;
            overflow: hidden;
        }

        .progress-custom .progress-bar {
            border-radius: 4px;
        }

        /* Rack Circular */
        .rack-progress {
            position: relative;
            width: 100px;
            height: 100px;
            margin: 0 auto 1rem;
        }

        .rack-progress svg {
            transform: rotate(-90deg);
        }

        .rack-progress-text {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            font-size: 1.25rem;
            font-weight: 700;
        }

        .section-title {
            font-size: 1.1rem;
            font-weight: 600;
            color: #1e293b;
            margin-bottom: 1rem;
            padding-bottom: 0.5rem;
            border-bottom: 2px solid var(--tacsa-red);
            display: inline-block;
        }

        /* Modales */
        .modal-header.critical {
            background: linear-gradient(135deg, #fee2e2, #fecaca);
            border-left: 5px solid var(--color-critical);
        }

        .modal-header.attention {
            background: linear-gradient(135deg, #fef9c3, #fef08a);
            border-left: 5px solid var(--color-attention);
        }

        .modal-header.ok {
            background: linear-gradient(135deg, #dcfce7, #bbf7d0);
            border-left: 5px solid var(--color-ok);
        }

        .modal-title-critical {
            color: var(--color-critical);
        }

        .modal-title-attention {
            color: #a16207;
        }

        .modal-title-ok {
            color: var(--color-ok);
        }

        .filter-section {
            background: #f8fafc;
            border-radius: 10px;
            padding: 1rem;
            margin-bottom: 1rem;
        }

        .table-semaforo {
            font-size: 0.85rem;
        }

        .table-semaforo th {
            background: #f1f5f9;
            font-weight: 600;
            color: #475569;
            border-bottom: 2px solid #e2e8f0;
            white-space: nowrap;
        }

        .table-semaforo td {
            vertical-align: middle;
        }

        .no-data-message {
            padding: 3rem;
            text-align: center;
            color: #94a3b8;
        }
    </style>

    <main id="main" class="main bg-light py-4">
        <div class="container-fluid">

            {{-- SECCIÓN 1: Header --}}
            <div class="page-header">
                <div>
                    <h1 class="page-title">
                        <i class="bi bi-speedometer2 me-2" style="color: var(--tacsa-red);"></i>
                        {{ $titulo }}
                    </h1>
                    <div class="breadcrumb-custom">
                        <a href="{{ route('home') }}">Inicio</a> / Panel Principal
                    </div>
                </div>
                <div class="d-flex align-items-center gap-3 flex-wrap">
                    @if (($critical->getTotalStock() ?? 0) > 0)
                        <span class="badge-critical-alert">
                            <i class="bi bi-exclamation-triangle-fill"></i>
                            {{ number_format($critical->getTotalStock(), 0, ',', '.') }} críticos
                        </span>
                    @endif
                    <span class="date-badge">
                        <i class="bi bi-calendar3"></i>
                        {{ now()->format('d/m/Y') }}
                    </span>
                </div>
            </div>

            {{-- SECCIÓN 2: Semáforo Horizontal --}}
           

            {{-- SECCIÓN 3: Gráfica + Resumen Vertical --}}
            @php
                $totalChart =
                    ($critical->getTotalStock() ?? 0) +
                    ($attention->getTotalStock() ?? 0) +
                    ($ok->getTotalStock() ?? 0);
            @endphp
            <div class="row g-4 mb-4">
                {{-- Gráfica --}}
                <div class="col-lg-7">
                    <div class="chart-card">
                        <h3 class="chart-title">
                            <i class="bi bi-pie-chart-fill me-2" style="color: var(--tacsa-red);"></i>
                            Distribución por Estado de Caducidad
                        </h3>
                        @if ($totalChart > 0)
                            <div class="chart-container">
                                <canvas id="chartCaducidad"></canvas>
                            </div>
                            <div class="chart-legend">
                                <div class="legend-item">
                                    <span class="legend-color" style="background: var(--color-critical);"></span>
                                    <span>Crítico (&lt;90 días)</span>
                                </div>
                                <div class="legend-item">
                                    <span class="legend-color" style="background: var(--color-attention);"></span>
                                    <span>Atención (90-120 días)</span>
                                </div>
                                <div class="legend-item">
                                    <span class="legend-color" style="background: var(--color-ok);"></span>
                                    <span>OK (&gt;120 días)</span>
                                </div>
                            </div>
                        @else
                            <div class="no-data-message">
                                <i class="bi bi-inbox-fill fs-1 mb-3 d-block"></i>
                                <p class="mb-0">Sin datos de inventario</p>
                            </div>
                        @endif
                    </div>
                </div>

                {{-- Resumen Vertical --}}
                <div class="col-lg-5">
                    <div class="resumen-card">
                        <h3 class="chart-title">
                            <i class="bi bi-list-check me-2" style="color: var(--tacsa-red);"></i>
                            Resumen de Caducidad
                        </h3>

                        <div class="semaforo-mini-card critical" onclick="openSemaforoModal('critical')">
                            <div class="mini-card-label">
                                <span class="dot-indicator dot-critical"></span>
                                <span style="color: var(--color-critical);">Crítico (&lt;90 días)</span>
                            </div>
                            <span
                                class="mini-card-value critical">{{ number_format($critical->getTotalStock() ?? 0, 0, ',', '.') }}</span>
                        </div>

                        <div class="semaforo-mini-card attention" onclick="openSemaforoModal('attention')">
                            <div class="mini-card-label">
                                <span class="dot-indicator dot-attention"></span>
                                <span style="color: #a16207;">Atención (90-120 días)</span>
                            </div>
                            <span
                                class="mini-card-value attention">{{ number_format($attention->getTotalStock() ?? 0, 0, ',', '.') }}</span>
                        </div>

                        <div class="semaforo-mini-card ok" onclick="openSemaforoModal('ok')">
                            <div class="mini-card-label">
                                <span class="dot-indicator dot-ok"></span>
                                <span style="color: var(--color-ok);">OK (&gt;120 días)</span>
                            </div>
                            <span
                                class="mini-card-value ok">{{ number_format($ok->getTotalStock() ?? 0, 0, ',', '.') }}</span>
                        </div>
                    </div>
                </div>
            </div>

          
        </div>{{-- /container-fluid --}}
    </main>

    {{-- ============================================================= --}}
    {{--                    MODALES DEL SEMÁFORO                       --}}
    {{-- ============================================================= --}}

    {{-- Modal CRÍTICO --}}
    <div class="modal fade" id="modalCritical" tabindex="-1" aria-labelledby="modalCriticalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header critical">
                    <h5 class="modal-title modal-title-critical fw-bold" id="modalCriticalLabel">
                        <i class="bi bi-exclamation-triangle-fill me-2"></i>
                        Productos Críticos
                        <span class="badge bg-danger ms-2">{{ $critical->getTotalStock() ?? 0 }}</span>
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                </div>
                <div class="modal-body">
                    <div class="filter-section">
                        <div class="row g-3">
                            <div class="col-md-4">
                                <label class="form-label small fw-medium">Almacén</label>
                                <select class="form-select form-select-sm" id="filterWarehouseCritical"
                                    onchange="filterSemaforoTable('critical')">
                                    <option value="">Todos los almacenes</option>
                                    @foreach ($almacenes as $almacen)
                                        <option value="{{ $almacen->id }}">{{ $almacen->warehouses_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label small fw-medium">Producto</label>
                                <input type="text" class="form-control form-control-sm" id="filterProductCritical"
                                    placeholder="Buscar producto..." onkeyup="filterSemaforoTable('critical')">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label small fw-medium">Fecha Máx. Caducidad</label>
                                <input type="date" class="form-control form-control-sm" id="filterDateCritical"
                                    onchange="filterSemaforoTable('critical')">
                            </div>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-hover table-semaforo mb-0">
                            <thead>
                                <tr>
                                    <th class="text-center">#</th>
                                    <th>Código</th>
                                    <th>Producto</th>
                                    <th>Almacén</th>
                                    <th>Rack</th>
                                    <th class="text-center">Nivel</th>
                                    <th>Lote</th>
                                    <th class="text-center">Cantidad</th>
                                    <th class="text-center">Fecha Caducidad</th>
                                    <th class="text-center">Días</th>
                                    <th class="text-center">Obsolescencia (%)</th>
                                </tr>
                            </thead>
                            <tbody id="tableCriticalBody">
                                <tr>
                                    <td colspan="11" class="text-center text-muted py-4"><i
                                            class="bi bi-hourglass-split me-2"></i>Cargando datos...</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="modal-footer justify-content-between">
                    <span class="text-muted small" id="countCritical">0 productos</span>
                    <button type="button" class="btn btn-outline-secondary btn-sm" data-bs-dismiss="modal">
                        <i class="bi bi-x-circle me-1"></i>Cerrar
                    </button>
                </div>
            </div>
        </div>
    </div>

    {{-- Modal ATENCIÓN --}}
    <div class="modal fade" id="modalAttention" tabindex="-1" aria-labelledby="modalAttentionLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header attention">
                    <h5 class="modal-title modal-title-attention fw-bold" id="modalAttentionLabel">
                        <i class="bi bi-clock-fill me-2"></i>
                        Productos en Atención
                        <span class="badge bg-warning text-dark ms-2">{{ $attention->getTotalStock() ?? 0 }}</span>
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                </div>
                <div class="modal-body">
                    <div class="filter-section">
                        <div class="row g-3">
                            <div class="col-md-4">
                                <label class="form-label small fw-medium">Almacén</label>
                                <select class="form-select form-select-sm" id="filterWarehouseAttention"
                                    onchange="filterSemaforoTable('attention')">
                                    <option value="">Todos los almacenes</option>
                                    @foreach ($almacenes as $almacen)
                                        <option value="{{ $almacen->id }}">{{ $almacen->warehouses_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label small fw-medium">Producto</label>
                                <input type="text" class="form-control form-control-sm" id="filterProductAttention"
                                    placeholder="Buscar producto..." onkeyup="filterSemaforoTable('attention')">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label small fw-medium">Fecha Máx. Caducidad</label>
                                <input type="date" class="form-control form-control-sm" id="filterDateAttention"
                                    onchange="filterSemaforoTable('attention')">
                            </div>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-hover table-semaforo mb-0">
                            <thead>
                                <tr>
                                    <th class="text-center">#</th>
                                    <th>Código</th>
                                    <th>Producto</th>
                                    <th>Almacén</th>
                                    <th>Rack</th>
                                    <th class="text-center">Nivel</th>
                                    <th>Lote</th>
                                    <th class="text-center">Cantidad</th>
                                    <th class="text-center">Fecha Caducidad</th>
                                    <th class="text-center">Días</th>
                                    <th class="text-center">Obsolescencia (%)</th>
                                </tr>
                            </thead>
                            <tbody id="tableAttentionBody">
                                <tr>
                                    <td colspan="11" class="text-center text-muted py-4"><i
                                            class="bi bi-hourglass-split me-2"></i>Cargando datos...</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="modal-footer justify-content-between">
                    <span class="text-muted small" id="countAttention">0 productos</span>
                    <button type="button" class="btn btn-outline-secondary btn-sm" data-bs-dismiss="modal">
                        <i class="bi bi-x-circle me-1"></i>Cerrar
                    </button>
                </div>
            </div>
        </div>
    </div>

    {{-- Modal OK --}}
    <div class="modal fade" id="modalOk" tabindex="-1" aria-labelledby="modalOkLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header ok">
                    <h5 class="modal-title modal-title-ok fw-bold" id="modalOkLabel">
                        <i class="bi bi-check-circle-fill me-2"></i>
                        Productos Vigentes
                        <span class="badge bg-success ms-2">{{ $ok->getTotalStock() ?? 0 }}</span>
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                </div>
                <div class="modal-body">
                    <div class="filter-section">
                        <div class="row g-3">
                            <div class="col-md-4">
                                <label class="form-label small fw-medium">Almacén</label>
                                <select class="form-select form-select-sm" id="filterWarehouseOk"
                                    onchange="filterSemaforoTable('ok')">
                                    <option value="">Todos los almacenes</option>
                                    @foreach ($almacenes as $almacen)
                                        <option value="{{ $almacen->id }}">{{ $almacen->warehouses_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label small fw-medium">Producto</label>
                                <input type="text" class="form-control form-control-sm" id="filterProductOk"
                                    placeholder="Buscar producto..." onkeyup="filterSemaforoTable('ok')">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label small fw-medium">Fecha Máx. Caducidad</label>
                                <input type="date" class="form-control form-control-sm" id="filterDateOk"
                                    onchange="filterSemaforoTable('ok')">
                            </div>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-hover table-semaforo mb-0">
                            <thead>
                                <tr>
                                    <th class="text-center">#</th>
                                    <th>Código</th>
                                    <th>Producto</th>
                                    <th>Almacén</th>
                                    <th>Rack</th>
                                    <th class="text-center">Nivel</th>
                                    <th>Lote</th>
                                    <th class="text-center">Cantidad</th>
                                    <th class="text-center">Fecha Caducidad</th>
                                    <th class="text-center">Días</th>
                                    <th class="text-center">Obsolescencia (%)</th>
                                </tr>
                            </thead>
                            <tbody id="tableOkBody">
                                <tr>
                                    <td colspan="11" class="text-center text-muted py-4"><i
                                            class="bi bi-hourglass-split me-2"></i>Cargando datos...</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="modal-footer justify-content-between">
                    <span class="text-muted small" id="countOk">0 productos</span>
                    <button type="button" class="btn btn-outline-secondary btn-sm" data-bs-dismiss="modal">
                        <i class="bi bi-x-circle me-1"></i>Cerrar
                    </button>
                </div>
            </div>
        </div>
    </div>

@endsection

@push('scripts')
    <script>
        // Datos del semáforo desde PHP
        const semaforoData = {
            critical: {!! json_encode($semaforoCritical ?? []) !!},
            attention: {!! json_encode($semaforoAttention ?? []) !!},
            ok: {!! json_encode($semaforoOk ?? []) !!}
        };

        // Inicializar Chart.js (layouts.main ya lo carga)
        document.addEventListener('DOMContentLoaded', function() {
            const canvas = document.getElementById('chartCaducidad');
            if (canvas) {
                const ctx = canvas.getContext('2d');
                const totalItems =
                    {{ ($critical->getTotalStock() ?? 0) + ($attention->getTotalStock() ?? 0) + ($ok->getTotalStock() ?? 0) }};

                if (totalItems > 0) {
                    new Chart(ctx, {
                        type: 'doughnut',
                        data: {
                            labels: ['Crítico (<90 días)', 'Atención (90-120 días)', 'OK (>120 días)'],
                            datasets: [{
                                data: [
                                    {{ $critical->getTotalStock() ?? 0 }},
                                    {{ $attention->getTotalStock() ?? 0 }},
                                    {{ $ok->getTotalStock() ?? 0 }}
                                ],
                                backgroundColor: ['#dc2626', '#ca8a04', '#16a34a'],
                                borderWidth: 3,
                                borderColor: '#ffffff',
                                hoverOffset: 8
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: true,
                            cutout: '60%',
                            plugins: {
                                legend: {
                                    display: false
                                },
                                tooltip: {
                                    backgroundColor: 'rgba(15, 23, 42, 0.9)',
                                    padding: 12,
                                    cornerRadius: 8,
                                    callbacks: {
                                        label: function(context) {
                                            const pct = totalItems > 0 ? ((context.raw / totalItems) *
                                                100).toFixed(1) : 0;
                                            return context.label + ': ' + context.raw.toLocaleString(
                                                'es-MX') + ' (' + pct + '%)';
                                        }
                                    }
                                }
                            }
                        }
                    });
                }
            }
        });

        function formatNumber(num) {
            return new Intl.NumberFormat('es-MX').format(num);
        }

        function formatDate(dateStr) {
            if (!dateStr) return '-';
            const date = new Date(dateStr);
            return date.toLocaleDateString('es-MX', {
                day: '2-digit',
                month: '2-digit',
                year: 'numeric'
            });
        }

        function getBadgeClass(days) {
            if (days < 0) return 'bg-danger';
            if (days <= 30) return 'bg-danger';
            if (days <= 60) return 'bg-warning text-dark';
            return 'bg-success';
        }

        function capitalize(str) {
            return str.charAt(0).toUpperCase() + str.slice(1);
        }

        function renderTableData(data, tbodyId, countId) {
            const tbody = document.getElementById(tbodyId);
            const countEl = document.getElementById(countId);
            if (!tbody) return;

            if (!data || data.length === 0) {
                tbody.innerHTML =
                    `<tr><td colspan="11" class="text-center text-muted py-4"><i class="bi bi-inbox-fill me-2"></i>No hay productos en esta categoría</td></tr>`;
                if (countEl) countEl.textContent = '0 productos';
                return;
            }

            tbody.innerHTML = data.map((item, index) => `
            <tr class="${item.remainingDays < 0 ? 'table-danger' : ''}">
                <td class="text-center text-muted">${index + 1}</td>
                <td class="fw-medium">${item.productId || 'N/A'}</td>
                <td class="fw-medium">${item.productName || 'N/A'}</td>
                <td><span class="badge bg-secondary">${item.warehouseName || 'N/A'}</span></td>
                <td><span class="badge bg-dark">${item.rack || '-'}</span></td>
                <td class="text-center"><strong>${item.level || '-'}</strong></td>
                <td><small>${item.lotNumber || '-'}</small></td>
                <td class="text-center fw-bold">${formatNumber(item.quantity)}</td>
                <td class="text-center">${formatDate(item.expirationDate)}</td>
                <td class="text-center">
                    <span class="badge ${getBadgeClass(item.remainingDays)}">
                        ${item.remainingDays < 0 ? 'Vencido' : item.remainingDays + ' días'}
                    </span>
                </td>
                <td class="text-center text-muted">-</td>
            </tr>
        `).join('');

            if (countEl) countEl.textContent = `${data.length} producto${data.length !== 1 ? 's' : ''}`;
        }

        let currentModal = null;

        function openSemaforoModal(type) {
            const modalId = type === 'critical' ? 'modalCritical' : type === 'attention' ? 'modalAttention' : 'modalOk';
            const tbodyId = type === 'critical' ? 'tableCriticalBody' : type === 'attention' ? 'tableAttentionBody' :
                'tableOkBody';
            const countId = `count${capitalize(type)}`;

            renderTableData(semaforoData[type] || [], tbodyId, countId);
            currentModal = new bootstrap.Modal(document.getElementById(modalId));
            currentModal.show();
        }

        function filterSemaforoTable(type) {
            const warehouseFilter = document.getElementById(`filterWarehouse${capitalize(type)}`)?.value || '';
            const productFilter = document.getElementById(`filterProduct${capitalize(type)}`)?.value.toLowerCase() || '';
            const dateFilter = document.getElementById(`filterDate${capitalize(type)}`)?.value || '';

            let filteredData = semaforoData[type] || [];

            if (warehouseFilter) filteredData = filteredData.filter(item => item.warehouseId == warehouseFilter);
            if (productFilter) filteredData = filteredData.filter(item =>
                (item.productId || '').toLowerCase().includes(productFilter) ||
                (item.productName || '').toLowerCase().includes(productFilter)
            );
            if (dateFilter) {
                const filterDate = new Date(dateFilter);
                filteredData = filteredData.filter(item => new Date(item.expirationDate) <= filterDate);
            }

            const tbodyId = type === 'critical' ? 'tableCriticalBody' : type === 'attention' ? 'tableAttentionBody' :
                'tableOkBody';
            renderTableData(filteredData, tbodyId, `count${capitalize(type)}`);
        }

        function resetFilters(type) {
            ['filterWarehouse', 'filterProduct', 'filterDate'].forEach(prefix => {
                const el = document.getElementById(`${prefix}${capitalize(type)}`);
                if (el) el.value = '';
            });
            filterSemaforoTable(type);
        }

        ['Critical', 'Attention', 'Ok'].forEach(type => {
            const modalEl = document.getElementById(`modal${type}`);
            if (modalEl) {
                modalEl.addEventListener('hidden.bs.modal', function() {
                    currentModal = null;
                    resetFilters(type.toLowerCase());
                });
            }
        });
    </script>
@endpush
