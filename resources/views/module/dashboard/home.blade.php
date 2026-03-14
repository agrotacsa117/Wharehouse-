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
                                @if (isset($criticoTotal) && $criticoTotal > 0)
                                    <span class="badge bg-danger ms-2 animate__animated animate__pulse animate__infinite">
                                        <i class="bi bi-exclamation-triangle-fill me-1"></i>{{ $criticoTotal }} críticos
                                    </span>
                                @endif
                            </h5>
                            <div class="row g-3">
                                {{-- Tarjeta CRÍTICO (Rojo) - Menos de 90 días --}}
                                <div class="col-lg-4 col-md-6">
                                    <div class="card h-100 border-0 shadow-sm position-relative overflow-hidden"
                                        style="background: linear-gradient(135deg, #fee2e2 0%, #fecaca 100%); border-left: 4px solid #dc2626 !important;">
                                        <div class="card-body">
                                            <div class="d-flex align-items-center gap-2 mb-3">
                                                <span
                                                    class="rounded-circle d-inline-flex align-items-center justify-content-center"
                                                    style="width: 14px; height: 14px; background-color: #dc2626; box-shadow: 0 0 8px rgba(220, 38, 38, 0.6);">
                                                </span>
                                                <span class="fw-semibold text-danger">Crítico</span>
                                            </div>
                                            <h2 class="fw-bold mb-2" style="color: #dc2626; font-size: 2.5rem;">
                                                {{ number_format($criticoTotal ?? 0, 0, ',', '.') }}
                                            </h2>
                                            <p class="text-danger mb-3 small fw-medium">
                                                <i class="bi bi-calendar-x me-1"></i>Menos de 90 días
                                            </p>
                                            <hr class="my-2" style="border-color: rgba(220, 38, 38, 0.2);">
                                            <div class="d-flex justify-content-between small">
                                                <span class="text-muted">Tapachula:</span>
                                                <span class="fw-bold"
                                                    style="color: #dc2626;">{{ number_format($criticoTapachula ?? 0, 0, ',', '.') }}</span>
                                            </div>
                                            <div class="d-flex justify-content-between small">
                                                <span class="text-muted">Dorado:</span>
                                                <span class="fw-bold"
                                                    style="color: #dc2626;">{{ number_format($criticoDorado ?? 0, 0, ',', '.') }}</span>
                                            </div>
                                        </div>
                                        {{-- Icono decorativo de fondo --}}
                                        <i class="bi bi-exclamation-triangle-fill position-absolute"
                                            style="font-size: 5rem; bottom: -10px; right: -10px; color: rgba(220, 38, 38, 0.1);"></i>
                                    </div>
                                </div>

                                {{-- Tarjeta ATENCIÓN (Amarillo) - Entre 90 y 120 días --}}
                                <div class="col-lg-4 col-md-6">
                                    <div class="card h-100 border-0 shadow-sm position-relative overflow-hidden"
                                        style="background: linear-gradient(135deg, #fef9c3 0%, #fef08a 100%); border-left: 4px solid #ca8a04 !important;">
                                        <div class="card-body">
                                            <div class="d-flex align-items-center gap-2 mb-3">
                                                <span
                                                    class="rounded-circle d-inline-flex align-items-center justify-content-center"
                                                    style="width: 14px; height: 14px; background-color: #ca8a04; box-shadow: 0 0 8px rgba(202, 138, 4, 0.6);">
                                                </span>
                                                <span class="fw-semibold" style="color: #a16207;">Atención</span>
                                            </div>
                                            <h2 class="fw-bold mb-2" style="color: #ca8a04; font-size: 2.5rem;">
                                                {{ number_format($atencionTotal ?? 0, 0, ',', '.') }}
                                            </h2>
                                            <p class="small fw-medium mb-3" style="color: #a16207;">
                                                <i class="bi bi-calendar-event me-1"></i>Entre 90 y 120 días
                                            </p>
                                            <hr class="my-2" style="border-color: rgba(202, 138, 4, 0.2);">
                                            <div class="d-flex justify-content-between small">
                                                <span class="text-muted">Tapachula:</span>
                                                <span class="fw-bold"
                                                    style="color: #ca8a04;">{{ number_format($atencionTapachula ?? 0, 0, ',', '.') }}</span>
                                            </div>
                                            <div class="d-flex justify-content-between small">
                                                <span class="text-muted">Dorado:</span>
                                                <span class="fw-bold"
                                                    style="color: #ca8a04;">{{ number_format($atencionDorado ?? 0, 0, ',', '.') }}</span>
                                            </div>
                                        </div>
                                        {{-- Icono decorativo de fondo --}}
                                        <i class="bi bi-clock-fill position-absolute"
                                            style="font-size: 5rem; bottom: -10px; right: -10px; color: rgba(202, 138, 4, 0.1);"></i>
                                    </div>
                                </div>

                                {{-- Tarjeta OK (Verde) - Más de 120 días --}}
                                <div class="col-lg-4 col-md-12">
                                    <div class="card h-100 border-0 shadow-sm position-relative overflow-hidden"
                                        style="background: linear-gradient(135deg, #dcfce7 0%, #bbf7d0 100%); border-left: 4px solid #16a34a !important;">
                                        <div class="card-body">
                                            <div class="d-flex align-items-center gap-2 mb-3">
                                                <span
                                                    class="rounded-circle d-inline-flex align-items-center justify-content-center"
                                                    style="width: 14px; height: 14px; background-color: #16a34a; box-shadow: 0 0 8px rgba(22, 163, 74, 0.6);">
                                                </span>
                                                <span class="fw-semibold text-success">OK</span>
                                            </div>
                                            <h2 class="fw-bold mb-2" style="color: #16a34a; font-size: 2.5rem;">
                                                {{ number_format($okTotal ?? 0, 0, ',', '.') }}
                                            </h2>
                                            <p class="text-success mb-3 small fw-medium">
                                                <i class="bi bi-calendar-check me-1"></i>Más de 120 días
                                            </p>
                                            <hr class="my-2" style="border-color: rgba(22, 163, 74, 0.2);">
                                            <div class="d-flex justify-content-between small">
                                                <span class="text-muted">Tapachula:</span>
                                                <span class="fw-bold"
                                                    style="color: #16a34a;">{{ number_format($okTapachula ?? 0, 0, ',', '.') }}</span>
                                            </div>
                                            <div class="d-flex justify-content-between small">
                                                <span class="text-muted">Dorado:</span>
                                                <span class="fw-bold"
                                                    style="color: #16a34a;">{{ number_format($okDorado ?? 0, 0, ',', '.') }}</span>
                                            </div>
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
@endsection
