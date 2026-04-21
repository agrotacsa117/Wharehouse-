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

        .page-header { display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 1rem; margin-bottom: 1.5rem; }
        .page-title { font-size: 1.75rem; font-weight: 700; color: #1e293b; margin: 0; }
        .breadcrumb-custom { font-size: 0.875rem; color: #64748b; margin-top: 0.25rem; }
        .breadcrumb-custom a { color: var(--tacsa-red); text-decoration: none; }

        .badge-critical-alert {
            background: linear-gradient(135deg, #dc2626, #b91c1c);
            color: white; padding: 0.5rem 1rem; border-radius: 50px;
            font-weight: 600; font-size: 0.875rem;
            animation: pulse-alert 2s infinite;
            display: inline-flex; align-items: center; gap: 0.4rem;
        }
        @keyframes pulse-alert {
            0%, 100% { box-shadow: 0 0 0 0 rgba(220, 38, 38, 0.4); }
            50%       { box-shadow: 0 0 0 8px rgba(220, 38, 38, 0); }
        }

        .date-badge { background: white; border: 1px solid #e2e8f0; padding: 0.5rem 1rem; border-radius: 8px; font-size: 0.875rem; color: #475569; display: inline-flex; align-items: center; gap: 0.5rem; }

        .dot-indicator { width: 12px; height: 12px; border-radius: 50%; display: inline-block; flex-shrink: 0; }
        .dot-critical  { background-color: var(--color-critical); animation: blink 1.5s infinite; }
        .dot-attention { background-color: var(--color-attention); }
        .dot-ok        { background-color: var(--color-ok); }
        @keyframes blink { 0%, 100% { opacity: 1; } 50% { opacity: 0.4; } }

        /* Chart y Resumen */
        .chart-card, .resumen-card {
            background: white; border-radius: 16px; padding: 1.5rem;
            box-shadow: 0 1px 3px rgba(0,0,0,0.08); border: 1px solid #e2e8f0; height: 100%;
        }
        .chart-title { font-size: 1rem; font-weight: 600; color: #1e293b; margin-bottom: 1.25rem; }
        .chart-container { position: relative; max-width: 260px; margin: 0 auto; }
        .chart-legend { display: flex; justify-content: center; gap: 1.25rem; margin-top: 1rem; flex-wrap: wrap; }
        .legend-item { display: flex; align-items: center; gap: 0.5rem; font-size: 0.8rem; color: #475569; }
        .legend-color { width: 12px; height: 12px; border-radius: 3px; flex-shrink: 0; }

        /* Mini Cards */
        .semaforo-mini-card {
            cursor: pointer; transition: transform 0.15s ease, box-shadow 0.15s ease;
            border-radius: 10px; padding: 1rem 1.25rem; margin-bottom: 0.75rem;
            display: flex; align-items: center; justify-content: space-between; border: 1px solid transparent;
        }
        .semaforo-mini-card:last-child { margin-bottom: 0; }
        .semaforo-mini-card:hover { transform: translateX(4px); box-shadow: 0 4px 12px rgba(0,0,0,0.1); }
        .semaforo-mini-card.critical  { background: linear-gradient(135deg, #fef2f2, #fee2e2); border-left: 4px solid var(--color-critical) !important; }
        .semaforo-mini-card.attention { background: linear-gradient(135deg, #fefce8, #fef9c3); border-left: 4px solid var(--color-attention) !important; }
        .semaforo-mini-card.ok        { background: linear-gradient(135deg, #f0fdf4, #dcfce7); border-left: 4px solid var(--color-ok) !important; }
        .mini-card-label { display: flex; align-items: center; gap: 0.5rem; font-size: 0.9rem; font-weight: 500; }
        .mini-card-value { font-size: 1.6rem; font-weight: 700; }
        .mini-card-value.critical { color: var(--color-critical); }
        .mini-card-value.attention { color: var(--color-attention); }
        .mini-card-value.ok        { color: var(--color-ok); }

        .section-title { font-size: 1.1rem; font-weight: 600; color: #1e293b; margin-bottom: 1rem; padding-bottom: 0.5rem; border-bottom: 2px solid var(--tacsa-red); display: inline-block; }

        /* Modales */
        .modal-header.critical  { background: linear-gradient(135deg, #fee2e2, #fecaca); border-left: 5px solid var(--color-critical); }
        .modal-header.attention { background: linear-gradient(135deg, #fef9c3, #fef08a); border-left: 5px solid var(--color-attention); }
        .modal-header.ok        { background: linear-gradient(135deg, #dcfce7, #bbf7d0); border-left: 5px solid var(--color-ok); }
        .modal-title-critical { color: var(--color-critical); }
        .modal-title-attention { color: #a16207; }
        .modal-title-ok        { color: var(--color-ok); }
        .filter-section { background: #f8fafc; border-radius: 10px; padding: 1rem; margin-bottom: 1rem; }
        .table-semaforo { font-size: 0.85rem; }
        .table-semaforo th { background: #f1f5f9; font-weight: 600; color: #475569; border-bottom: 2px solid #e2e8f0; white-space: nowrap; }
        .table-semaforo td { vertical-align: middle; }
        .no-data-message { padding: 3rem; text-align: center; color: #94a3b8; }

        /* TABS */
        .tabs-nav { display: flex; gap: 0; background: white; border-radius: 12px; padding: 0.35rem; box-shadow: 0 1px 3px rgba(0,0,0,0.08); border: 1px solid #e2e8f0; margin-bottom: 1.75rem; width: fit-content; }
        .tab-btn { padding: 0.55rem 1.25rem; border-radius: 8px; border: none; background: transparent; font-size: 0.875rem; font-weight: 500; color: #64748b; cursor: pointer; transition: all 0.2s ease; display: inline-flex; align-items: center; gap: 0.4rem; }
        .tab-btn:hover { background: #f1f5f9; color: #1e293b; }
        .tab-btn.active { background: var(--tacsa-red); color: white; box-shadow: 0 2px 8px rgba(220, 38, 38, 0.3); }
        .tab-panel { display: none; }
        .tab-panel.active { display: block; }

        /* TOP 3 */
        .top3-card { background: white; border-radius: 16px; padding: 1.5rem; box-shadow: 0 1px 3px rgba(0,0,0,0.08); border: 1px solid #e2e8f0; height: 100%; }
        .top3-item { display: flex; align-items: center; gap: 1rem; padding: 0.85rem 1rem; border-radius: 10px; margin-bottom: 0.6rem; background: linear-gradient(135deg, #fef2f2, #fee2e2); border-left: 4px solid var(--color-critical); transition: transform 0.15s ease; }
        .top3-item:hover { transform: translateX(3px); }
        .top3-item:last-child { margin-bottom: 0; }
        .top3-rank { width: 32px; height: 32px; border-radius: 50%; color: white; font-weight: 700; font-size: 0.85rem; display: flex; align-items: center; justify-content: center; flex-shrink: 0; }
        .top3-rank.rank-1 { background: #dc2626; }
        .top3-rank.rank-2 { background: #ef4444; }
        .top3-rank.rank-3 { background: #f87171; }
        .top3-info { flex: 1; min-width: 0; }
        .top3-name { font-weight: 600; font-size: 0.875rem; color: #1e293b; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
        .top3-meta { font-size: 0.75rem; color: #64748b; margin-top: 0.1rem; }
        .top3-days { font-size: 0.8rem; font-weight: 700; padding: 0.2rem 0.6rem; border-radius: 50px; white-space: nowrap; flex-shrink: 0; }
        .days-expired { background: #dc2626; color: white; }
        .days-urgent  { background: #fef2f2; color: #dc2626; border: 1px solid #fecaca; }

        /* VENCIDOS */
        .vencidos-card { background: white; border-radius: 16px; padding: 1.5rem; box-shadow: 0 1px 3px rgba(0,0,0,0.08); border: 1px solid #e2e8f0; }

        /* MOVIMIENTOS */
        .reporte-card { background: white; border-radius: 16px; padding: 1.5rem; box-shadow: 0 1px 3px rgba(0,0,0,0.08); border: 1px solid #e2e8f0; margin-bottom: 1.5rem; }
        .reporte-filter-bar { background: #f8fafc; border-radius: 12px; padding: 1.25rem; border: 1px solid #e2e8f0; margin-bottom: 1.5rem; }
        .movement-stat-card { background: white; border-radius: 12px; padding: 1rem 1.25rem; border: 1px solid #e2e8f0; text-align: center; }
        .movement-stat-value { font-size: 1.75rem; font-weight: 700; color: #1e293b; }
        .movement-stat-label { font-size: 0.78rem; color: #64748b; margin-top: 0.2rem; }
        .movement-stat-icon { font-size: 1.5rem; margin-bottom: 0.5rem; }
    </style>

    <main id="main" class="main bg-light py-4">
        <div class="container-fluid">

            {{-- HEADER --}}
            <div class="page-header">
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
                <button class="tab-btn" onclick="switchTab('movimientos', this)">
                    <i class="bi bi-arrow-left-right"></i> Movimientos
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
                        Top 3 Productos Vencidos por Almacén
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
                                        <i class="bi bi-check-circle-fill fs-2 mb-2 d-block" style="color: var(--color-ok);"></i>
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
                                <p>¡Sin productos vencidos en ningún almacén!</p>
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
                                $totalVencidos = collect($semaforoCritical ?? [])->filter(function($p) {
                                    $days = is_array($p) ? ($p['remainingDays'] ?? 1) : ($p->remainingDays ?? 1);
                                    return $days <= 0;
                                })->count();
                            @endphp
                            @if ($totalVencidos > 0)
                                <span class="badge bg-danger ms-1">{{ $totalVencidos }}</span>
                            @endif
                        </h3>
                        <div class="d-flex gap-2 flex-wrap">
                            <input type="text" class="form-control form-control-sm" id="filterVencidosSearch"
                                placeholder="Buscar producto o lote..." style="width: 220px;"
                                onkeyup="filterVencidos()">
                            <select class="form-select form-select-sm" id="filterVencidosAlmacen"
                                style="width: 170px;" onchange="filterVencidos()">
                                <option value="">Todos los almacenes</option>
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
                                    <th>Almacén</th>
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
                                <span class="mini-card-value critical">{{ number_format($critical->getTotalStock() ?? 0, 0, ',', '.') }}</span>
                            </div>

                            <div class="semaforo-mini-card attention" onclick="openSemaforoModal('attention')">
                                <div class="mini-card-label">
                                    <span class="dot-indicator dot-attention"></span>
                                    <span style="color: #a16207;">Atención (90-120 días)</span>
                                </div>
                                <span class="mini-card-value attention">{{ number_format($attention->getTotalStock() ?? 0, 0, ',', '.') }}</span>
                            </div>

                            <div class="semaforo-mini-card ok" onclick="openSemaforoModal('ok')">
                                <div class="mini-card-label">
                                    <span class="dot-indicator dot-ok"></span>
                                    <span style="color: var(--color-ok);">OK (&gt;120 días)</span>
                                </div>
                                <span class="mini-card-value ok">{{ number_format($ok->getTotalStock() ?? 0, 0, ',', '.') }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>{{-- /tab-existencias --}}


            {{-- =========================================== --}}
            {{--         TAB 3: MOVIMIENTOS                  --}}
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
                            <label class="form-label small fw-medium">Almacén</label>
                            <select class="form-select form-select-sm" id="movAlmacen">
                                <option value="">Todos los almacenes</option>
                                @foreach ($almacenes as $almacen)
                                    <option value="{{ $almacen->id }}">{{ $almacen->warehouses_name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label small fw-medium">Tipo</label>
                            <select class="form-select form-select-sm" id="movTipo">
                                <option value="">Todos</option>
                                <option value="entrada">Entradas</option>
                                <option value="salida">Salidas</option>
                                <option value="traslado">Traslados</option>
                                <option value="ajuste">Ajustes</option>
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
                            <div class="movement-stat-icon text-success"><i class="bi bi-arrow-down-circle-fill"></i></div>
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
                                    <th class="text-center">#</th>
                                    <th>Fecha</th>
                                    <th>Tipo</th>
                                    <th>Producto</th>
                                    <th>Almacén Origen</th>
                                    <th>Almacén Destino</th>
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
                    </div>
                    <div class="mt-2 text-muted small" id="countMovimientos"></div>
                </div>

            </div>{{-- /tab-movimientos --}}

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
                                <label class="form-label small fw-medium">Almacén</label>
                                <select class="form-select form-select-sm" id="filterWarehouseCritical" onchange="filterSemaforoTable('critical')">
                                    <option value="">Todos los almacenes</option>
                                    @foreach ($almacenes as $almacen)
                                        <option value="{{ $almacen->id }}">{{ $almacen->warehouses_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label small fw-medium">Producto</label>
                                <input type="text" class="form-control form-control-sm" id="filterProductCritical" placeholder="Buscar..." onkeyup="filterSemaforoTable('critical')">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label small fw-medium">Fecha Máx. Caducidad</label>
                                <input type="date" class="form-control form-control-sm" id="filterDateCritical" onchange="filterSemaforoTable('critical')">
                            </div>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-hover table-semaforo mb-0">
                            <thead>
                                <tr>
                                    <th class="text-center">#</th><th>Código</th><th>Producto</th><th>Almacén</th>
                                    <th>Rack</th><th class="text-center">Nivel</th><th>Lote</th>
                                    <th class="text-center">Cantidad</th><th class="text-center">Fecha Caducidad</th>
                                    <th class="text-center">Días</th><th class="text-center">Obsolescencia (%)</th>
                                </tr>
                            </thead>
                            <tbody id="tableCriticalBody">
                                <tr><td colspan="11" class="text-center text-muted py-4"><i class="bi bi-hourglass-split me-2"></i>Cargando...</td></tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="modal-footer justify-content-between">
                    <span class="text-muted small" id="countCritical">0 productos</span>
                    <button type="button" class="btn btn-outline-secondary btn-sm" data-bs-dismiss="modal"><i class="bi bi-x-circle me-1"></i>Cerrar</button>
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
                                <label class="form-label small fw-medium">Almacén</label>
                                <select class="form-select form-select-sm" id="filterWarehouseAttention" onchange="filterSemaforoTable('attention')">
                                    <option value="">Todos los almacenes</option>
                                    @foreach ($almacenes as $almacen)
                                        <option value="{{ $almacen->id }}">{{ $almacen->warehouses_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label small fw-medium">Producto</label>
                                <input type="text" class="form-control form-control-sm" id="filterProductAttention" placeholder="Buscar..." onkeyup="filterSemaforoTable('attention')">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label small fw-medium">Fecha Máx. Caducidad</label>
                                <input type="date" class="form-control form-control-sm" id="filterDateAttention" onchange="filterSemaforoTable('attention')">
                            </div>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-hover table-semaforo mb-0">
                            <thead>
                                <tr>
                                    <th class="text-center">#</th><th>Código</th><th>Producto</th><th>Almacén</th>
                                    <th>Rack</th><th class="text-center">Nivel</th><th>Lote</th>
                                    <th class="text-center">Cantidad</th><th class="text-center">Fecha Caducidad</th>
                                    <th class="text-center">Días</th><th class="text-center">Obsolescencia (%)</th>
                                </tr>
                            </thead>
                            <tbody id="tableAttentionBody">
                                <tr><td colspan="11" class="text-center text-muted py-4"><i class="bi bi-hourglass-split me-2"></i>Cargando...</td></tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="modal-footer justify-content-between">
                    <span class="text-muted small" id="countAttention">0 productos</span>
                    <button type="button" class="btn btn-outline-secondary btn-sm" data-bs-dismiss="modal"><i class="bi bi-x-circle me-1"></i>Cerrar</button>
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
                                <label class="form-label small fw-medium">Almacén</label>
                                <select class="form-select form-select-sm" id="filterWarehouseOk" onchange="filterSemaforoTable('ok')">
                                    <option value="">Todos los almacenes</option>
                                    @foreach ($almacenes as $almacen)
                                        <option value="{{ $almacen->id }}">{{ $almacen->warehouses_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label small fw-medium">Producto</label>
                                <input type="text" class="form-control form-control-sm" id="filterProductOk" placeholder="Buscar..." onkeyup="filterSemaforoTable('ok')">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label small fw-medium">Fecha Máx. Caducidad</label>
                                <input type="date" class="form-control form-control-sm" id="filterDateOk" onchange="filterSemaforoTable('ok')">
                            </div>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-hover table-semaforo mb-0">
                            <thead>
                                <tr>
                                    <th class="text-center">#</th><th>Código</th><th>Producto</th><th>Almacén</th>
                                    <th>Rack</th><th class="text-center">Nivel</th><th>Lote</th>
                                    <th class="text-center">Cantidad</th><th class="text-center">Fecha Caducidad</th>
                                    <th class="text-center">Días</th><th class="text-center">Obsolescencia (%)</th>
                                </tr>
                            </thead>
                            <tbody id="tableOkBody">
                                <tr><td colspan="11" class="text-center text-muted py-4"><i class="bi bi-hourglass-split me-2"></i>Cargando...</td></tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="modal-footer justify-content-between">
                    <span class="text-muted small" id="countOk">0 productos</span>
                    <button type="button" class="btn btn-outline-secondary btn-sm" data-bs-dismiss="modal"><i class="bi bi-x-circle me-1"></i>Cerrar</button>
                </div>
            </div>
        </div>
    </div>

@endsection

@push('scripts')
<script>
    const defaultFechaInicio = "{{ now()->startOfMonth()->format('Y-m-d') }}";
    const defaultFechaFin    = "{{ now()->format('Y-m-d') }}";

    // =============================================
    //   DATOS DESDE PHP
    // =============================================
    const semaforoData = {
        critical:  {!! json_encode($semaforoCritical ?? []) !!},
        attention: {!! json_encode($semaforoAttention ?? []) !!},
        ok:        {!! json_encode($semaforoOk ?? []) !!}
    };

    // ✅ Movimientos del mes desde el controller
    const movimientosData = {!! json_encode($movimientos ?? []) !!};

    // =============================================
    //   CHART.JS
    // =============================================
    document.addEventListener('DOMContentLoaded', function () {
        renderVencidos();

        const canvas = document.getElementById('chartCaducidad');
        if (canvas) {
            const ctx = canvas.getContext('2d');
            const totalItems = {{ ($critical->getTotalStock() ?? 0) + ($attention->getTotalStock() ?? 0) + ($ok->getTotalStock() ?? 0) }};
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
                            borderWidth: 3, borderColor: '#ffffff', hoverOffset: 8
                        }]
                    },
                    options: {
                        responsive: true, maintainAspectRatio: true, cutout: '60%',
                        plugins: {
                            legend: { display: false },
                            tooltip: {
                                backgroundColor: 'rgba(15,23,42,0.9)', padding: 12, cornerRadius: 8,
                                callbacks: {
                                    label: function(context) {
                                        const pct = totalItems > 0 ? ((context.raw / totalItems) * 100).toFixed(1) : 0;
                                        return context.label + ': ' + context.raw.toLocaleString('es-MX') + ' (' + pct + '%)';
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
    function formatNumber(num) { return new Intl.NumberFormat('es-MX').format(num); }

    function formatDate(dateStr) {
        if (!dateStr) return '-';
        const date = new Date(dateStr + 'T00:00:00');
        return date.toLocaleDateString('es-MX', { day: '2-digit', month: '2-digit', year: 'numeric' });
    }

    function getBadgeClass(days) {
        if (days <= 0)  return 'bg-danger';
        if (days <= 30) return 'bg-danger';
        if (days <= 60) return 'bg-warning text-dark';
        return 'bg-success';
    }

    function capitalize(str) { return str.charAt(0).toUpperCase() + str.slice(1); }

    // =============================================
    //   TABLA VENCIDOS
    // =============================================
    function renderVencidos(data) {
        const tbody   = document.getElementById('tableVencidosBody');
        const countEl = document.getElementById('countVencidos');
        const vencidos = (data !== undefined) ? data :
            (semaforoData.critical || []).filter(p => (p.remainingDays ?? 999) <= 0);

        if (!vencidos || vencidos.length === 0) {
            tbody.innerHTML = `<tr><td colspan="8" class="text-center text-muted py-5">
                <i class="bi bi-check-circle-fill me-2 text-success"></i>No hay productos vencidos. ¡Excelente!
            </td></tr>`;
            if (countEl) countEl.textContent = '';
            return;
        }

        tbody.innerHTML = vencidos.map((item, i) => `
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
                <td class="text-center">${formatDate(item.expirationDate)}</td>
                <td class="text-center">
                    <span class="badge bg-danger">
                        <i class="bi bi-x-circle me-1"></i>
                        Vencido hace ${Math.abs(item.remainingDays)} días
                    </span>
                </td>
            </tr>
        `).join('');

        if (countEl) countEl.textContent = `${vencidos.length} producto${vencidos.length !== 1 ? 's' : ''} vencido${vencidos.length !== 1 ? 's' : ''}`;
    }

    function filterVencidos() {
        const search  = (document.getElementById('filterVencidosSearch')?.value || '').toLowerCase();
        const almacen = document.getElementById('filterVencidosAlmacen')?.value || '';
        let data = (semaforoData.critical || []).filter(p => (p.remainingDays ?? 999) <= 0);
        if (almacen) data = data.filter(p => String(p.warehouseId) === almacen);
        if (search)  data = data.filter(p =>
            (p.productName || '').toLowerCase().includes(search) ||
            (p.lotNumber   || '').toLowerCase().includes(search)
        );
        renderVencidos(data);
    }

    // =============================================
    //   MODALES SEMÁFORO
    // =============================================
    function renderTableData(data, tbodyId, countId) {
        const tbody   = document.getElementById(tbodyId);
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
                <td class="text-center">${formatDate(item.expirationDate)}</td>
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
        const tbodyId = type === 'critical' ? 'tableCriticalBody' : type === 'attention' ? 'tableAttentionBody' : 'tableOkBody';
        renderTableData(semaforoData[type] || [], tbodyId, `count${capitalize(type)}`);
        currentModal = new bootstrap.Modal(document.getElementById(modalId));
        currentModal.show();
    }

    function filterSemaforoTable(type) {
        const warehouseFilter = document.getElementById(`filterWarehouse${capitalize(type)}`)?.value || '';
        const productFilter   = document.getElementById(`filterProduct${capitalize(type)}`)?.value.toLowerCase() || '';
        const dateFilter      = document.getElementById(`filterDate${capitalize(type)}`)?.value || '';
        let filteredData = semaforoData[type] || [];
        if (warehouseFilter) filteredData = filteredData.filter(item => item.warehouseId == warehouseFilter);
        if (productFilter)   filteredData = filteredData.filter(item =>
            (item.productId   || '').toLowerCase().includes(productFilter) ||
            (item.productName || '').toLowerCase().includes(productFilter)
        );
        if (dateFilter) {
            const filterDate = new Date(dateFilter);
            filteredData = filteredData.filter(item => new Date(item.expirationDate) <= filterDate);
        }
        const tbodyId = type === 'critical' ? 'tableCriticalBody' : type === 'attention' ? 'tableAttentionBody' : 'tableOkBody';
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
            modalEl.addEventListener('hidden.bs.modal', function () {
                currentModal = null;
                resetFilters(type.toLowerCase());
            });
        }
    });

    // =============================================
    //   MOVIMIENTOS
    // =============================================
    const tipoConfig = {
        entrada:  { label: 'Entrada',  badge: 'bg-success',              icon: 'bi-arrow-down-circle-fill' },
        salida:   { label: 'Salida',   badge: 'bg-danger',               icon: 'bi-arrow-up-circle-fill' },
        traslado: { label: 'Traslado', badge: 'bg-primary',              icon: 'bi-arrow-left-right' },
        ajuste:   { label: 'Ajuste',   badge: 'bg-warning text-dark',    icon: 'bi-sliders' },
        out:      { label: 'Salida',   badge: 'bg-danger',               icon: 'bi-arrow-up-circle-fill' },
        sale:     { label: 'Venta',    badge: 'bg-danger',               icon: 'bi-cart-fill' },
        in:       { label: 'Entrada',  badge: 'bg-success',              icon: 'bi-arrow-down-circle-fill' },
        transfer: { label: 'Traslado', badge: 'bg-primary',              icon: 'bi-arrow-left-right' },
        relocation:{ label: 'Reubicación', badge: 'bg-info text-dark',   icon: 'bi-arrows-move' },
    };

    function filtrarMovimientos() {
        const inicio  = document.getElementById('movFechaInicio').value;
        const fin     = document.getElementById('movFechaFin').value;
        const almacen = document.getElementById('movAlmacen').value;
        const tipo    = document.getElementById('movTipo').value;

        let data = movimientosData || [];

        if (inicio) data = data.filter(m => m.date >= inicio);
        if (fin)    data = data.filter(m => m.date <= fin);
        if (almacen) data = data.filter(m =>
            String(m.warehouseOriginId) === almacen ||
            String(m.warehouseDestinationId) === almacen
        );
        if (tipo) data = data.filter(m => {
            const t = (m.type || '').toLowerCase();
            if (tipo === 'entrada')  return t === 'in'       || t === 'entrada';
            if (tipo === 'salida')   return t === 'out'      || t === 'sale'   || t === 'salida';
            if (tipo === 'traslado') return t === 'transfer' || t === 'traslado';
            if (tipo === 'ajuste')   return t === 'adjustment' || t === 'ajuste';
            return false;
        });

        // Stats
        const counts = { entrada: 0, salida: 0, traslado: 0, ajuste: 0 };
        data.forEach(m => {
            const t = (m.type || '').toLowerCase();
            if (t === 'in'       || t === 'entrada')              counts.entrada++;
            else if (t === 'out' || t === 'sale' || t === 'salida') counts.salida++;
            else if (t === 'transfer' || t === 'traslado')         counts.traslado++;
            else if (t === 'adjustment' || t === 'ajuste')         counts.ajuste++;
        });

        document.getElementById('statEntradas').textContent  = counts.entrada;
        document.getElementById('statSalidas').textContent   = counts.salida;
        document.getElementById('statTraslados').textContent = counts.traslado;
        document.getElementById('statAjustes').textContent   = counts.ajuste;

        const rangoEl = document.getElementById('movRangoLabel');
        if (rangoEl && inicio && fin) rangoEl.textContent = `Del ${formatDate(inicio)} al ${formatDate(fin)}`;

        renderMovimientos(data);
    }

    function renderMovimientos(data) {
        const tbody   = document.getElementById('tableMovimientosBody');
        const countEl = document.getElementById('countMovimientos');

        if (!data || data.length === 0) {
            tbody.innerHTML = `<tr><td colspan="9" class="text-center text-muted py-5">
                <i class="bi bi-inbox-fill me-2"></i>No se encontraron movimientos con los filtros aplicados
            </td></tr>`;
            if (countEl) countEl.textContent = '';
            return;
        }

        tbody.innerHTML = data.map((mov, i) => {
            const t   = (mov.type || '').toLowerCase();
            const cfg = tipoConfig[t] || { label: mov.type || '-', badge: 'bg-secondary', icon: 'bi-circle' };
            return `
            <tr>
                <td class="text-center text-muted">${i + 1}</td>
                <td>${formatDate(mov.date)}</td>
                <td><span class="badge ${cfg.badge}"><i class="bi ${cfg.icon} me-1"></i>${cfg.label}</span></td>
                <td class="fw-medium">${mov.productName || 'N/A'}</td>
                <td><span class="badge bg-secondary">${mov.warehouseOriginName || '-'}</span></td>
                <td><span class="badge bg-secondary">${mov.warehouseDestinationName || '-'}</span></td>
                <td><small>${mov.lotNumber || '-'}</small></td>
                <td class="text-center fw-bold">${formatNumber(mov.quantity || 0)}</td>
                <td><small class="text-muted">${mov.userName || '-'}</small></td>
            </tr>`;
        }).join('');

        if (countEl) countEl.textContent = `${data.length} movimiento${data.length !== 1 ? 's' : ''}`;
    }

    function limpiarFiltrosMovimientos() {
        document.getElementById('movAlmacen').value    = '';
        document.getElementById('movTipo').value       = '';
        document.getElementById('movFechaInicio').value = defaultFechaInicio;
        document.getElementById('movFechaFin').value    = defaultFechaFin;
        document.getElementById('tableMovimientosBody').innerHTML = `
            <tr><td colspan="9" class="text-center text-muted py-5">
                <i class="bi bi-search me-2"></i>Aplica los filtros para consultar movimientos
            </td></tr>`;
        document.getElementById('countMovimientos').textContent = '';
        ['statEntradas','statSalidas','statTraslados','statAjustes'].forEach(id => {
            document.getElementById(id).textContent = '-';
        });
        const rangoEl = document.getElementById('movRangoLabel');
        if (rangoEl) rangoEl.textContent = '';
    }
</script>
@endpush