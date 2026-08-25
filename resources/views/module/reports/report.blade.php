@extends('layouts.main')
@section('titulo', $titulo)


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

    .table-footer {
        padding: 1rem 1.5rem;
        display: flex;
        align-items: center;
        justify-content: space-between;
        border-top: 1px solid var(--border-color);
        font-size: 0.8125rem;
        color: var(--text-secondary);
    }

    .pagination-btns {
        display: flex;
        gap: 0.375rem;
    }

    .page-btn {
        width: 34px;
        height: 34px;
        border-radius: 6px;
        border: 1px solid var(--border-color);
        background: var(--bg-card);
        color: var(--text-secondary);
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 0.8125rem;
        font-weight: 500;
        cursor: pointer;
        transition: all 0.15s;
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

    /* Mini Cards */
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
        background: linear-gradient(135deg, #fef2f2, #fee2e2);
        border-left: 4px solid var(--color-critical) !important;
    }

    .semaforo-mini-card.attention {
        background: linear-gradient(135deg, #fefce8, #fef9c3);
        border-left: 4px solid var(--color-attention) !important;
    }

    .semaforo-mini-card.ok {
        background: linear-gradient(135deg, #f0fdf4, #dcfce7);
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

    /* TABS */
    .tabs-nav {
        display: flex;
        gap: 0;
        background: white;
        border-radius: 12px;
        padding: 0.35rem;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.08);
        border: 1px solid #e2e8f0;
        margin-bottom: 1.75rem;
        width: fit-content;
        flex-wrap: wrap;
    }

    .tab-btn {
        padding: 0.55rem 1.25rem;
        border-radius: 8px;
        border: none;
        background: transparent;
        font-size: 0.875rem;
        font-weight: 500;
        color: #64748b;
        cursor: pointer;
        transition: all 0.2s ease;
        display: inline-flex;
        align-items: center;
        gap: 0.4rem;
    }

    .tab-btn:hover {
        background: #f1f5f9;
        color: #1e293b;
    }

    .tab-btn.active {
        background: var(--tacsa-red);
        color: white;
        box-shadow: 0 2px 8px rgba(220, 38, 38, 0.3);
    }

    .tab-panel {
        display: none;
    }

    .tab-panel.active {
        display: block;
    }

    /* TOP 3 */
    .top3-card {
        background: white;
        border-radius: 16px;
        padding: 1.5rem;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.08);
        border: 1px solid #e2e8f0;
        height: 100%;
    }

    .top3-item {
        display: flex;
        align-items: center;
        gap: 1rem;
        padding: 0.85rem 1rem;
        border-radius: 10px;
        margin-bottom: 0.6rem;
        background: linear-gradient(135deg, #fef2f2, #fee2e2);
        border-left: 4px solid var(--color-critical);
        transition: transform 0.15s ease;
    }

    .top3-item:hover {
        transform: translateX(3px);
    }

    .top3-item:last-child {
        margin-bottom: 0;
    }

    .top3-rank {
        width: 32px;
        height: 32px;
        border-radius: 50%;
        color: white;
        font-weight: 700;
        font-size: 0.85rem;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }

    .top3-rank.rank-1 {
        background: #dc2626;
    }

    .top3-rank.rank-2 {
        background: #ef4444;
    }

    .top3-rank.rank-3 {
        background: #f87171;
    }

    .top3-info {
        flex: 1;
        min-width: 0;
    }

    .top3-name {
        font-weight: 600;
        font-size: 0.875rem;
        color: #1e293b;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .top3-meta {
        font-size: 0.75rem;
        color: #64748b;
        margin-top: 0.1rem;
    }

    .top3-days {
        font-size: 0.8rem;
        font-weight: 700;
        padding: 0.2rem 0.6rem;
        border-radius: 50px;
        white-space: nowrap;
        flex-shrink: 0;
    }

    .days-expired {
        background: #dc2626;
        color: white;
    }

    .days-urgent {
        background: #fef2f2;
        color: #dc2626;
        border: 1px solid #fecaca;
    }

    /* VENCIDOS */
    .vencidos-card {
        background: white;
        border-radius: 16px;
        padding: 1.5rem;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.08);
        border: 1px solid #e2e8f0;
    }

    /* MOVIMIENTOS */
    .reporte-card {
        background: white;
        border-radius: 16px;
        padding: 1.5rem;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.08);
        border: 1px solid #e2e8f0;
        margin-bottom: 1.5rem;
    }

    .reporte-filter-bar {
        background: #f8fafc;
        border-radius: 12px;
        padding: 1.25rem;
        border: 1px solid #e2e8f0;
        margin-bottom: 1.5rem;
    }

    .movement-stat-card {
        background: white;
        border-radius: 12px;
        padding: 1rem 1.25rem;
        border: 1px solid #e2e8f0;
        text-align: center;
    }

    .movement-stat-value {
        font-size: 1.75rem;
        font-weight: 700;
        color: #1e293b;
    }

    .movement-stat-label {
        font-size: 0.78rem;
        color: #64748b;
        margin-top: 0.2rem;
    }

    .movement-stat-icon {
        font-size: 1.5rem;
        margin-bottom: 0.5rem;
    }
</style>

@section('contenido')
    <main id="main" class="main bg-light py-4">
        <div class="container-fluid">

            {{-- HEADER --}}
            <div class="page-header">
                <meta name="csrf-token" content="{{ csrf_token() }}">

                <div>
                    <h1 class="page-title">
                        <i class="bi bi-bar-chart-fill me-2" style="color: var(--tacsa-red);"></i>
                        {{ $titulo }}
                    </h1>
                    <div class="breadcrumb-custom">
                        <a href="{{ route('home') }}">Inicio</a> / Reportes
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

            {{-- TABS NAV --}}
            <div class="tabs-nav">
                <button class="tab-btn active" onclick="switchTab('caducidad', this)">
                    <i class="bi bi-exclamation-triangle-fill"></i> Caducidad
                </button>
                <button class="tab-btn" onclick="switchTab('existencias', this)">
                    <i class="bi bi-pie-chart-fill"></i> Existencias
                </button>
                {{-- ✅ NUEVO TAB --}}
                <button class="tab-btn" onclick="switchTab('productos', this)">
                    <i class="bi bi-box-seam"></i> Productos
                </button>
                <button class="tab-btn" onclick="switchTab('movimientos', this)">
                    <i class="bi bi-arrow-left-right"></i> Movimientos
                </button>
                <button class="tab-btn" onclick="switchTab('sales', this)">
                    <i class="bi bi-cart3"></i> Ventas
                </button>
            </div>

            {{-- =========================================== --}}
            {{--           TAB 1: CADUCIDAD                  --}}
            {{-- =========================================== --}}
            <div id="tab-caducidad" class="tab-panel active">

                {{-- TOP 3 DESDE BACKEND ($rankingCaducidad) --}}
                <div class="mb-3">
                    <span class="section-title">
                        <i class="bi bi-trophy-fill me-1" style="color: var(--tacsa-red);"></i>
                        Top 3 Productos Vencidos por Bodega
                    </span>
                </div>

                <div class="row g-4 mb-4">
                    @forelse ($rankingCaducidad as $warehouseRanking)
                        <div class="col-lg-4 col-md-6">
                            <div class="top3-card">
                                <h3 class="chart-title">
                                    <i class="bi bi-building me-2" style="color: var(--tacsa-red);"></i>
                                    {{ $warehouseRanking['warehouseName'] }}
                                </h3>

                                @if (empty($warehouseRanking['expiredItems']))
                                    <div class="no-data-message py-3">
                                        <i class="bi bi-check-circle-fill fs-2 mb-2 d-block"
                                            style="color: var(--color-ok);"></i>
                                        <p class="mb-0 small">Sin productos vencidos</p>
                                    </div>
                                @else
                                    @foreach ($warehouseRanking['expiredItems'] as $prod)
                                        <div class="top3-item">
                                            <div class="top3-rank rank-{{ $prod['rank'] }}">{{ $prod['rank'] }}</div>
                                            <div class="top3-info">
                                                <div class="top3-name" title="{{ $prod['productName'] }}">
                                                    {{ $prod['productName'] }}
                                                </div>
                                                <div class="top3-meta">
                                                    Lote: {{ $prod['lotNumber'] }} &bull;
                                                    {{ number_format($prod['quantity'], 0, ',', '.') }} uds
                                                    &bull; Rack {{ $prod['rack'] }}/Nv.{{ $prod['level'] }}
                                                </div>
                                            </div>
                                            <span class="top3-days days-expired">
                                                Hace {{ abs($prod['remainingDays']) }} días
                                            </span>
                                        </div>
                                    @endforeach
                                @endif
                            </div>
                        </div>
                    @empty
                        <div class="col-12">
                            <div class="no-data-message">
                                <i class="bi bi-check-circle-fill fs-1 mb-3 d-block" style="color: var(--color-ok);"></i>
                                <p>¡Sin productos vencidos en ningúna bodega!</p>
                            </div>
                        </div>
                    @endforelse
                </div>

                {{-- TABLA PRODUCTOS YA VENCIDOS (desde semaforoCritical filtrado) --}}
                <div class="vencidos-card">
                    <div class="d-flex align-items-center justify-content-between mb-3 flex-wrap gap-2">
                        <h3 class="chart-title mb-0">
                            <i class="bi bi-x-circle-fill me-2" style="color: var(--color-critical);"></i>
                            Productos Ya Vencidos
                            @php
                                $totalVencidos = collect($semaforoCritical ?? [])
                                    ->filter(function ($p) {
                                        $days = is_array($p) ? $p['remainingDays'] ?? 1 : $p->remainingDays ?? 1;
                                        return $days <= 0;
                                    })
                                    ->count();
                            @endphp
                            @if ($totalVencidos > 0)
                                <span class="badge bg-danger ms-1">{{ $totalVencidos }}</span>
                            @endif
                        </h3>
                        <div class="d-flex gap-2 flex-wrap">
                            <input type="text" class="form-control form-control-sm" id="filterVencidosSearch"
                                placeholder="Buscar producto o lote..." style="width: 220px;" onkeyup="filterVencidos()">
                            <select class="form-select form-select-sm" id="filterVencidosAlmacen" style="width: 170px;"
                                onchange="filterVencidos()">
                                <option value="">Todos las bodegas</option>
                                @foreach ($almacenes as $almacen)
                                    <option value="{{ $almacen->id }}">{{ $almacen->warehouses_name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-hover table-semaforo mb-0">
                            <thead>
                                <tr>
                                    <th class="text-center">#</th>
                                    <th>Producto</th>
                                    <th>Bodega</th>
                                    <th>Rack / Nivel</th>
                                    <th>Lote</th>
                                    <th class="text-center">Cantidad</th>
                                    <th class="text-center">Fecha Caducidad</th>
                                    <th class="text-center">Estado</th>
                                </tr>
                            </thead>
                            <tbody id="tableVencidosBody"></tbody>
                        </table>
                    </div>
                    <div class="mt-2 text-muted small" id="countVencidos"></div>
                </div>

            </div>{{-- /tab-caducidad --}}


            {{-- =========================================== --}}
            {{--          TAB 2: EXISTENCIAS                 --}}
            {{-- =========================================== --}}
            <div id="tab-existencias" class="tab-panel">
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
            </div>{{-- /tab-existencias --}}


            {{-- =========================================== --}}
            {{--         TAB 3: PRODUCTOS (NUEVO)            --}}
            {{-- =========================================== --}}
            <div id="tab-productos" class="tab-panel">

                {{-- FILTROS --}}
                <div class="reporte-filter-bar">
                    <div class="row g-3 align-items-end">
                        <div class="col-md-3">
                            <label class="form-label small fw-medium">Producto</label>
                            <input type="text" class="form-control form-control-sm" id="prodProducto"
                                placeholder="TacsAmina 950 mls...">
                        </div>
                        <div class="col-md-2">
                            <label class="form-label small fw-medium">Bodega</label>
                            <select class="form-select form-select-sm" id="prodAlmacen">
                                <option value="">Todas las bodegas</option>
                                @foreach ($almacenes as $almacen)
                                    <option value="{{ $almacen->id }}">{{ $almacen->warehouses_name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label small fw-medium">Período</label>
                            <select class="form-select form-select-sm" id="prodPeriodo">
                                <option value="6">Últimos 6 meses</option>
                                <option value="12">Últimos 12 meses</option>
                                <option value="all">Todo el historial</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label small fw-medium">Estado</label>
                            <select class="form-select form-select-sm" id="prodEstado">
                                <option value="">Todos</option>
                                <option value="vigente">Vigentes</option>
                                <option value="por_caducar">Por caducar</option>
                                <option value="vencido">Vencidos</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <button class="btn btn-sm btn-danger" onclick="buscarProducto()">
                                <i class="bi bi-search me-1"></i> Buscar
                            </button>
                            <button class="btn btn-sm btn-outline-secondary ms-2" onclick="limpiarBusquedaProducto()">
                                <i class="bi bi-x-circle me-1"></i> Limpiar
                            </button>
                        </div>
                    </div>
                </div>

                {{-- VISTA GRID DE BODEGAS (cuando NO hay producto seleccionado) --}}
                <div id="productos-grid-view">
                    <div class="mb-3">
                        <h3 class="chart-title">
                            <i class="bi bi-building me-2" style="color: var(--tacsa-red);"></i>
                            Existencias por bodega
                        </h3>
                        <p class="text-muted small mb-0">Haz click en una bodega para ver el detalle de sus productos</p>
                    </div>

                    <div class="row g-3 mb-4">
                        @foreach ($warehousesWithLocationsDTO as $warehouse)
                            <div class="col-lg-4 col-md-6">
                                <div class="reporte-card" style="cursor: pointer; transition: transform 0.15s;"
                                    onclick="verDetalleBodega({{ $warehouse->getId() }}, 
                                    '{{ $warehouse->getWarehouseName() }}',
                                    '{{ $stockSummary[$warehouse->getId()]->getStockTotal() }}',
                                    '{{ $stockSummary[$warehouse->getId()]->getProductTotal() }}',
                                    '{{ isset($statsWarehouses[1][$warehouse->getId()])
                                        ? $statsWarehouses[1][$warehouse->getId()]->getTotalStock()
                                        : 0 }}',
                                                        '{{ isset($statsWarehouses[2][$warehouse->getId()])
                                                            ? $statsWarehouses[2][$warehouse->getId()]->getTotalStock()
                                                            : 0 }}',
                                                        '{{ $stockSummary[$warehouse->getId()]->getTotalExpiredInventory() }}')"
                                    onmouseover="this.style.transform='translateY(-4px)'"
                                    onmouseout="this.style.transform='translateY(0)'">

                                    <div class="d-flex align-items-center gap-3 mb-3">
                                        <div
                                            style="background: var(--tacsa-red); width: 48px; height: 48px; border-radius: 12px; display: flex; align-items: center; justify-content: center; color: white;">
                                            <i class="bi bi-building" style="font-size: 24px;"></i>
                                        </div>
                                        <div style="flex: 1; min-width: 0;">
                                            <h4 class="mb-0"
                                                style="font-size: 14px; font-weight: 600; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                                                {{ $warehouse->getWarehouseName() }}
                                            </h4>
                                            <p class="text-muted small mb-0">{{ $warehouse->getHeadquartersName() }}</p>
                                        </div>
                                    </div>

                                    <div class="row g-2">
                                        <div class="col-6">
                                            <div
                                                style="background: #f8fafc; padding: 10px; border-radius: 8px; text-align: center;">
                                                <div class="text-muted" style="font-size: 11px;">Stock total</div>
                                                <div style="font-size: 20px; font-weight: 700;">
                                                    {{ $stockSummary[$warehouse->getId()]->getStockTotal() }}</div>
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <div
                                                style="background: #f8fafc; padding: 10px; border-radius: 8px; text-align: center;">
                                                <div class="text-muted" style="font-size: 11px;">Productos</div>
                                                <div style="font-size: 20px; font-weight: 700;">
                                                    {{ $stockSummary[$warehouse->getId()]->getProductTotal() }}</div>
                                            </div>
                                        </div>
                                        <div class="col-4">
                                            <div
                                                style="background: #EAF3DE; color: #173404; padding: 6px; border-radius: 6px; text-align: center; font-size: 11px;">
                                                <div style="font-weight: 600;">
                                                    {{ isset($statsWarehouses[1][$warehouse->getId()])
                                                        ? $statsWarehouses[1][$warehouse->getId()]->getTotalStock()
                                                        : 0 }}
                                                </div>
                                                <div style="opacity: 0.8;">Vigentes</div>
                                            </div>
                                        </div>
                                        <div class="col-4">
                                            <div
                                                style="background: #FAEEDA; color: #633806; padding: 6px; border-radius: 6px; text-align: center; font-size: 11px;">
                                                <div style="font-weight: 600;">
                                                    {{ isset($statsWarehouses[2][$warehouse->getId()])
                                                        ? $statsWarehouses[2][$warehouse->getId()]->getTotalStock()
                                                        : 0 }}
                                                </div>
                                                <div style="opacity: 0.8;">Por caducar</div>
                                            </div>
                                        </div>
                                        <div class="col-4">
                                            <div
                                                style="background: #FCEBEB; color: #791F1F; padding: 6px; border-radius: 6px; text-align: center; font-size: 11px;">
                                                <div style="font-weight: 600;">
                                                    {{ $stockSummary[$warehouse->getId()]->getTotalExpiredInventory() }}
                                                </div>
                                                <div style="opacity: 0.8;">Vencidos</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>


                </div>

                {{-- VISTA DETALLE DE PRODUCTO (cuando SÍ hay producto seleccionado) --}}
                {{-- VISTA DETALLE BODEGA (tabla de productos) --}}
                <div id="productos-detalle-bodega" style="display: none;">

                    {{-- Breadcrumb --}}
                    <div class="mb-3"
                        style="display: flex; align-items: center; gap: 8px; font-size: 13px; color: #64748b;">
                        <a href="#" onclick="volverAGridBodegas(); return false;"
                            style="color: var(--tacsa-red); text-decoration: none;">
                            <i class="bi bi-arrow-left me-1"></i> Existencias
                        </a>
                        <i class="bi bi-chevron-right" style="font-size: 12px;"></i>
                        <span style="color: #1e293b; font-weight: 500;" id="breadcrumbBodegaNombre"></span>
                    </div>

                    {{-- Header bodega --}}
                    <div class="reporte-card mb-3" id="header_kpis">
                        <div
                            style="display: flex; justify-content: space-between; align-items: start; flex-wrap: wrap; gap: 1rem;">
                            <div style="display: flex; align-items: center; gap: 12px;">
                                <div
                                    style="background: var(--tacsa-red); width: 48px; height: 48px; border-radius: 12px; display: flex; align-items: center; justify-content: center; color: white;">
                                    <i class="bi bi-building" style="font-size: 24px;"></i>
                                </div>
                                <div>
                                    <h2 style="font-size: 18px; font-weight: 600; margin: 0 0 4px;"
                                        id="detalleBodegaNombre"></h2>
                                    <p style="font-size: 13px; color: #64748b; margin: 0;">Planta · Actualizado hace 2
                                        horas</p>
                                </div>
                            </div>
                            <div class="d-flex gap-2">
                                <button class="btn btn-sm btn-outline-secondary">
                                    <i class="bi bi-download me-1"></i> Excel
                                </button>
                                <button class="btn btn-sm btn-outline-secondary">
                                    <i class="bi bi-printer me-1"></i> Imprimir
                                </button>
                            </div>
                        </div>

                        {{-- KPIs bodega --}}
                        <div class="row g-2 mt-3">
                            <div class="col-6 col-md">
                                <div style="background: #f8fafc; padding: 12px; border-radius: 8px;">
                                    <div class="text-muted" style="font-size: 11px; margin-bottom: 4px;">Stock total</div>
                                    <div style="font-size: 20px; font-weight: 700;" id="kpiStockTotal">-</div>
                                </div>
                            </div>
                            <div class="col-6 col-md">
                                <div style="background: #f8fafc; padding: 12px; border-radius: 8px;">
                                    <div class="text-muted" style="font-size: 11px; margin-bottom: 4px;">Productos</div>
                                    <div style="font-size: 20px; font-weight: 700;" id="kpiProductos">-</div>
                                </div>
                            </div>
                            <div class="col-4 col-md">
                                <div style="background: #EAF3DE; padding: 12px; border-radius: 8px;">
                                    <div style="font-size: 11px; color: #173404; margin-bottom: 4px;">Vigentes</div>
                                    <div style="font-size: 20px; font-weight: 700; color: #3B6D11;" id="kpiVigentes">-
                                    </div>
                                </div>
                            </div>
                            <div class="col-4 col-md">
                                <div style="background: #FAEEDA; padding: 12px; border-radius: 8px;">
                                    <div style="font-size: 11px; color: #633806; margin-bottom: 4px;">Por caducar</div>
                                    <div style="font-size: 20px; font-weight: 700; color: #854F0B;" id="kpiPorCaducar">-
                                    </div>
                                </div>
                            </div>
                            <div class="col-4 col-md">
                                <div style="background: #FCEBEB; padding: 12px; border-radius: 8px;">
                                    <div style="font-size: 11px; color: #791F1F; margin-bottom: 4px;">Vencidos</div>
                                    <div style="font-size: 20px; font-weight: 700; color: #A32D2D;" id="kpiVencidos">-
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Filtros --}}
                    <div class="reporte-filter-bar" id="secondary_saerch">
                        <div class="row g-3 align-items-end">
                            <div class="col-md-4">
                                <label class="form-label small fw-medium">Buscar producto</label>
                                <input type="text" class="form-control form-control-sm" id="filterDetalleBuscar"
                                    placeholder="Nombre o código" onkeyup="filtrarTablaDetalleBodega()">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label small fw-medium">Estado</label>
                                <select class="form-select form-select-sm" id="filterDetalleEstado"
                                    onchange="filtrarTablaDetalleBodega()">
                                    <option value="">Todos</option>
                                    <option value="vigente">Vigentes</option>
                                    <option value="por_caducar">Por caducar</option>
                                    <option value="vencido">Vencidos</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label small fw-medium">Ordenar por</label>
                                <select class="form-select form-select-sm" id="filterDetalleOrden"
                                    onchange="filtrarTablaDetalleBodega()">
                                    <option value="caducidad">Caducidad próxima</option>
                                    <option value="stock_desc">Stock descendente</option>
                                    <option value="nombre">Nombre A-Z</option>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <button class="btn btn-sm btn-outline-secondary w-100" onclick="limpiarFiltrosDetalle()">
                                    <i class="bi bi-x-circle me-1"></i> Limpiar
                                </button>
                            </div>
                        </div>
                    </div>

                    {{-- Tabla productos --}}
                    <div class="reporte-card">
                        <h3 class="chart-title mb-3">
                            <i class="bi bi-package me-2" style="color: var(--tacsa-red);"></i>
                            Productos en bodega
                        </h3>
                        <div class="table-responsive">
                            <table class="table table-hover table-semaforo mb-0">
                                <thead>
                                    <tr>
                                        <th style="display: none;" id="warehouse_column">Bodega</th>
                                        <th>Código</th>
                                        <th>Producto</th>
                                        <th class="text-center">Cantidad</th>
                                    </tr>
                                </thead>
                                <tbody id="tableDetalleBodegaBody">
                                    <tr>
                                        <td colspan="7" class="text-center text-muted py-5">
                                            <i class="bi bi-hourglass-split me-2"></i>Cargando productos...
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="mt-2 text-muted small" id="countDetalleBodega"></div>
                    </div>

                </div>{{-- /productos-detalle-bodega --}}

            </div>{{-- /tab-productos --}}


            {{-- =========================================== --}}
            {{--         TAB 4: MOVIMIENTOS                  --}}
            {{-- =========================================== --}}
            <div id="tab-movimientos" class="tab-panel">

                <div class="reporte-filter-bar">
                    <div class="row g-3 align-items-end">
                        <div class="col-md-3">
                            <label class="form-label small fw-medium">Fecha Inicio</label>
                            <input type="date" class="form-control form-control-sm" id="movFechaInicio"
                                value="{{ now()->startOfMonth()->format('Y-m-d') }}">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label small fw-medium">Fecha Fin</label>
                            <input type="date" class="form-control form-control-sm" id="movFechaFin"
                                value="{{ now()->format('Y-m-d') }}">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label small fw-medium">Bodegas</label>
                            <select class="form-select form-select-sm" id="movAlmacen">
                                <option value="">Todos las bodegas</option>
                                @foreach ($almacenes as $almacen)
                                    <option value="{{ $almacen->id }}">{{ $almacen->warehouses_name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label small fw-medium">Tipo</label>
                            <select class="form-select form-select-sm" id="movTipo">
                                <option value="">Todos</option>
                                <option value="IN">Entradas</option>
                                <option value="OUT">Salidas</option>
                                <option value="TRANSFER">Traslados</option>
                                <option value="ADJUSTMENT">Ajustes</option>
                                <option value="RELOCATION">Reubicación</option>
                                <option value="SALE">Venta</option>
                            </select>
                        </div>
                        <div class="col-12">
                            <button class="btn btn-sm btn-danger" onclick="filtrarMovimientos()">
                                <i class="bi bi-search me-1"></i> Buscar
                            </button>
                            <button class="btn btn-sm btn-outline-secondary ms-2" onclick="limpiarFiltrosMovimientos()">
                                <i class="bi bi-x-circle me-1"></i> Limpiar
                            </button>
                        </div>
                    </div>
                </div>

                {{-- Stats --}}
                <div class="row g-3 mb-4">
                    <div class="col-6 col-md-3">
                        <div class="movement-stat-card">
                            <div class="movement-stat-icon text-success"><i class="bi bi-arrow-down-circle-fill"></i>
                            </div>
                            <div class="movement-stat-value text-success" id="statEntradas">-</div>
                            <div class="movement-stat-label">Entradas</div>
                        </div>
                    </div>
                    <div class="col-6 col-md-3">
                        <div class="movement-stat-card">
                            <div class="movement-stat-icon text-danger"><i class="bi bi-arrow-up-circle-fill"></i></div>
                            <div class="movement-stat-value text-danger" id="statSalidas">-</div>
                            <div class="movement-stat-label">Salidas</div>
                        </div>
                    </div>
                    <div class="col-6 col-md-3">
                        <div class="movement-stat-card">
                            <div class="movement-stat-icon text-primary"><i class="bi bi-arrow-left-right"></i></div>
                            <div class="movement-stat-value text-primary" id="statTraslados">-</div>
                            <div class="movement-stat-label">Traslados</div>
                        </div>
                    </div>
                    <div class="col-6 col-md-3">
                        <div class="movement-stat-card">
                            <div class="movement-stat-icon text-warning"><i class="bi bi-sliders"></i></div>
                            <div class="movement-stat-value text-warning" id="statAjustes">-</div>
                            <div class="movement-stat-label">Ajustes</div>
                        </div>
                    </div>

                    <div class="col-6 col-md-3">
                        <div class="movement-stat-card">
                            <div class="movement-stat-icon text-danger"><i class="bi bi-arrows-move"></i></div>
                            <div class="movement-stat-value text-danger" id="statRelocation">-</div>
                            <div class="movement-stat-label">Reubicación</div>
                        </div>
                    </div>

                    <div class="col-6 col-md-3">
                        <div class="movement-stat-card">
                            <div class="movement-stat-icon text-danger"><i class="bi bi-cart-check-fill"></i></div>
                            <div class="movement-stat-value text-danger" id="statSale">-</div>
                            <div class="movement-stat-label">Venta</div>
                        </div>
                    </div>
                </div>

                {{-- Tabla movimientos --}}
                <div class="reporte-card">
                    <h3 class="chart-title">
                        <i class="bi bi-table me-2" style="color: var(--tacsa-red);"></i>
                        Detalle de Movimientos
                        <span class="text-muted fw-normal small ms-2" id="movRangoLabel"></span>
                    </h3>
                    <div class="table-responsive">
                        <table class="table table-hover table-semaforo mb-0">
                            <thead>
                                <tr>
                                    <th class="text-center">Folio</th>
                                    <th>Fecha</th>
                                    <th>Tipo</th>
                                    <th>Producto</th>
                                    <th>Motivo</th>
                                    <th>Bodega Origen</th>
                                    <th>Bodega Destino</th>
                                    <th>Lote</th>
                                    <th>Folio SAP</th>
                                    <th class="text-center">Cantidad</th>
                                    <th>Usuario</th>
                                </tr>
                            </thead>
                            <tbody id="tableMovimientosBody">
                                <tr>
                                    <td colspan="9" class="text-center text-muted py-5">
                                        <i class="bi bi-search me-2"></i>Aplica los filtros para consultar movimientos
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-2 text-muted small" id="countMovimientos"></div>
                </div>

            </div>{{-- /tab-movimientos --}}

            {{-- =========================================== --}}
            {{--         TAB 5: SALES                  --}}
            {{-- =========================================== --}}
            <div id="tab-sales" class="tab-panel">

                <div class="reporte-filter-bar">
                    <div class="row g-3 align-items-end">
                        <div class="col-md-3">
                            <label class="form-label small fw-medium">Fecha Inicio</label>
                            <input type="date" class="form-control form-control-sm" id="movFechaInicio"
                                value="{{ now()->startOfMonth()->format('Y-m-d') }}">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label small fw-medium">Fecha Fin</label>
                            <input type="date" class="form-control form-control-sm" id="movFechaFin"
                                value="{{ now()->format('Y-m-d') }}">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label small fw-medium">Bodega</label>
                            <select class="form-select form-select-sm" id="movAlmacen">
                                <option value="">Todos las bodegas</option>
                                @foreach ($almacenes as $almacen)
                                    <option value="{{ $almacen->id }}">{{ $almacen->warehouses_name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-12">
                            <button class="btn btn-sm btn-danger" onclick="filtrarMovimientos()">
                                <i class="bi bi-search me-1"></i> Buscar
                            </button>
                            <button class="btn btn-sm btn-outline-secondary ms-2" onclick="limpiarFiltrosMovimientos()">
                                <i class="bi bi-x-circle me-1"></i> Limpiar
                            </button>
                        </div>
                    </div>
                </div>


                {{-- Tabla movimientos --}}
                <div class="reporte-card">
                    <h3 class="chart-title">
                        <i class="bi bi-table me-2" style="color: var(--tacsa-red);"></i>
                        Detalle de ventas
                        <span class="text-muted fw-normal small ms-2" id="movRangoLabel"></span>
                    </h3>
                    <div class="table-responsive">
                        <table class="table table-hover table-semaforo mb-0">
                            <thead>
                                <tr>
                                    <th class="text-center">Folio</th>
                                    <th>Fecha</th>
                                    <th>Id. Cliente</th>
                                    <th>Folio Factura</th>
                                    <th>Cód. de Producto</th>
                                    <th>Producto</th>
                                    <th>Bodega Origen</th>
                                    <th>Lote</th>
                                    <th class="text-center">Cantidad</th>
                                    <th>Usuario</th>
                                </tr>
                            </thead>
                            <tbody id="tableMovimientosBody">
                                <tr>
                                    <td colspan="9" class="text-center text-muted py-5">
                                        <i class="bi bi-search me-2"></i>Aplica los filtros para consultar movimientos
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        <div class="table-footer" id="historialFooter">
                            <span>Mostrando <strong id="showingFrom">1</strong>-<strong id="showingTo">15</strong> de
                                <strong id="showingTotal">0</strong> movimientos</span>
                            <div class="pagination-btns" id="paginationBtns">
                                <button class="page-btn" id="prevPageBtn" onclick="changePage(-1)" disabled><i
                                        class="bi bi-chevron-left"></i></button>
                                <span id="pageNumbers"></span>
                                <button class="page-btn" id="nextPageBtn" onclick="changePage(1)"><i
                                        class="bi bi-chevron-right"></i></button>
                            </div>
                        </div>
                    </div>
                    <div class="mt-2 text-muted small" id="countMovimientos"></div>
                </div>

            </div>{{-- /tab-sales --}}

        </div>{{-- /container-fluid --}}
    </main>

    {{-- ============================================================= --}}
    {{--                    MODALES DEL SEMÁFORO                       --}}
    {{-- ============================================================= --}}

    {{-- Modal CRÍTICO --}}
    <div class="modal fade" id="modalCritical" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header critical">
                    <h5 class="modal-title modal-title-critical fw-bold">
                        <i class="bi bi-exclamation-triangle-fill me-2"></i>Productos Críticos
                        <span class="badge bg-danger ms-2">{{ $critical->getTotalStock() ?? 0 }}</span>
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="filter-section">
                        <div class="row g-3">
                            <div class="col-md-4">
                                <label class="form-label small fw-medium">Bodega</label>
                                <select class="form-select form-select-sm" id="filterWarehouseCritical"
                                    onchange="filterSemaforoTable('critical')">
                                    <option value="">Todos las bodegas</option>
                                    @foreach ($almacenes as $almacen)
                                        <option value="{{ $almacen->id }}">{{ $almacen->warehouses_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label small fw-medium">Producto</label>
                                <input type="text" class="form-control form-control-sm" id="filterProductCritical"
                                    placeholder="Buscar..." onkeyup="filterSemaforoTable('critical')">
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
                                    <th>Bodega</th>
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
                                            class="bi bi-hourglass-split me-2"></i>Cargando...</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="modal-footer justify-content-between">
                    <span class="text-muted small" id="countCritical">0 productos</span>
                    <button type="button" class="btn btn-outline-secondary btn-sm" data-bs-dismiss="modal"><i
                            class="bi bi-x-circle me-1"></i>Cerrar</button>
                </div>
            </div>
        </div>
    </div>

    {{-- Modal ATENCIÓN --}}
    <div class="modal fade" id="modalAttention" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header attention">
                    <h5 class="modal-title modal-title-attention fw-bold">
                        <i class="bi bi-clock-fill me-2"></i>Productos en Atención
                        <span class="badge bg-warning text-dark ms-2">{{ $attention->getTotalStock() ?? 0 }}</span>
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="filter-section">
                        <div class="row g-3">
                            <div class="col-md-4">
                                <label class="form-label small fw-medium">Bodega</label>
                                <select class="form-select form-select-sm" id="filterWarehouseAttention"
                                    onchange="filterSemaforoTable('attention')">
                                    <option value="">Todos las bodegas</option>
                                    @foreach ($almacenes as $almacen)
                                        <option value="{{ $almacen->id }}">{{ $almacen->warehouses_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label small fw-medium">Producto</label>
                                <input type="text" class="form-control form-control-sm" id="filterProductAttention"
                                    placeholder="Buscar..." onkeyup="filterSemaforoTable('attention')">
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
                                    <th>Bodega</th>
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
                                            class="bi bi-hourglass-split me-2"></i>Cargando...</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="modal-footer justify-content-between">
                    <span class="text-muted small" id="countAttention">0 productos</span>
                    <button type="button" class="btn btn-outline-secondary btn-sm" data-bs-dismiss="modal"><i
                            class="bi bi-x-circle me-1"></i>Cerrar</button>
                </div>
            </div>
        </div>
    </div>

    {{-- Modal OK --}}
    <div class="modal fade" id="modalOk" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header ok">
                    <h5 class="modal-title modal-title-ok fw-bold">
                        <i class="bi bi-check-circle-fill me-2"></i>Productos Vigentes
                        <span class="badge bg-success ms-2">{{ $ok->getTotalStock() ?? 0 }}</span>
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="filter-section">
                        <div class="row g-3">
                            <div class="col-md-4">
                                <label class="form-label small fw-medium">Bodega</label>
                                <select class="form-select form-select-sm" id="filterWarehouseOk"
                                    onchange="filterSemaforoTable('ok')">
                                    <option value="">Todos las bodegas</option>
                                    @foreach ($almacenes as $almacen)
                                        <option value="{{ $almacen->id }}">{{ $almacen->warehouses_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label small fw-medium">Producto</label>
                                <input type="text" class="form-control form-control-sm" id="filterProductOk"
                                    placeholder="Buscar..." onkeyup="filterSemaforoTable('ok')">
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
                                    <th>Bodega</th>
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
                                            class="bi bi-hourglass-split me-2"></i>Cargando...</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="modal-footer justify-content-between">
                    <span class="text-muted small" id="countOk">0 productos</span>
                    <button type="button" class="btn btn-outline-secondary btn-sm" data-bs-dismiss="modal"><i
                            class="bi bi-x-circle me-1"></i>Cerrar</button>
                </div>
            </div>
        </div>
    </div>

    {{-- ============================================================= --}}
    {{--              MODAL DETALLE LOTES DE PRODUCTO                  --}}
    {{-- ============================================================= --}}
    <div class="modal fade" id="modalLotesProducto" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header"
                    style="background: linear-gradient(135deg, #f8fafc, #e2e8f0); border-bottom: 2px solid var(--tacsa-red);">
                    <div>
                        <h5 class="modal-title fw-bold mb-1" style="color: #1e293b;">
                            <i class="bi bi-box-seam me-2" style="color: var(--tacsa-red);"></i>
                            <span id="modalLotesProductoNombre">Producto</span>
                        </h5>
                        <p class="mb-0 small text-muted">
                            <i class="bi bi-building me-1"></i>
                            <span id="modalLotesBodegaNombre">Bodega</span>
                            <span class="mx-2">·</span>
                            <i class="bi bi-upc me-1"></i>
                            Código: <span id="modalLotesProductoCodigo">-</span>
                        </p>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">

                    {{-- KPIs Resumen --}}
                    <div class="row g-3 mb-4">
                        <div class="col-6 col-md-3">
                            <div
                                style="background: #eb4b4bdc; border: 1px solid #e2e4f0; border-radius: 8px; padding: 12px; text-align: center;">
                                <div class="text-muted"
                                    style="font-size: 14px; margin-bottom: 4px; color: #eff1f4 !important;">En Peligro
                                </div>
                                <div style="font-size: 24px; font-weight: 700; color: #eff1f4;" id="modalLotesTotalLotes">
                                    -</div>
                            </div>
                        </div>
                        <div class="col-6 col-md-3">
                            <div
                                style="background: #f8fafc; border: 1px solid #f0e5e2; border-radius: 8px; padding: 12px; text-align: center;">
                                <div class="text-muted" style="font-size: 11px; margin-bottom: 4px;">Stock Total</div>
                                <div style="font-size: 24px; font-weight: 700; color: #1e293b;" id="modalLotesStockTotal">
                                    -</div>
                            </div>
                        </div>
                        <div class="col-6 col-md-3">
                            <div
                                style="background: #FAEEDA; border: 1px solid #f59e0b; border-radius: 8px; padding: 12px; text-align: center;">
                                <div style="font-size: 11px; color: #633806; margin-bottom: 4px;">Por Caducar</div>
                                <div style="font-size: 24px; font-weight: 700; color: #854F0B;" id="modalLotesPorCaducar">
                                    -</div>
                            </div>
                        </div>
                        <div class="col-6 col-md-3">
                            <div
                                style="background: #FCEBEB; border: 1px solid #dc2626; border-radius: 8px; padding: 12px; text-align: center;">
                                <div style="font-size: 11px; color: #791F1F; margin-bottom: 4px;">Vencidos</div>
                                <div style="font-size: 24px; font-weight: 700; color: #A32D2D;" id="modalLotesVencidos">-
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Filtros --}}
                    <div class="filter-section mb-3">
                        <div class="row g-3 align-items-end">
                            <div class="col-md-4">
                                <label class="form-label small fw-medium">Buscar lote</label>
                                <input type="text" class="form-control form-control-sm" id="filterLotesSearch"
                                    placeholder="Número de lote..." onkeyup="filtrarLotesProducto()">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label small fw-medium">Estado</label>
                                <select class="form-select form-select-sm" id="filterLotesEstado"
                                    onchange="filtrarLotesProducto()">
                                    <option value="">Todos</option>
                                    <option value="vigente">Vigentes</option>
                                    <option value="por_caducar">Por caducar</option>
                                    <option value="vencido">Vencidos</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label small fw-medium">Ordenar por</label>
                                <select class="form-select form-select-sm" id="filterLotesOrden"
                                    onchange="filtrarLotesProducto()">
                                    <option value="caducidad">Caducidad próxima</option>
                                    <option value="stock_desc">Stock descendente</option>
                                    <option value="lote">Lote</option>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <button class="btn btn-sm btn-outline-secondary w-100" onclick="limpiarFiltrosLotes()">
                                    <i class="bi bi-x-circle me-1"></i> Limpiar
                                </button>
                            </div>
                        </div>
                    </div>

                    {{-- Tabla Lotes --}}
                    <div class="table-responsive">
                        <table class="table table-hover table-semaforo mb-0">
                            <thead>
                                <tr>
                                    <th class="text-center">#</th>
                                    <th>Lote</th>
                                    <th>Ubicación</th>
                                    <th class="text-center">Cantidad</th>
                                    <th class="text-center">Fecha Caducidad</th>
                                    <th class="text-center">Días Restantes</th>
                                    <th class="text-center">Obsolescencia</th>
                                    <th class="text-center">Estado</th>
                                </tr>
                            </thead>
                            <tbody id="tableLotesProductoBody">
                                <tr>
                                    <td colspan="8" class="text-center text-muted py-5">
                                        <i class="bi bi-hourglass-split me-2"></i>Cargando lotes...
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="modal-footer justify-content-between" style="background: #f8fafc;">
                    <span class="text-muted small" id="countLotes">0 lotes</span>
                    <div class="d-flex gap-2">
                        <div class="dropdown">
                            <button type="button" class="btn btn-sm btn-outline-secondary dropdown-toggle"
                                id="btnExportLots" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="bi bi-download me-1"></i> Exportar
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="btnExportLots">
                                <li><a class="dropdown-item" href="#" onclick="exportLots('excel'); return false;">
                                        <i class="bi bi-file-earmark-excel me-2 text-success"></i>Excel (.xlsx)</a>
                                </li>
                                <li><a class="dropdown-item" href="#" onclick="exportLots('pdf'); return false;">
                                        <i class="bi bi-file-earmark-pdf me-2 text-danger"></i>PDF</a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li><h6 class="dropdown-header">Lista Conteo Físico</h6></li>
                                <li><a class="dropdown-item" href="#"
                                        onclick="exportPhysicalCount('excel'); return false;">
                                        <i class="bi bi-file-earmark-excel me-2 text-success"></i>Excel (.xlsx)</a>
                                </li>
                                <li><a class="dropdown-item" href="#"
                                        onclick="exportPhysicalCount('pdf'); return false;">
                                        <i class="bi bi-file-earmark-pdf me-2 text-danger"></i>PDF</a></li>
                            </ul>
                        </div>
                        <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal">
                            <i class="bi bi-x-circle me-1"></i> Cerrar
                        </button>
                    </div>
                </div>

                <form id="formExportLotsExcel" method="POST"
                    action="{{ route('reports.products.lots.export.excel') }}" target="_blank" class="d-none">
                    @csrf
                    <input type="hidden" name="lots" id="exportLotsExcelData">
                </form>
                <form id="formExportLotsPdf" method="POST" action="{{ route('reports.products.lots.export.pdf') }}"
                    target="_blank" class="d-none">
                    @csrf
                    <input type="hidden" name="lots" id="exportLotsPdfData">
                </form>
                <form id="formExportPhysicalCountExcel" method="POST"
                    action="{{ route('reports.products.physicalcount.export.excel') }}" target="_blank"
                    class="d-none">
                    @csrf
                    <input type="hidden" name="lots" id="exportPhysicalCountExcelData">
                </form>
                <form id="formExportPhysicalCountPdf" method="POST"
                    action="{{ route('reports.products.physicalcount.export.pdf') }}" target="_blank"
                    class="d-none">
                    @csrf
                    <input type="hidden" name="lots" id="exportPhysicalCountPdfData">
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        const defaultFechaInicio = "{{ now()->startOfMonth()->format('Y-m-d') }}";
        const defaultFechaFin = "{{ now()->format('Y-m-d') }}";

        // =============================================
        //   DATOS DESDE PHP
        // =============================================
        const semaforoData = {
            critical: {!! json_encode($semaforoCritical ?? []) !!},
            attention: {!! json_encode($semaforoAttention ?? []) !!},
            ok: {!! json_encode($semaforoOk ?? []) !!}
        };

        const expiredProducts = {!! json_encode($expiredProducts ?? []) !!};

        // ✅ Movimientos del mes desde el controller
        const movimientosData = {!! json_encode($movimientos ?? []) !!};

        let isGeneralSaerch = false;
        // =============================================
        //   CHART.JS
        // =============================================
        document.addEventListener('DOMContentLoaded', function() {
            renderVencidos();

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
                                    backgroundColor: 'rgba(15,23,42,0.9)',
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

        // =============================================
        //   TABS
        // =============================================
        function switchTab(tabName, btn) {
            document.querySelectorAll('.tab-panel').forEach(p => p.classList.remove('active'));
            document.querySelectorAll('.tab-btn').forEach(b => b.classList.remove('active'));
            document.getElementById('tab-' + tabName).classList.add('active');
            btn.classList.add('active');
        }

        // =============================================
        //   HELPERS
        // =============================================
        function formatNumber(num) {
            return new Intl.NumberFormat('es-MX').format(num);
        }

        function formatDate(dateStr) {
            if (!dateStr) return '-';
            const date = new Date(dateStr + 'T00:00:00');
            return date.toLocaleDateString('es-MX', {
                day: '2-digit',
                month: '2-digit',
                year: 'numeric'
            });
        }

        function getBadgeClass(days) {
            if (days <= 0) return 'bg-danger';
            if (days <= 30) return 'bg-danger';
            if (days <= 60) return 'bg-warning text-dark';
            return 'bg-success';
        }

        function capitalize(str) {
            return str.charAt(0).toUpperCase() + str.slice(1);
        }

        // =============================================
        //   TABLA VENCIDOS
        // =============================================
        function renderVencidos(data) {

            const tbody = document.getElementById('tableVencidosBody');
            const countEl = document.getElementById('countVencidos');
            const vencidos = (data !== undefined) ? data :
                (expiredProducts || []);

            //const vencidos = data;
            if (!vencidos || vencidos.length === 0) {
                tbody.innerHTML = `<tr><td colspan="8" class="text-center text-muted py-5">
                <i class="bi bi-check-circle-fill me-2 text-success"></i>No hay productos vencidos. ¡Excelente!
            </td></tr>`;
                if (countEl) countEl.textContent = '';
                return;
            }


            tbody.innerHTML = vencidos.map((item, i) =>
                `
            <tr class="table-danger">
                <td class="text-center text-muted">${i + 1}</td>
                <td class="fw-medium">${item.productName || 'N/A'}</td>
                <td><span class="badge bg-secondary">${item.warehouseName || 'N/A'}</span></td>
                <td>
                    <span class="badge bg-dark me-1">${item.rack || '-'}</span>
                    <strong>Nv.${item.level || '-'}</strong>
                </td>
                <td><small>${item.lotNumber || '-'}</small></td>
                <td class="text-center fw-bold">${formatNumber(item.quantity || 0)}</td>
                <td class="text-center">${item.expirationDate}</td>
                <td class="text-center">
                    <span class="badge bg-danger">
                        <i class="bi bi-x-circle me-1"></i>
                        Vencido hace ${item.expiredDays} días
                    </span>
                </td>
            </tr>
        `).join('');

            if (countEl) countEl.textContent =
                `${vencidos.length} producto${vencidos.length !== 1 ? 's' : ''} vencido${vencidos.length !== 1 ? 's' : ''}`;
        }

        function filterVencidos() {
            const search = (document.getElementById('filterVencidosSearch')?.value || '').toLowerCase();

            const almacen = document.getElementById('filterVencidosAlmacen')?.value || '';
            let data = (expiredProducts || []);
            let isDigit = isNaN(search);



            if (almacen) data = data.filter(p => String(p.warehouseId) === almacen);
            if (search && isDigit) data = data.filter(p =>
                (p.productName || '').toLowerCase().includes(search) ||
                (p.lotNumber || '').toLowerCase().includes(search) ||
                (p.rack || '').toLowerCase().includes(search)
            );


            let objectiveNumber = 0;
            if (search && !isDigit) {
                objectiveNumber = Number(search);

                data = data.filter(p =>
                    p.level === objectiveNumber
                );
            }

            renderVencidos(data);
        }

        // =============================================
        //   MODALES SEMÁFORO
        // =============================================
        function renderTableData(data, tbodyId, countId) {
            const tbody = document.getElementById(tbodyId);
            const countEl = document.getElementById(countId);
            if (!tbody) return;

            if (!data || data.length === 0) {
                tbody.innerHTML = `<tr><td colspan="11" class="text-center text-muted py-4">
                <i class="bi bi-inbox-fill me-2"></i>No hay productos en esta categoría</td></tr>`;
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
                <td class="text-center">${item.expirationDate}</td>
                <td class="text-center">
                    <span class="badge ${getBadgeClass(item.remainingDays)}">
                        ${item.remainingDays < 0 ? 'Vencido' : item.remainingDays + ' días'}
                    </span>
                </td>
                <td class="text-center">
                        <div class="d-flex align-items-center justify-content-center gap-1">
                            <div class="progress" style="height: 6px; width: 50px;">
                                <div class="progress-bar ${(item.obsolescence || 0) > 80 ? 'bg-danger' : (item.obsolescence || 0) > 50 ? 'bg-warning' : 'bg-success'}" 
                                     role="progressbar" 
                                     style="width: ${Math.min(100, Math.abs(item.obsolescence || 0))}%">
                                </div>
                            </div>
                            <span class="small fw-medium">${(item.obsolescence || 0)}%</span>
                        </div>
                    </td>
            </tr>
        `).join('');

            if (countEl) countEl.textContent = `${data.length} producto${data.length !== 1 ? 's' : ''}`;
        }

        let currentModal = null;

        function openSemaforoModal(type) {
            const modalId = type === 'critical' ? 'modalCritical' : type === 'attention' ? 'modalAttention' : 'modalOk';
            const tbodyId = type === 'critical' ? 'tableCriticalBody' : type === 'attention' ? 'tableAttentionBody' :
                'tableOkBody';
            renderTableData(semaforoData[type] || [], tbodyId, `count${capitalize(type)}`);
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

        // =============================================
        //   MOVIMIENTOS
        // =============================================
        const tipoConfig = {
            entrada: {
                label: 'Entrada',
                badge: 'bg-success',
                icon: 'bi-arrow-down-circle-fill'
            },
            salida: {
                label: 'Salida',
                badge: 'bg-danger',
                icon: 'bi-arrow-up-circle-fill'
            },
            traslado: {
                label: 'Traslado',
                badge: 'bg-primary',
                icon: 'bi-arrow-left-right'
            },
            ajuste: {
                label: 'Ajuste',
                badge: 'bg-warning text-dark',
                icon: 'bi-sliders'
            },


            out: {
                label: 'Salida',
                badge: 'bg-danger',
                icon: 'bi-arrow-up-circle-fill'
            },
            ventas: {
                label: 'Venta',
                badge: 'bg-danger',
                icon: 'bi-cart-fill'
            },
            in: {
                label: 'Entrada',
                badge: 'bg-success',
                icon: 'bi-arrow-down-circle-fill'
            },
            transfer: {
                label: 'Traslado',
                badge: 'bg-primary',
                icon: 'bi-arrow-left-right'
            },
            reubicacion: {
                label: 'Reubicación',
                badge: 'bg-info text-dark',
                icon: 'bi-arrows-move'
            },
        };

        async function filtrarMovimientos() {

            const inicio = document.getElementById('movFechaInicio').value;
            const fin = document.getElementById('movFechaFin').value;
            const almacen = document.getElementById('movAlmacen').value;
            const tipo = document.getElementById('movTipo').value;
            let result;
            let movementsRecords, statics;


            result = await getWarehouseMovements({
                startDate: inicio,
                endDate: fin,
                warehouseId: almacen,
                movementType: tipo
            });

            if (result.success) {
                movementsRecords = result.data.details;
                statics = result.data.statistics;
            }


            const data = movementsRecords;

            // Stats
            const counts = {
                entrada: 0,
                salida: 0,
                traslado: 0,
                ajuste: 0
            };



            document.getElementById('statEntradas').textContent = statics["IN"];
            document.getElementById('statSalidas').textContent = statics["OUT"];
            document.getElementById('statTraslados').textContent = statics["TRANSFER"];
            document.getElementById('statAjustes').textContent = statics["ADJUSTMENT"];
            document.getElementById('statRelocation').textContent = statics["RELOCATION"];
            document.getElementById('statSale').textContent = statics["SALE"];

            const rangoEl = document.getElementById('movRangoLabel');
            if (rangoEl && inicio && fin) rangoEl.textContent = `Del ${formatDate(inicio)} al ${formatDate(fin)}`;

            renderMovimientos(data);
        }

        async function getWarehouseMovements(filters) {

            const API_ROUTES = {
                filterMovements: "{{ route('reports.filter.by.range') }}"
            };

            try {
                // Construir los parámetros de la URL
                const params = new URLSearchParams({
                    start_date: filters.startDate,
                    end_date: filters.endDate
                });

                if (filters.warehouseId) {
                    params.append(
                        'warehouse_id',
                        filters.warehouseId
                    );
                }

                if (filters.movementType) {
                    params.append(
                        'movement_type',
                        filters.movementType
                    );
                }


                // Usar la URL generada por Laravel
                const response = await fetch(`${API_ROUTES.filterMovements}?${params.toString()}`, {
                    method: 'GET',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    }
                });


                const data = await response.json();

                if (!response.ok) {
                    throw new Error(data.message ||
                        'Error al obtener los movimientos');
                }

                return data;

            } catch (error) {
                console.error(
                    "Error al consultar los movimientos " +
                    "favor de contactar a soporte técnico " + error
                );
            }
        }

        function renderMovimientos(data) {
            const tbody = document.getElementById('tableMovimientosBody');
            const countEl = document.getElementById('countMovimientos');


            if (!data || data.length === 0) {
                tbody.innerHTML = `<tr><td colspan="9" class="text-center text-muted py-5">
                <i class="bi bi-inbox-fill me-2"></i>No se encontraron movimientos con los filtros aplicados
            </td></tr>`;
                if (countEl) countEl.textContent = '';
                return;
            }

            tbody.innerHTML = data.map((mov, i) => {
                const t = (mov.movementType || '').toLowerCase();
                const cfg = tipoConfig[t] || {
                    label: mov.movementType || '-',
                    badge: 'bg-secondary',
                    icon: 'bi-circle'
                };
                return `
            <tr>
                <td class="text-center text-muted">${mov.folio}</td>
                <td>${formatDate(mov.createdAt)}</td>
                <td><span class="badge ${cfg.badge}"><i class="bi ${cfg.icon} me-1"></i>${cfg.label}</span></td>
                <td class="fw-medium">${mov.productName || 'N/A'}</td>
                <td class="fw-medium">${mov.reason || 'N/A'}</td>
                <td><span class="badge bg-secondary">${mov.warehousesName || '-'}</span></td>
                <td><span class="badge bg-secondary">${mov.destinationWarehouseName || '-'}</span></td>
                <td><small>${mov.lotNumber || '-'}</small></td>
                <td><span class="badge bg-secondary">
                    ${mov.movementType === 'Ventas' ? (mov.invoiceSap || '-') : (mov.movementType === 'Traslado' ? (mov.transferFolio || '-') : '-')}</span></td>
                <td class="text-center fw-bold">${formatNumber(mov.quantity || 0)}</td>
                <td><small class="text-muted">${mov.userName || '-'}</small></td>
            </tr>`;
            }).join('');

            if (countEl) countEl.textContent = `${data.length} movimiento${data.length !== 1 ? 's' : ''}`;
        }

        function limpiarFiltrosMovimientos() {
            document.getElementById('movAlmacen').value = '';
            document.getElementById('movTipo').value = '';
            document.getElementById('movFechaInicio').value = defaultFechaInicio;
            document.getElementById('movFechaFin').value = defaultFechaFin;
            document.getElementById('tableMovimientosBody').innerHTML = `
            <tr><td colspan="9" class="text-center text-muted py-5">
                <i class="bi bi-search me-2"></i>Aplica los filtros para consultar movimientos
            </td></tr>`;
            document.getElementById('countMovimientos').textContent = '';
            ['statEntradas', 'statSalidas', 'statTraslados', 'statAjustes', 'statRelocation', 'statSale'].forEach(id => {
                document.getElementById(id).textContent = '-';
            });
            const rangoEl = document.getElementById('movRangoLabel');
            if (rangoEl) rangoEl.textContent = '';
        }

        // =============================================
        //   TAB PRODUCTOS (NUEVO)
        // =============================================

        // Variable global para almacenar productos de la bodega actual
        let productosDetalleBodega = [];
        let productStockDetails = [];
        let currentWarehouseId = 0;
        let warehouseName = "";
        let productosDetalleBodegaFiltrados = [];

        async function buscarProducto() {
            isGeneralSaerch = true;
            const producto = document.getElementById('prodProducto').value;
            const almacen = document.getElementById('prodAlmacen').value;
            const periodo = document.getElementById('prodPeriodo').value;
            const estado = document.getElementById('prodEstado').value;

            document.getElementById('productos-grid-view').style.display = 'none';
            document.getElementById('header_kpis').style.display = 'none';
            document.getElementById('secondary_saerch').style.display = 'none';

            document.getElementById('productos-detalle-bodega').style.display = 'block';

            if (!producto.trim()) {
                alert('Por favor ingresa un nombre de producto');
                return;
            }


            // TODO: Aquí harías un fetch al backend para obtener análisis del producto
            console.log('Buscando producto:', {
                producto,
                almacen,
                periodo,
                estado
            });


            try {
                const response = await fetch(`/inventory/search/${producto}`, {
                    method: 'GET',
                    headers: {
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    }
                });

                document.getElementById('warehouse_column').style.display = 'block';
                const data = await response.json();
                
                renderTablaDetalleBodega(data.products);
            } catch (error) {
                console.log(
                    "Has been ocurred one error " +
                    error.message);
            }
        }

        function limpiarBusquedaProducto() {
            document.getElementById('prodProducto').value = '';
            document.getElementById('prodAlmacen').value = '';
            document.getElementById('prodPeriodo').value = '6';
            document.getElementById('prodEstado').value = '';

            volverAGridBodegas();
        }

        async function verDetalleBodega(
            almacenId,
            almacenNombre,
            stock,
            totalProduct,
            current,
            expiringSoon,
            expired
        ) {

            if (isGeneralSaerch) {
                document.getElementById('warehouse_column').style.display = 'none';
                document.getElementById('header_kpis').style.display = 'block';
                document.getElementById('secondary_saerch').style.display = 'block';

                isGeneralSaerch = false;
            }

            try {
                // Mostrar loading
                document.getElementById('tableDetalleBodegaBody').innerHTML = `
            <tr><td colspan="7" class="text-center text-muted py-5">
                <div class="spinner-border spinner-border-sm text-danger me-2"></div>
                Cargando productos de ${almacenNombre}...
            </td></tr>`;

                // Ocultar grid, mostrar detalle
                document.getElementById('productos-grid-view').style.display = 'none';
                document.getElementById('productos-detalle-bodega').style.display = 'block';

                // Actualizar breadcrumb y header
                document.getElementById('breadcrumbBodegaNombre').textContent = almacenNombre;
                document.getElementById('detalleBodegaNombre').textContent = almacenNombre;
                actualizarKPIsBodega(
                    stock,
                    totalProduct,
                    current,
                    expiringSoon,
                    expired
                );
                // Fetch datos del backend
                const response = await fetch(`/reports/products/warehouse/${almacenId}`, {
                    method: 'GET',
                    headers: {
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    }
                });

                currentWarehouseId = almacenId;
                warehouseName = almacenNombre;
                if (!response.ok) throw new Error('Error al obtener productos');

                const data = await response.json();

                productosDetalleBodega = data.products || [];
                // Renderizar tabla
                productosDetalleBodegaFiltrados = [...productosDetalleBodega];

                renderTablaDetalleBodega(
                    productosDetalleBodegaFiltrados,
                    almacenId, almacenNombre);

            } catch (error) {
                console.error('Error:', error);
                document.getElementById('tableDetalleBodegaBody').innerHTML = `
            <tr><td colspan="7" class="text-center text-danger py-5">
                <i class="bi bi-exclamation-triangle me-2"></i>
                Error al cargar productos. Por favor intenta nuevamente.
            </td></tr>`;
            }
        }

        function actualizarKPIsBodega(
            stock,
            totalProducts,
            currentProducts,
            expiringSoon,
            expired
        ) {
            document.getElementById('kpiStockTotal').textContent = stock;
            document.getElementById('kpiProductos').textContent = totalProducts;
            document.getElementById('kpiVigentes').textContent = currentProducts;
            document.getElementById('kpiPorCaducar').textContent = expiringSoon;
            document.getElementById('kpiVencidos').textContent = expired;
        }

        function renderTablaDetalleBodega(
            productos,
            warehouseId,
            warehouseName) {

            console.log(
                "The warehouse id in turn is: " +
                warehouseId + " and warehouse name is: " +
                warehouseName
            );

            const tbody = document.getElementById('tableDetalleBodegaBody');
            const countEl = document.getElementById('countDetalleBodega');

            if (!productos || productos.length === 0) {
                tbody.innerHTML = `
            <tr><td colspan="7" class="text-center text-muted py-5">
                <i class="bi bi-inbox-fill me-2"></i>No se encontraron productos
            </td></tr>`;
                if (countEl) countEl.textContent = '';
                return;
            }

            tbody.innerHTML = productos.map(p => {


                return `
            <tr>
                <td class="warehouse-column" style="display: ${isGeneralSaerch ? 'table-cell' : 'none'};">
                    ${p.warehouse_name || 'N/A'}
                </td>

                <td>
                    <div style="font-weight: 500; margin-bottom: 2px;">${p.product_id || 'N/A'}</div>
                    <div style="font-size: 11px; color: #64748b;">${p.product_name || ''}</div>
                </td>
                <td class="text-center" style="font-weight: 700;">${p.product_name}</td>
                <td class="text-center" style="font-weight: 700;">${formatNumber(p.stock || 0)}</td>
                
                <td class="text-center">
                    <button class="btn btn-sm btn-outline-secondary" onclick="verAnalisisProducto('${p.product_name}','${p.product_id}','${isGeneralSaerch ? p.warehouse_id:warehouseId}','${isGeneralSaerch ? p.warehouse_name : warehouseName}', '${p.stock}')">
                        Ver detalle ↗
                    </button>
                </td>
            </tr>`;
            }).join('');

            if (countEl) countEl.textContent = `${productos.length} producto${productos.length !== 1 ? 's' : ''}`;
        }

        function filtrarTablaDetalleBodega() {
            const buscar = (document.getElementById('filterDetalleBuscar')?.value || '').toLowerCase();
            const estado = document.getElementById('filterDetalleEstado')?.value || '';
            const orden = document.getElementById('filterDetalleOrden')?.value || 'caducidad';

            let filtrados = [...productosDetalleBodega];

            // Filtrar por búsqueda
            // alert("The element to saerch is: " + buscar);
            if (buscar && buscar != '') {
                //alert("Entered here!")
                filtrados = filtrados.filter(p =>
                    (p.product_name || '').toLowerCase().includes(buscar) ||
                    (p.product_id || '').toLowerCase().includes(buscar) ||
                    (p.lot_number || '').toLowerCase().includes(buscar)
                );
            }

            // Filtrar por estado
            if (estado) {
                filtrados = filtrados.filter(p => {
                    const dias = p.dias_restantes;

                    if (estado === 'vencido') return dias < 0;
                    if (estado === 'por_caducar') return dias >= 0 && dias <= 90;
                    if (estado === 'vigente') return dias > 90;
                    return true;
                });
            }

            // Ordenar
            if (orden === 'caducidad') {
                filtrados.sort((a, b) => a.dias_restantes - b.dias_restantes);
            } else if (orden === 'stock_desc') {
                filtrados.sort((a, b) => b.quantity - a.quantity);
            } else if (orden === 'nombre') {
                filtrados.sort((a, b) => (a.product_name || '').localeCompare(b.product_name || ''));
            }

            console.log(
                "The current warehouse id is: " +
                currentWarehouseId);
            productosDetalleBodegaFiltrados = filtrados;
            renderTablaDetalleBodega(
                filtrados,
                currentWarehouseId,
                warehouseName
            );
        }

        function limpiarFiltrosDetalle() {
            document.getElementById('filterDetalleBuscar').value = '';
            document.getElementById('filterDetalleEstado').value = '';
            document.getElementById('filterDetalleOrden').value = 'caducidad';
            filtrarTablaDetalleBodega();
        }

        function volverAGridBodegas() {
            document.getElementById('productos-grid-view').style.display = 'block';
            document.getElementById('productos-detalle-bodega').style.display = 'none';
            productosDetalleBodega = [];
            productosDetalleBodegaFiltrados = [];
        }

        async function verAnalisisProducto(
            productoNombre, productCode,
            warehouseId, warehouseName, stock) {

            let kpis = {};

            try {
                const response = await fetch(
                    `/reports/products/stock/warehouses/${warehouseId}/products/${productCode}`, {
                        method: 'GET',
                        headers: {
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                        }
                    });

                if (!response.ok) throw new Error('Error al obtener productos');

                const data = await response.json();

                productStockDetails = data.products || [];
                kpis = data.kpis || {};

            } catch (error) {
                console.error('Error:', error);
            }

            actualizarKPIsLotes(stock, kpis);

            document.getElementById(
                'modalLotesProductoNombre').textContent = productoNombre;
            document.getElementById(
                'modalLotesBodegaNombre').textContent = warehouseName;
            document.getElementById(
                'modalLotesProductoCodigo').textContent = productCode;
            renderTableLotsProduct(productStockDetails);
            const modal = new bootstrap.Modal(
                document.getElementById(
                    'modalLotesProducto'));
            modal.show();
        }

        function actualizarKPIsLotes(stock, kpis = {}) {
            document.getElementById('modalLotesStockTotal').textContent = formatNumber(stock || 0);
            document.getElementById('modalLotesTotalLotes').textContent = formatNumber(kpis.endangered || 0);
            document.getElementById('modalLotesPorCaducar').textContent = formatNumber(kpis.expiredSoon || 0);
            document.getElementById('modalLotesVencidos').textContent = formatNumber(kpis.expired || 0);
        }

        function limpiarBusquedaProducto() {
            document.getElementById('prodProducto').value = '';
            document.getElementById('prodAlmacen').value = '';
            document.getElementById('prodPeriodo').value = '6';
            document.getElementById('prodEstado').value = '';

            // Volver a vista grid
            document.getElementById('productos-grid-view').style.display = 'block';
            document.getElementById('productos-detalle-view').style.display = 'none';
        }

        function renderTableLotsProduct(lotes) {
            //alert(JSON.stringify(lotes));
            const tbody = document.getElementById('tableLotesProductoBody');
            const countEl = document.getElementById('countLotes');

            if (!lotes || lotes.length === 0) {
                tbody.innerHTML = `
            <tr><td colspan="8" class="text-center text-muted py-5">
                <i class="bi bi-inbox-fill me-2"></i>No se encontraron lotes
            </td></tr>`;
                if (countEl) countEl.textContent = '0 lotes';
                return;
            }

            tbody.innerHTML = lotes.map((lote, index) => {
                const diasRestantes = lote.remainingDays;
                let estadoBadge, bgRow;

                if (diasRestantes < 0) {
                    estadoBadge = '<span class="badge bg-danger">Vencido</span>';
                    bgRow = 'table-danger';
                } else if (diasRestantes <= 90) {
                    estadoBadge = '<span class="badge bg-warning text-dark">Por caducar</span>';
                    bgRow = 'table-warning';
                } else {
                    estadoBadge = '<span class="badge bg-success">Vigente</span>';
                    bgRow = '';
                }

                // Construir ubicación completa
                const ubicacion = [
                    lote.rack ? `Rack ${lote.rack}` : '',
                    lote.level ? `Nv.${lote.level}` : '',
                    lote.module ? `Mod.${lote.module}` : '',
                    lote.bay ? `Bahía ${lote.bay}` : '',
                    lote.platform ? `Plat.${lote.platform}` : ''
                ].filter(Boolean).join(' · ') || 'Sin ubicación';

                return `
            <tr class="${bgRow}">
                <td class="text-center text-muted">${index + 1}</td>
                <td><span style="font-family: monospace; font-weight: 500;">${lote.lotNumber || '-'}</span></td>
                <td><small style="font-size: 11px;">${ubicacion}</small></td>
                <td class="text-center fw-bold">${formatNumber(lote.quantity || 0)}</td>
                <td class="text-center">${lote.expirationDate || '-'}</td>
                <td class="text-center">
                    <span class="badge ${diasRestantes < 0 ? 'bg-danger' : diasRestantes <= 90 ? 'bg-warning text-dark' : 'bg-success'}">
                        ${diasRestantes < 0 ? `Vencido hace ${Math.abs(diasRestantes)} días` : `${diasRestantes} días`}
                    </span>
                </td>
                <td class="text-center">
                    <div class="d-flex align-items-center justify-content-center gap-1">
                        <div class="progress" style="height: 6px; width: 50px;">
                            <div class="progress-bar ${(lote.obsolescence || 0) > 80 ? 'bg-danger' : (lote.obsolescence || 0) > 50 ? 'bg-warning' : 'bg-success'}" 
                                 role="progressbar" 
                                 style="width: ${Math.min(100, Math.abs(lote.obsolescence || 0))}%">
                            </div>
                        </div>
                        <span class="small fw-medium">${(lote.obsolescence || 0).toFixed(1)}%</span>
                    </div>
                </td>
                <td class="text-center">${estadoBadge}</td>
            </tr>`;
            }).join('');

            if (countEl) countEl.textContent = `${lotes.length} lote${lotes.length !== 1 ? 's' : ''}`;
        }



        function limpiarFiltrosLotes() {
            console.log("Entered in this method !");
            document.getElementById('filterLotesSearch').value = '';
            document.getElementById('filterLotesEstado').value = '';
            document.getElementById('filterLotesOrden').value = 'caducidad';
            //filtrarLotesProducto();
        }

        function exportLots(format) {
            if (!productStockDetails || productStockDetails.length === 0) {
                alert('No hay lotes para exportar.');
                return;
            }

            const isExcel = format === 'excel';
            document.getElementById(isExcel ? 'exportLotsExcelData' : 'exportLotsPdfData').value =
                JSON.stringify(productStockDetails);
            document.getElementById(isExcel ? 'formExportLotsExcel' : 'formExportLotsPdf').submit();
        }

        function exportPhysicalCount(format) {
            if (!productStockDetails || productStockDetails.length === 0) {
                alert('No hay productos para exportar.');
                return;
            }

            const isExcel = format === 'excel';
            document.getElementById(isExcel ? 'exportPhysicalCountExcelData' : 'exportPhysicalCountPdfData').value =
                JSON.stringify(productStockDetails);
            document.getElementById(isExcel ? 'formExportPhysicalCountExcel' : 'formExportPhysicalCountPdf').submit();
        }

        document.getElementById('modalLotesProducto')?.addEventListener('hidden.bs.modal', function() {
            lotesProductoData = [];
            lotesProductoFiltrados = [];
            currentProductoId = null;
            currentWarehouseId = null;
            limpiarFiltrosLotes();
        });
    </script>
@endpush
