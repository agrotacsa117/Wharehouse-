@extends('layouts.main')

@section('titulo', $titulo)

@section('contenido')
    <main id="main" class="main bg-light py-4">
        <div class="pagetitle mb-4">
            <h1 class="fw-bold text-primary" style="letter-spacing:1px;"><i
                    class="fa-solid fa-gauge-high me-2"></i>{{ $titulo }}</h1>
            <nav>
                <ol class="breadcrumb bg-white rounded shadow-sm px-3 py-2">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}">Inicio</a></li>
                    <li class="breadcrumb-item active">Panel Principal</li>
                </ol>
            </nav>
        </div>
        <section class="section dashboard">
            @php
                $user = auth()->user();
                $esAdmin = $user->rol === 'admin';
                $esTapachula = $user->rol === 'tapachula';
                $esDorado = $user->rol === 'bodega_dorado';
            @endphp
            <div class="row mb-4 justify-content-center">
                @if ($esAdmin)
                    <!-- Solo admin ve las tarjetas de precios individuales -->
                    <div class="col-xxl-4 col-md-6 mb-4">
                        <x-dashboard.card icon="fa-solid fa-dollar-sign" title="Precio Total Dorado" :value="'$ ' . number_format($precioTotalDorado, 2)"
                            iconBg="bg-success" valueClass="text-success" />
                    </div>
                    <div class="col-xxl-4 col-md-6 mb-4">
                        <x-dashboard.card icon="fa-solid fa-dollar-sign" title="Precio Total Tapachula" :value="'$ ' . number_format($precioTotalTapachula, 2)"
                            iconBg="bg-success" valueClass="text-success" />
                    </div>
                @endif
                <!-- Solo admin y otros roles distintos a tapachula y bodega_dorado ven el total general -->
                @if ($esAdmin || (!$esTapachula && !$esDorado))
                    <div class="col-xxl-4 col-md-12 mb-4">
                        <x-dashboard.card icon="fa-solid fa-sack-dollar" title="Precio Total General" :value="'$ ' . number_format($precioTotalGeneral, 2)"
                            iconBg="bg-success" valueClass="text-success" />
                    </div>
                @endif
            </div>

            {{-- ============================================================= --}}
            {{--              SEMÁFORO DE CADUCIDAD - NUEVA SECCIÓN            --}}
            {{-- ============================================================= --}}
            <div class="row mb-4">
                <div class="col-12">
                    <div class="card shadow border-0">
                        <div class="card-body">
                            <h5 class="card-title d-flex align-items-center gap-2 mb-4">
                                <i class="bi bi-clock-history text-primary"></i>
                                <span>Semáforo de Caducidad</span>
                                @if ( $critical->getTotalStock() > 0)
                                    <span class="badge bg-danger ms-2 animate__animated animate__pulse animate__infinite">
                                        <i class="bi bi-exclamation-triangle-fill me-1"></i>{{ $critical->getTotalStock() }} críticos
                                    </span>
                                @endif
                            </h5>
                            <div class="row g-3">
                                {{-- Tarjeta CRÍTICO (Rojo) - Menos de 90 días --}}
                                <div class="col-lg-4 col-md-6">
                                    <div class="card h-100 border-0 shadow-sm position-relative overflow-hidden clickable-card"
                                        style="background: linear-gradient(135deg, #fee2e2 0%, #fecaca 100%); border-left: 4px solid #dc2626 !important; cursor: pointer;"
                                        onclick="openSemaforoModal('critical')">
                                        <div class="card-body">
                                            <div class="d-flex align-items-center gap-2 mb-3">
                                                <span
                                                    class="rounded-circle d-inline-flex align-items-center justify-content-center"
                                                    style="width: 14px; height: 14px; background-color: #dc2626; box-shadow: 0 0 8px rgba(220, 38, 38, 0.6);">
                                                </span>
                                                <span class="fw-semibold text-danger">Crítico</span>
                                            </div>
                                            <h2 class="fw-bold mb-2" style="color: #dc2626; font-size: 2.5rem;">
                                                {{ number_format($critical->getTotalStock() ?? 0, 0, ',', '.') }}
                                            </h2>
                                            <p class="text-danger mb-3 small fw-medium">
                                                <i class="bi bi-calendar-x me-1"></i>Menos de 90 días
                                            </p>
                                            <hr class="my-2" style="border-color: rgba(220, 38, 38, 0.2);">
                                            @forelse ($statsWarehouses[3] ?? [] as $warehouseStat)
                                                <div class="d-flex justify-content-between small">
                                                    <span class="text-muted">{{ $warehouseStat->getWarehouseName() }}:</span>
                                                    <span class="fw-bold"
                                                        style="color: #dc2626;">{{ number_format($warehouseStat->getTotalStock() ?? 0, 0, ',', '.') }}</span>
                                                </div>
                                            @empty
                                                <div class="d-flex justify-content-between small">
                                                    <span class="text-muted">Sin datos</span>
                                                    <span class="fw-bold" style="color: #dc2626;">0</span>
                                                </div>
                                            @endforelse
                                        </div>
                                        {{-- Icono decorativo de fondo --}}
                                        <i class="bi bi-exclamation-triangle-fill position-absolute"
                                            style="font-size: 5rem; bottom: -10px; right: -10px; color: rgba(220, 38, 38, 0.1);"></i>
                                    </div>
                                </div>

                                {{-- Tarjeta ATENCIÓN (Amarillo) - Entre 90 y 120 días --}}
                                <div class="col-lg-4 col-md-6">
                                    <div class="card h-100 border-0 shadow-sm position-relative overflow-hidden clickable-card"
                                        style="background: linear-gradient(135deg, #fef9c3 0%, #fef08a 100%); border-left: 4px solid #ca8a04 !important; cursor: pointer;"
                                        onclick="openSemaforoModal('attention')">
                                        <div class="card-body">
                                            <div class="d-flex align-items-center gap-2 mb-3">
                                                <span
                                                    class="rounded-circle d-inline-flex align-items-center justify-content-center"
                                                    style="width: 14px; height: 14px; background-color: #ca8a04; box-shadow: 0 0 8px rgba(202, 138, 4, 0.6);">
                                                </span>
                                                <span class="fw-semibold" style="color: #a16207;">Atención</span>
                                            </div>
                                            <h2 class="fw-bold mb-2" style="color: #ca8a04; font-size: 2.5rem;">
                                                {{ number_format($attention->getTotalStock() ?? 0, 0, ',', '.') }}
                                            </h2>
                                            <p class="small fw-medium mb-3" style="color: #a16207;">
                                                <i class="bi bi-calendar-event me-1"></i>Entre 90 y 120 días
                                            </p>
                                            <hr class="my-2" style="border-color: rgba(202, 138, 4, 0.2);">
                                            @forelse ($statsWarehouses[2] ?? [] as $warehouseStat)
                                                <div class="d-flex justify-content-between small">
                                                    <span class="text-muted">{{ $warehouseStat->getWarehouseName() }}:</span>
                                                    <span class="fw-bold"
                                                        style="color: #ca8a04;">{{ number_format($warehouseStat->getTotalStock() ?? 0, 0, ',', '.') }}</span>
                                                </div>
                                            @empty
                                                <div class="d-flex justify-content-between small">
                                                    <span class="text-muted">Sin datos</span>
                                                    <span class="fw-bold" style="color: #ca8a04;">0</span>
                                                </div>
                                            @endforelse
                                        </div>
                                        {{-- Icono decorativo de fondo --}}
                                        <i class="bi bi-clock-fill position-absolute"
                                            style="font-size: 5rem; bottom: -10px; right: -10px; color: rgba(202, 138, 4, 0.1);"></i>
                                    </div>
                                </div>

                                {{-- Tarjeta OK (Verde) - Más de 120 días --}}
                                <div class="col-lg-4 col-md-12">
                                    <div class="card h-100 border-0 shadow-sm position-relative overflow-hidden clickable-card"
                                        style="background: linear-gradient(135deg, #dcfce7 0%, #bbf7d0 100%); border-left: 4px solid #16a34a !important; cursor: pointer;"
                                        onclick="openSemaforoModal('ok')">
                                        <div class="card-body">
                                            <div class="d-flex align-items-center gap-2 mb-3">
                                                <span
                                                    class="rounded-circle d-inline-flex align-items-center justify-content-center"
                                                    style="width: 14px; height: 14px; background-color: #16a34a; box-shadow: 0 0 8px rgba(22, 163, 74, 0.6);">
                                                </span>
                                                <span class="fw-semibold text-success">OK</span>
                                            </div>
                                            <h2 class="fw-bold mb-2" style="color: #16a34a; font-size: 2.5rem;">
                                                {{ number_format($ok->getTotalStock() ?? 0, 0, ',', '.') }}
                                            </h2>
                                            <p class="text-success mb-3 small fw-medium">
                                                <i class="bi bi-calendar-check me-1"></i>Más de 120 días
                                            </p>
                                            <hr class="my-2" style="border-color: rgba(22, 163, 74, 0.2);">
                                            @forelse ($statsWarehouses[1] ?? [] as $warehouseStat)
                                                <div class="d-flex justify-content-between small">
                                                    <span class="text-muted">{{ $warehouseStat->getWarehouseName() }}:</span>
                                                    <span class="fw-bold"
                                                        style="color: #16a34a;">{{ number_format($warehouseStat->getTotalStock() ?? 0, 0, ',', '.') }}</span>
                                                </div>
                                            @empty
                                                <div class="d-flex justify-content-between small">
                                                    <span class="text-muted">Sin datos</span>
                                                    <span class="fw-bold" style="color: #16a34a;">0</span>
                                                </div>
                                            @endforelse
                                        </div>
                                        {{-- Icono decorativo de fondo --}}
                                        <i class="bi bi-check-circle-fill position-absolute"
                                            style="font-size: 5rem; bottom: -10px; right: -10px; color: rgba(22, 163, 74, 0.1);"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            {{-- ============================================================= --}}
            {{--              FIN SEMÁFORO DE CADUCIDAD                        --}}
            {{-- ============================================================= --}}

            <div class="row">
                <div class="col-lg-12">
                    <div class="row">
                        @if ($esAdmin || $esTapachula)
                            <!-- Card 1: Total Productos Bodega Tapachula -->
                            <div class="col-xxl-3 col-md-6 mb-4">
                                <div class="card info-card sales-card shadow border-0">
                                    <div class="card-body">
                                        <h5 class="card-title">Productos en <span>| Bodega Tapachula</span></h5>
                                        <div class="d-flex align-items-center">
                                            <div
                                                class="card-icon rounded-circle d-flex align-items-center justify-content-center bg-primary text-white">
                                                <i class="bi bi-box-seam"></i>
                                            </div>
                                            <div class="ps-3">
                                                <h6 class="fw-bold text-primary">
                                                    {{ number_format($totalTapachula, 0, ',', '.') }}</h6>
                                                @if ($totalTapachula > 0)
                                                    @if (isset($cambioTapachula) && $cambioTapachula > 0)
                                                        <span
                                                            class="text-success small pt-1 fw-bold">+{{ $cambioTapachula }}%</span>
                                                        <span class="text-muted small pt-2 ps-1">vs mes anterior</span>
                                                    @elseif(isset($cambioTapachula) && $cambioTapachula < 0)
                                                        <span
                                                            class="text-danger small pt-1 fw-bold">{{ $cambioTapachula }}%</span>
                                                        <span class="text-muted small pt-2 ps-1">vs mes anterior</span>
                                                    @else
                                                        <span class="text-muted small pt-1 fw-bold">Sin cambios</span>
                                                    @endif
                                                @else
                                                    <span class="text-muted small pt-1 fw-bold">Sin datos previos</span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif

                        @if ($esAdmin || $esDorado)
                            <!-- Card 2: Total Productos Bodega Dorado -->
                            <div class="col-xxl-3 col-md-6 mb-4">
                                <div class="card info-card revenue-card shadow border-0">
                                    <div class="card-body">
                                        <h5 class="card-title">Productos en <span>| Bodega Dorado</span></h5>
                                        <div class="d-flex align-items-center">
                                            <div
                                                class="card-icon rounded-circle d-flex align-items-center justify-content-center bg-primary text-white">
                                                <i class="bi bi-box-seam"></i>
                                            </div>
                                            <div class="ps-3">
                                                <h6 class="fw-bold text-primary">
                                                    {{ number_format($totalDorado, 0, ',', '.') }}</h6>
                                                @if (isset($cambioDorado))
                                                    @if ($cambioDorado > 0)
                                                        <span
                                                            class="text-success small pt-1 fw-bold">+{{ $cambioDorado }}%</span>
                                                        <span class="text-muted small pt-2 ps-1">vs mes anterior</span>
                                                    @elseif($cambioDorado < 0)
                                                        <span
                                                            class="text-danger small pt-1 fw-bold">{{ $cambioDorado }}%</span>
                                                        <span class="text-muted small pt-2 ps-1">vs mes anterior</span>
                                                    @else
                                                        <span class="text-muted small pt-1 fw-bold">Sin cambios</span>
                                                    @endif
                                                @else
                                                    <span class="text-muted small pt-1 fw-bold">Sin datos previos</span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif

                        @if ($esAdmin)
                            <!-- Card 3: Total General de Productos (solo admin) -->
                            <div class="col-xxl-3 col-md-6 mb-4">
                                <x-dashboard.card icon="bi bi-boxes" title="Total de | Productos General"
                                    :value="number_format($totalGeneral, 0, ',', '.')" iconBg="bg-primary" valueClass="text-primary" :subtitle="'Tapachula: <span class=\'fw-bold\'>' .
                                        number_format($totalTapachula, 0, ',', '.') .
                                        '</span><br>Dorado: <span class=\'fw-bold\'>' .
                                        number_format($totalDorado, 0, ',', '.') .
                                        '</span>'" />
                            </div>
                        @endif

                        {{-- ------------------------------------------------------------- --}}
                        {{--       MEJORA VISUAL PARA LAS TARJETAS "POR VENCER"            --}}
                        {{-- ------------------------------------------------------------- --}}

                        <div class="w-100"></div>
                        @if ($esAdmin || $esTapachula)
                            <!-- Card 4: Por Vencer Bodega Tapachula (MEJORADO) -->
                            <div class="col-xxl-3 col-md-6 mb-4">
                                <x-dashboard.card-progress icon="bi bi-calendar-x-fill" title="Por Vencer | B. Tapachula"
                                    :dangerValue="number_format($porVencerYVencidosTapachula, 0, ',', '.')" :successValue="number_format(
                                        $totalTapachula - $porVencerYVencidosTapachula,
                                        0,
                                        ',',
                                        '.',
                                    )" dangerLabel="Por vencer + vencidos"
                                    successLabel="Vigentes" :dangerPercent="$porcentajePorVencerTapachulaBarra" :total="$totalTapachula" />
                            </div>
                        @endif

                        @if ($esAdmin || $esDorado)
                            <!-- Card 5: Por Vencer Bodega Dorado (MEJORADO) -->
                            <div class="col-xxl-3 col-md-6 mb-4">
                                <x-dashboard.card-progress icon="bi bi-calendar-x-fill" title="Por Vencer | B. Dorado"
                                    :dangerValue="number_format($porVencerYVencidosDorado, 0, ',', '.')" :successValue="number_format($totalDorado - $porVencerYVencidosDorado, 0, ',', '.')" dangerLabel="Por vencer + vencidos"
                                    successLabel="Vigentes" :dangerPercent="$porcentajePorVencerDoradoBarra" :total="$totalDorado" />
                            </div>
                        @endif

                        <div class="w-100"></div>
                        @if ($esAdmin || $esTapachula)
                            <!-- Tarjeta de racks Tapachula (circular) -->
                            <div class="col-xxl-3 col-md-6 mb-4">
                                <x-dashboard.circular-rack title="Racks | Tapachula" :percent="$porcentajeOcupacionTapachula" :ocupados="$ocupacionTapachula"
                                    :max="$capacidadMaxTapachula" :color="$porcentajeOcupacionTapachula >= 80 ? 'danger' : 'info'" usedLabel="Ocupado" freeLabel="Disponible" />
                            </div>
                        @endif

                        @if ($esAdmin || $esDorado)
                            <!-- Tarjeta de racks Dorado (circular) -->
                            <div class="col-xxl-3 col-md-6 mb-4">
                                <x-dashboard.circular-rack title="Racks | Dorado" :percent="$porcentajeOcupacionDorado" :ocupados="$ocupacionDorado"
                                    :max="$capacidadMaxDorado" :color="$porcentajeOcupacionDorado >= 80 ? 'danger' : 'warning'" usedLabel="Ocupado" freeLabel="Disponible" />
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </section>

        {{-- ============================================================= --}}
        {{--              MODALES DEL SEMÁFORO DE CADUCIDAD                  --}}
        {{-- ============================================================= --}}

        {{-- Modal CRÍTICO --}}
        <div class="modal fade" id="modalCritical" tabindex="-1" aria-labelledby="modalCriticalLabel" aria-hidden="true">
            <div class="modal-dialog modal-xl modal-dialog-scrollable">
                <div class="modal-content border-0 shadow" style="border-left: 5px solid #dc2626 !important;">
                    <div class="modal-header" style="background: linear-gradient(135deg, #fee2e2 0%, #fecaca 100%);">
                        <h5 class="modal-title fw-bold" id="modalCriticalLabel" style="color: #dc2626;">
                            <i class="bi bi-exclamation-triangle-fill me-2"></i>Productos Críticos
                        </h5>
                        <span class="badge bg-danger ms-2">{{ $critical->getTotalStock() ?? 0 }}</span>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                    </div>
                    {{-- Filtros --}}
                    <div class="px-3 pt-3 pb-0">
                        <div class="row g-2">
                            <div class="col-md-4">
                                <label class="form-label small fw-semibold text-muted">Almacén</label>
                                <select class="form-select form-select-sm" id="filterWarehouseCritical" onchange="filterSemaforoTable('critical')">
                                    <option value="">Todos los almacenes</option>
                                    @foreach($almacenes as $almacen)
                                        <option value="{{ $almacen->id }}">{{ $almacen->warehouses_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label small fw-semibold text-muted">Producto</label>
                                <input type="text" class="form-control form-control-sm" id="filterProductCritical" placeholder="Buscar producto..." onkeyup="filterSemaforoTable('critical')">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label small fw-semibold text-muted">Fecha Máx. Caducidad</label>
                                <input type="date" class="form-control form-control-sm" id="filterDateCritical" onchange="filterSemaforoTable('critical')">
                            </div>
                        </div>
                    </div>
                    <div class="modal-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th class="text-center" style="min-width: 40px;">#</th>
                                        <th style="min-width: 120px;">Código</th>
                                        <th style="min-width: 120px;">Producto</th>
                                        <th style="min-width: 100px;">Almacén</th>
                                        <th style="min-width: 70px;">Rack</th>
                                        <th class="text-center" style="min-width: 60px;">Nivel</th>
                                        <th style="min-width: 100px;">Lote</th>
                                        <th class="text-center" style="min-width: 70px;">Cantidad</th>
                                        <th class="text-center" style="min-width: 95px;">Fecha Caducidad</th>
                                        <th class="text-center" style="min-width: 85px;">Días</th>
                                    </tr>
                                </thead>
                                <tbody id="tableCriticalBody">
                                    <tr>
                                        <td colspan="9" class="text-center text-muted py-4">
                                            <i class="bi bi-hourglass-split me-2"></i>Cargando datos...
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="modal-footer d-flex justify-content-between">
                        <span class="text-muted small" id="countCritical">0 productos</span>
                        <button type="button" class="btn btn-outline-secondary btn-sm" data-bs-dismiss="modal">
                            <i class="bi bi-x-circle me-1"></i>Cerrar
                        </button>
                    </div>
                </div>
            </div>
        </div>

        {{-- Modal ATENCIÓN --}}
        <div class="modal fade" id="modalAttention" tabindex="-1" aria-labelledby="modalAttentionLabel" aria-hidden="true">
            <div class="modal-dialog modal-xl modal-dialog-scrollable">
                <div class="modal-content border-0 shadow" style="border-left: 5px solid #ca8a04 !important;">
                    <div class="modal-header" style="background: linear-gradient(135deg, #fef9c3 0%, #fef08a 100%);">
                        <h5 class="modal-title fw-bold" id="modalAttentionLabel" style="color: #a16207;">
                            <i class="bi bi-clock-fill me-2"></i>Productos en Atención
                        </h5>
                        <span class="badge bg-warning text-dark ms-2">{{ $attention->getTotalStock() ?? 0 }}</span>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                    </div>
                    {{-- Filtros --}}
                    <div class="px-3 pt-3 pb-0">
                        <div class="row g-2">
                            <div class="col-md-4">
                                <label class="form-label small fw-semibold text-muted">Almacén</label>
                                <select class="form-select form-select-sm" id="filterWarehouseAttention" onchange="filterSemaforoTable('attention')">
                                    <option value="">Todos los almacenes</option>
                                    @foreach($almacenes as $almacen)
                                        <option value="{{ $almacen->id }}">{{ $almacen->warehouses_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label small fw-semibold text-muted">Producto</label>
                                <input type="text" class="form-control form-control-sm" id="filterProductAttention" placeholder="Buscar producto..." onkeyup="filterSemaforoTable('attention')">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label small fw-semibold text-muted">Fecha Máx. Caducidad</label>
                                <input type="date" class="form-control form-control-sm" id="filterDateAttention" onchange="filterSemaforoTable('attention')">
                            </div>
                        </div>
                    </div>
                    <div class="modal-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th class="text-center" style="min-width: 40px;">#</th>
                                        <th style="min-width: 120px;">Código</th>
                                        <th style="min-width: 120px;">Producto</th>
                                        <th style="min-width: 100px;">Almacén</th>
                                        <th style="min-width: 70px;">Rack</th>
                                        <th class="text-center" style="min-width: 60px;">Nivel</th>
                                        <th style="min-width: 100px;">Lote</th>
                                        <th class="text-center" style="min-width: 70px;">Cantidad</th>
                                        <th class="text-center" style="min-width: 95px;">Fecha Caducidad</th>
                                        <th class="text-center" style="min-width: 85px;">Días</th>
                                    </tr>
                                </thead>
                                <tbody id="tableAttentionBody">
                                    <tr>
                                        <td colspan="9" class="text-center text-muted py-4">
                                            <i class="bi bi-hourglass-split me-2"></i>Cargando datos...
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="modal-footer d-flex justify-content-between">
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
                <div class="modal-content border-0 shadow" style="border-left: 5px solid #16a34a !important;">
                    <div class="modal-header" style="background: linear-gradient(135deg, #dcfce7 0%, #bbf7d0 100%);">
                        <h5 class="modal-title fw-bold" id="modalOkLabel" style="color: #16a34a;">
                            <i class="bi bi-check-circle-fill me-2"></i>Productos Vigentes
                        </h5>
                        <span class="badge bg-success ms-2">{{ $ok->getTotalStock() ?? 0 }}</span>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                    </div>
                    {{-- Filtros --}}
                    <div class="px-3 pt-3 pb-0">
                        <div class="row g-2">
                            <div class="col-md-4">
                                <label class="form-label small fw-semibold text-muted">Almacén</label>
                                <select class="form-select form-select-sm" id="filterWarehouseOk" onchange="filterSemaforoTable('ok')">
                                    <option value="">Todos los almacenes</option>
                                    @foreach($almacenes as $almacen)
                                        <option value="{{ $almacen->id }}">{{ $almacen->warehouses_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label small fw-semibold text-muted">Producto</label>
                                <input type="text" class="form-control form-control-sm" id="filterProductOk" placeholder="Buscar producto..." onkeyup="filterSemaforoTable('ok')">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label small fw-semibold text-muted">Fecha Máx. Caducidad</label>
                                <input type="date" class="form-control form-control-sm" id="filterDateOk" onchange="filterSemaforoTable('ok')">
                            </div>
                        </div>
                    </div>
                    <div class="modal-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th class="text-center" style="min-width: 40px;">#</th>
                                        <th style="min-width: 120px;">Código</th>
                                        <th style="min-width: 120px;">Producto</th>
                                        <th style="min-width: 100px;">Almacén</th>
                                        <th style="min-width: 70px;">Rack</th>
                                        <th class="text-center" style="min-width: 60px;">Nivel</th>
                                        <th style="min-width: 100px;">Lote</th>
                                        <th class="text-center" style="min-width: 70px;">Cantidad</th>
                                        <th class="text-center" style="min-width: 95px;">Fecha Caducidad</th>
                                        <th class="text-center" style="min-width: 85px;">Días</th>
                                    </tr>
                                </thead>
                                <tbody id="tableOkBody">
                                    <tr>
                                        <td colspan="9" class="text-center text-muted py-4">
                                            <i class="bi bi-hourglass-split me-2"></i>Cargando datos...
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="modal-footer d-flex justify-content-between">
                        <span class="text-muted small" id="countOk">0 productos</span>
                        <button type="button" class="btn btn-outline-secondary btn-sm" data-bs-dismiss="modal">
                            <i class="bi bi-x-circle me-1"></i>Cerrar
                        </button>
                    </div>
                </div>
            </div>
        </div>

    </main>

    @push('scripts')
    <script>
        const semaforoData = {
            critical: {!! json_encode($semaforoCritical ?? []) !!},
            attention: {!! json_encode($semaforoAttention ?? []) !!},
            ok: {!! json_encode($semaforoOk ?? []) !!}
        };

        function formatNumber(num) {
            return new Intl.NumberFormat('es-MX').format(num);
        }

        function formatDate(dateStr) {
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

        function renderTableData(data, tbodyId, countId) {
            const tbody = document.getElementById(tbodyId);
            const countEl = document.getElementById(countId);
            if (!tbody) return;

            if (!data || data.length === 0) {
                tbody.innerHTML = `
                    <tr>
                        <td colspan="6" class="text-center text-muted py-4">
                            <i class="bi bi-inbox-fill me-2"></i>No hay productos en esta categoría
                        </td>
                    </tr>
                `;
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
                </tr>
            `).join('');

            if (countEl) countEl.textContent = `${data.length} producto${data.length !== 1 ? 's' : ''}`;
        }

        function filterSemaforoTable(type) {
            const warehouseFilter = document.getElementById(`filterWarehouse${capitalize(type)}`)?.value || '';
            const productFilter = document.getElementById(`filterProduct${capitalize(type)}`)?.value.toLowerCase() || '';
            const dateFilter = document.getElementById(`filterDate${capitalize(type)}`)?.value || '';
            
            let filteredData = semaforoData[type] || [];

            if (warehouseFilter) {
                filteredData = filteredData.filter(item => item.warehouseId == warehouseFilter);
            }

            if (productFilter) {
                filteredData = filteredData.filter(item => 
                    (item.productId || '').toLowerCase().includes(productFilter) ||
                    (item.productName || '').toLowerCase().includes(productFilter) 
                );
            }

            if (dateFilter) {
                const filterDate = new Date(dateFilter);
                filteredData = filteredData.filter(item => {
                    const itemDate = new Date(item.expirationDate);
                    return itemDate <= filterDate;
                });
            }

            const tbodyId = type === 'critical' ? 'tableCriticalBody' : 
                           type === 'attention' ? 'tableAttentionBody' : 'tableOkBody';
            const countId = `count${capitalize(type)}`;
            
            renderTableData(filteredData, tbodyId, countId);
        }

        function capitalize(str) {
            return str.charAt(0).toUpperCase() + str.slice(1);
        }

        let currentModal = null;

        function openSemaforoModal(type) {
            const modalId = type === 'critical' ? 'modalCritical' : 
                           type === 'attention' ? 'modalAttention' : 'modalOk';
            const tbodyId = type === 'critical' ? 'tableCriticalBody' : 
                           type === 'attention' ? 'tableAttentionBody' : 'tableOkBody';
            const countId = `count${capitalize(type)}`;

            if (semaforoData[type] && semaforoData[type].length > 0) {
                renderTableData(semaforoData[type], tbodyId, countId);
            } else {
                const tbody = document.getElementById(tbodyId);
                if (tbody) {
                    tbody.innerHTML = `
                        <tr>
                            <td colspan="6" class="text-center text-muted py-4">
                                <i class="bi bi-inbox-fill me-2"></i>No hay productos en esta categoría
                            </td>
                        </tr>
                    `;
                }
                if (document.getElementById(countId)) {
                    document.getElementById(countId).textContent = '0 productos';
                }
            }

            currentModal = new bootstrap.Modal(document.getElementById(modalId));
            currentModal.show();
        }

        function resetFilters(type) {
            const warehouseFilter = document.getElementById(`filterWarehouse${capitalize(type)}`);
            const productFilter = document.getElementById(`filterProduct${capitalize(type)}`);
            const dateFilter = document.getElementById(`filterDate${capitalize(type)}`);
            
            if (warehouseFilter) warehouseFilter.value = '';
            if (productFilter) productFilter.value = '';
            if (dateFilter) dateFilter.value = '';
            
            filterSemaforoTable(type);
        }

        document.getElementById('modalCritical')?.addEventListener('hidden.bs.modal', function () {
            currentModal = null;
            resetFilters('critical');
        });
        document.getElementById('modalAttention')?.addEventListener('hidden.bs.modal', function () {
            currentModal = null;
            resetFilters('attention');
        });
        document.getElementById('modalOk')?.addEventListener('hidden.bs.modal', function () {
            currentModal = null;
            resetFilters('ok');
        });
    </script>
    @endpush

    <style>
        .clickable-card {
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }
        .clickable-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15) !important;
        }
    </style>
@endsection
