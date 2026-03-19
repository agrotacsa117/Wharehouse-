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
                                @if ($critical->getTotalStock() > 0)
                                    <span class="badge bg-danger ms-2 animate__animated animate__pulse animate__infinite">
                                        <i class="bi bi-exclamation-triangle-fill me-1"></i>{{ $critical->getTotalStock() }}
                                        críticos
                                    </span>
                                @endif
                            </h5>
                            <div class="row g-3">
                                {{-- Tarjeta CRÍTICO (Rojo) - Menos de 90 días --}}
                                <div class="col-lg-4 col-md-6">
                                    <div class="card h-100 border-0 shadow-sm position-relative overflow-hidden semaforo-card"
                                        style="background: linear-gradient(135deg, #fee2e2 0%, #fecaca 100%); border-left: 4px solid #dc2626 !important; cursor: pointer;"
                                        data-bs-toggle="modal" data-bs-target="#modalCritico" data-status="critical"
                                        role="button" tabindex="0" aria-label="Ver productos críticos">
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
                                                    <span
                                                        class="text-muted">{{ $warehouseStat->getWarehouseName() }}:</span>
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
                                    <div class="card h-100 border-0 shadow-sm position-relative overflow-hidden semaforo-card"
                                        style="background: linear-gradient(135deg, #fef9c3 0%, #fef08a 100%); border-left: 4px solid #ca8a04 !important; cursor: pointer;"
                                        data-bs-toggle="modal" data-bs-target="#modalAtencion" data-status="attention"
                                        role="button" tabindex="0" aria-label="Ver productos en atención">
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
                                                    <span
                                                        class="text-muted">{{ $warehouseStat->getWarehouseName() }}:</span>
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
                                    <div class="card h-100 border-0 shadow-sm position-relative overflow-hidden semaforo-card"
                                        style="background: linear-gradient(135deg, #dcfce7 0%, #bbf7d0 100%); border-left: 4px solid #16a34a !important; cursor: pointer;"
                                        data-bs-toggle="modal" data-bs-target="#modalOk" data-status="ok"
                                        role="button" tabindex="0" aria-label="Ver productos OK">
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
                                                    <span
                                                        class="text-muted">{{ $warehouseStat->getWarehouseName() }}:</span>
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
    </main>

    {{-- ============================================================= --}}
    {{--              MODALES DE SEMÁFORO DE CADUCIDAD                 --}}
    {{-- ============================================================= --}}

    {{-- Modal CRÍTICO (Rojo) --}}
    <div class="modal fade" id="modalCritico" tabindex="-1" aria-labelledby="modalCriticoLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content border-0 shadow">
                <div class="modal-header text-white position-relative" style="background: linear-gradient(135deg, #dc2626 0%, #b91c1c 100%);">
                    <div class="d-flex align-items-center gap-3">
                        <span class="rounded-circle d-inline-flex align-items-center justify-content-center" 
                            style="width: 20px; height: 20px; background-color: #fff; box-shadow: 0 0 12px rgba(255, 255, 255, 0.6);">
                        </span>
                        <div>
                            <h5 class="modal-title mb-0 fw-bold" id="modalCriticoLabel">
                                <i class="bi bi-exclamation-triangle-fill me-2"></i>Productos Críticos
                            </h5>
                            <small class="opacity-75">Menos de 90 días para caducar</small>
                        </div>
                    </div>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                </div>
                <div class="modal-body p-0">
                    <div id="loadingCritico" class="text-center py-5">
                        <div class="spinner-border text-danger" role="status">
                            <span class="visually-hidden">Cargando...</span>
                        </div>
                        <p class="mt-3 text-muted">Cargando productos...</p>
                    </div>
                    <div id="tablaCritico" class="d-none">
                        <div class="table-responsive">
                            <table class="table table-hover table-striped mb-0">
                                <thead class="table-light sticky-top">
                                    <tr>
                                        <th class="fw-semibold text-muted small text-uppercase">Producto</th>
                                        <th class="fw-semibold text-muted small text-uppercase">Almacén</th>
                                        <th class="fw-semibold text-muted small text-uppercase text-center">Stock</th>
                                        <th class="fw-semibold text-muted small text-uppercase text-center">Fecha Caducidad</th>
                                        <th class="fw-semibold text-muted small text-uppercase text-center">Días Restantes</th>
                                        <th class="fw-semibold text-muted small text-uppercase text-center">Estado</th>
                                    </tr>
                                </thead>
                                <tbody id="tbodyCritico">
                                    {{-- Los datos se cargarán dinámicamente --}}
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div id="emptyCritico" class="text-center py-5 d-none">
                        <i class="bi bi-inbox text-muted" style="font-size: 4rem;"></i>
                        <p class="mt-3 text-muted">No hay productos en estado crítico</p>
                    </div>
                </div>
                <div class="modal-footer bg-light">
                    <span class="text-muted small me-auto" id="totalCritico"></span>
                    <button type="button" class="btn btn-outline-danger" data-bs-dismiss="modal">
                        <i class="bi bi-x-lg me-1"></i>Cerrar
                    </button>
                </div>
            </div>
        </div>
    </div>

    {{-- Modal ATENCIÓN (Amarillo) --}}
    <div class="modal fade" id="modalAtencion" tabindex="-1" aria-labelledby="modalAtencionLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content border-0 shadow">
                <div class="modal-header text-dark position-relative" style="background: linear-gradient(135deg, #fbbf24 0%, #f59e0b 100%);">
                    <div class="d-flex align-items-center gap-3">
                        <span class="rounded-circle d-inline-flex align-items-center justify-content-center" 
                            style="width: 20px; height: 20px; background-color: #fff; box-shadow: 0 0 12px rgba(255, 255, 255, 0.6);">
                        </span>
                        <div>
                            <h5 class="modal-title mb-0 fw-bold" id="modalAtencionLabel" style="color: #78350f;">
                                <i class="bi bi-clock-fill me-2"></i>Productos en Atención
                            </h5>
                            <small style="color: #92400e;">Entre 90 y 120 días para caducar</small>
                        </div>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                </div>
                <div class="modal-body p-0">
                    <div id="loadingAtencion" class="text-center py-5">
                        <div class="spinner-border text-warning" role="status">
                            <span class="visually-hidden">Cargando...</span>
                        </div>
                        <p class="mt-3 text-muted">Cargando productos...</p>
                    </div>
                    <div id="tablaAtencion" class="d-none">
                        <div class="table-responsive">
                            <table class="table table-hover table-striped mb-0">
                                <thead class="table-light sticky-top">
                                    <tr>
                                        <th class="fw-semibold text-muted small text-uppercase">Producto</th>
                                        <th class="fw-semibold text-muted small text-uppercase">Almacén</th>
                                        <th class="fw-semibold text-muted small text-uppercase text-center">Stock</th>
                                        <th class="fw-semibold text-muted small text-uppercase text-center">Fecha Caducidad</th>
                                        <th class="fw-semibold text-muted small text-uppercase text-center">Días Restantes</th>
                                        <th class="fw-semibold text-muted small text-uppercase text-center">Estado</th>
                                    </tr>
                                </thead>
                                <tbody id="tbodyAtencion">
                                    {{-- Los datos se cargarán dinámicamente --}}
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div id="emptyAtencion" class="text-center py-5 d-none">
                        <i class="bi bi-inbox text-muted" style="font-size: 4rem;"></i>
                        <p class="mt-3 text-muted">No hay productos en estado de atención</p>
                    </div>
                </div>
                <div class="modal-footer bg-light">
                    <span class="text-muted small me-auto" id="totalAtencion"></span>
                    <button type="button" class="btn btn-outline-warning" data-bs-dismiss="modal" style="color: #92400e; border-color: #f59e0b;">
                        <i class="bi bi-x-lg me-1"></i>Cerrar
                    </button>
                </div>
            </div>
        </div>
    </div>

    {{-- Modal OK (Verde) --}}
    <div class="modal fade" id="modalOk" tabindex="-1" aria-labelledby="modalOkLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content border-0 shadow">
                <div class="modal-header text-white position-relative" style="background: linear-gradient(135deg, #16a34a 0%, #15803d 100%);">
                    <div class="d-flex align-items-center gap-3">
                        <span class="rounded-circle d-inline-flex align-items-center justify-content-center" 
                            style="width: 20px; height: 20px; background-color: #fff; box-shadow: 0 0 12px rgba(255, 255, 255, 0.6);">
                        </span>
                        <div>
                            <h5 class="modal-title mb-0 fw-bold" id="modalOkLabel">
                                <i class="bi bi-check-circle-fill me-2"></i>Productos OK
                            </h5>
                            <small class="opacity-75">Más de 120 días para caducar</small>
                        </div>
                    </div>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                </div>
                <div class="modal-body p-0">
                    <div id="loadingOk" class="text-center py-5">
                        <div class="spinner-border text-success" role="status">
                            <span class="visually-hidden">Cargando...</span>
                        </div>
                        <p class="mt-3 text-muted">Cargando productos...</p>
                    </div>
                    <div id="tablaOk" class="d-none">
                        <div class="table-responsive">
                            <table class="table table-hover table-striped mb-0">
                                <thead class="table-light sticky-top">
                                    <tr>
                                        <th class="fw-semibold text-muted small text-uppercase">Producto</th>
                                        <th class="fw-semibold text-muted small text-uppercase">Almacén</th>
                                        <th class="fw-semibold text-muted small text-uppercase text-center">Stock</th>
                                        <th class="fw-semibold text-muted small text-uppercase text-center">Fecha Caducidad</th>
                                        <th class="fw-semibold text-muted small text-uppercase text-center">Días Restantes</th>
                                        <th class="fw-semibold text-muted small text-uppercase text-center">Estado</th>
                                    </tr>
                                </thead>
                                <tbody id="tbodyOk">
                                    {{-- Los datos se cargarán dinámicamente --}}
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div id="emptyOk" class="text-center py-5 d-none">
                        <i class="bi bi-inbox text-muted" style="font-size: 4rem;"></i>
                        <p class="mt-3 text-muted">No hay productos en estado OK</p>
                    </div>
                </div>
                <div class="modal-footer bg-light">
                    <span class="text-muted small me-auto" id="totalOk"></span>
                    <button type="button" class="btn btn-outline-success" data-bs-dismiss="modal">
                        <i class="bi bi-x-lg me-1"></i>Cerrar
                    </button>
                </div>
            </div>
        </div>
    </div>

    {{-- ============================================================= --}}
    {{--              ESTILOS ADICIONALES PARA LOS MODALES             --}}
    {{-- ============================================================= --}}
    <style>
        /* Efecto hover en las tarjetas del semáforo */
        .semaforo-card {
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }
        .semaforo-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15) !important;
        }
        .semaforo-card:focus {
            outline: 3px solid rgba(59, 130, 246, 0.5);
            outline-offset: 2px;
        }

        /* Estilos para la tabla en los modales */
        .modal-body .table th {
            border-bottom: 2px solid #dee2e6;
        }
        .modal-body .table td {
            vertical-align: middle;
        }

        /* Badge de estado personalizado */
        .badge-expiry {
            font-size: 0.75rem;
            padding: 0.4em 0.8em;
            border-radius: 50px;
        }
        .badge-critical {
            background-color: #fee2e2;
            color: #dc2626;
            border: 1px solid #fecaca;
        }
        .badge-attention {
            background-color: #fef9c3;
            color: #ca8a04;
            border: 1px solid #fef08a;
        }
        .badge-ok {
            background-color: #dcfce7;
            color: #16a34a;
            border: 1px solid #bbf7d0;
        }

        /* Sticky header en la tabla */
        .modal-body .sticky-top {
            top: 0;
            z-index: 10;
            background: #f8f9fa !important;
        }

        /* Animación suave para el modal */
        .modal.fade .modal-dialog {
            transition: transform 0.3s ease-out;
        }

        /* Responsive para móviles */
        @media (max-width: 576px) {
            .modal-body .table {
                font-size: 0.85rem;
            }
            .modal-body .table th,
            .modal-body .table td {
                padding: 0.5rem;
            }
        }
    </style>

    {{-- ============================================================= --}}
    {{--              JAVASCRIPT PARA CARGAR DATOS                     --}}
    {{-- ============================================================= --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Configuración de los estados
            const statusConfig = {
                critical: {
                    modalId: 'modalCritico',
                    loadingId: 'loadingCritico',
                    tableId: 'tablaCritico',
                    tbodyId: 'tbodyCritico',
                    emptyId: 'emptyCritico',
                    totalId: 'totalCritico',
                    badgeClass: 'badge-critical',
                    statusText: 'Crítico'
                },
                attention: {
                    modalId: 'modalAtencion',
                    loadingId: 'loadingAtencion',
                    tableId: 'tablaAtencion',
                    tbodyId: 'tbodyAtencion',
                    emptyId: 'emptyAtencion',
                    totalId: 'totalAtencion',
                    badgeClass: 'badge-attention',
                    statusText: 'Atención'
                },
                ok: {
                    modalId: 'modalOk',
                    loadingId: 'loadingOk',
                    tableId: 'tablaOk',
                    tbodyId: 'tbodyOk',
                    emptyId: 'emptyOk',
                    totalId: 'totalOk',
                    badgeClass: 'badge-ok',
                    statusText: 'OK'
                }
            };

            // Función para formatear fecha
            function formatDate(dateString) {
                if (!dateString) return '-';
                const date = new Date(dateString);
                return date.toLocaleDateString('es-MX', {
                    year: 'numeric',
                    month: 'short',
                    day: 'numeric'
                });
            }

            // Función para formatear número
            function formatNumber(num) {
                return new Intl.NumberFormat('es-MX').format(num || 0);
            }

            // Función para generar el HTML de una fila
            function generateRow(item, config) {
                const daysRemaining = item.days_remaining || 0;
                let badgeClass = config.badgeClass;
                let statusText = config.statusText;

                // Determinar el badge según los días restantes
                if (daysRemaining < 0) {
                    badgeClass = 'badge-critical';
                    statusText = 'Vencido';
                } else if (daysRemaining < 90) {
                    badgeClass = 'badge-critical';
                    statusText = 'Crítico';
                } else if (daysRemaining <= 120) {
                    badgeClass = 'badge-attention';
                    statusText = 'Atención';
                } else {
                    badgeClass = 'badge-ok';
                    statusText = 'OK';
                }

                return `
                    <tr>
                        <td>
                            <div class="fw-medium">${item.product_name || item.nombre_producto || '-'}</div>
                            ${item.sku ? `<small class="text-muted">${item.sku}</small>` : ''}
                        </td>
                        <td>
                            <i class="bi bi-building me-1 text-muted"></i>
                            ${item.warehouses_name || item.almacen || '-'}
                        </td>
                        <td class="text-center">
                            <span class="fw-bold">${formatNumber(item.stock || item.cantidad_stock)}</span>
                        </td>
                        <td class="text-center">
                            <i class="bi bi-calendar3 me-1 text-muted"></i>
                            ${formatDate(item.expiration_date || item.fecha_caducidad)}
                        </td>
                        <td class="text-center">
                            <span class="fw-bold ${daysRemaining < 0 ? 'text-danger' : ''}">${daysRemaining}</span>
                            <small class="text-muted">días</small>
                        </td>
                        <td class="text-center">
                            <span class="badge badge-expiry ${badgeClass}">
                                ${statusText}
                            </span>
                        </td>
                    </tr>
                `;
            }

            // Función para cargar datos del servidor
            async function loadExpiryData(status) {
                const config = statusConfig[status];
                const loading = document.getElementById(config.loadingId);
                const table = document.getElementById(config.tableId);
                const tbody = document.getElementById(config.tbodyId);
                const empty = document.getElementById(config.emptyId);
                const total = document.getElementById(config.totalId);

                // Mostrar loading
                loading.classList.remove('d-none');
                table.classList.add('d-none');
                empty.classList.add('d-none');

                try {
                    // Realizar petición AJAX al endpoint
                    const response = await fetch(`{{ url('/dashboard/expiry-products') }}/${status}`, {
                        method: 'GET',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
                        }
                    });

                    if (!response.ok) {
                        throw new Error('Error al cargar los datos');
                    }

                    const data = await response.json();

                    // Ocultar loading
                    loading.classList.add('d-none');

                    if (data.products && data.products.length > 0) {
                        // Generar filas de la tabla
                        tbody.innerHTML = data.products.map(item => generateRow(item, config)).join('');
                        table.classList.remove('d-none');
                        total.textContent = `Total: ${formatNumber(data.products.length)} productos | Stock total: ${formatNumber(data.total_stock || 0)} unidades`;
                    } else {
                        empty.classList.remove('d-none');
                        total.textContent = '';
                    }
                } catch (error) {
                    console.error('Error:', error);
                    loading.classList.add('d-none');
                    tbody.innerHTML = `
                        <tr>
                            <td colspan="6" class="text-center py-4 text-danger">
                                <i class="bi bi-exclamation-circle me-2"></i>
                                Error al cargar los datos. Por favor, intente nuevamente.
                            </td>
                        </tr>
                    `;
                    table.classList.remove('d-none');
                }
            }

            // Agregar eventos a los modales
            Object.keys(statusConfig).forEach(status => {
                const config = statusConfig[status];
                const modal = document.getElementById(config.modalId);

                if (modal) {
                    modal.addEventListener('show.bs.modal', function() {
                        loadExpiryData(status);
                    });

                    // Limpiar tabla al cerrar el modal
                    modal.addEventListener('hidden.bs.modal', function() {
                        const tbody = document.getElementById(config.tbodyId);
                        const loading = document.getElementById(config.loadingId);
                        const table = document.getElementById(config.tableId);
                        const empty = document.getElementById(config.emptyId);

                        tbody.innerHTML = '';
                        loading.classList.remove('d-none');
                        table.classList.add('d-none');
                        empty.classList.add('d-none');
                    });
                }
            });

            // Permitir abrir modales con Enter/Space en las tarjetas
            document.querySelectorAll('.semaforo-card').forEach(card => {
                card.addEventListener('keydown', function(e) {
                    if (e.key === 'Enter' || e.key === ' ') {
                        e.preventDefault();
                        this.click();
                    }
                });
            });
        });
    </script>
@endsection
