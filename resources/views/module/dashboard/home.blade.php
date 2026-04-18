@extends('layouts.main')

@section('titulo', $titulo)

@section('contenido')
    <main id="main" class="main" style="background-color: #f8f9fa; min-height: 100vh; padding: 24px;">
        
        {{-- ============================================================= --}}
        {{--              ENCABEZADO DEL DASHBOARD                          --}}
        {{-- ============================================================= --}}
        <div class="mb-4">
            <h1 class="fw-bold mb-1" style="font-size: 1.75rem; color: #1f2937;">Dashboard</h1>
            <p class="text-muted mb-0" style="font-size: 0.95rem;">Vista general del inventario y caducidad</p>
        </div>

        <section class="section dashboard">
            @php
                $user = auth()->user();
                $esAdmin = $user->rol === 'admin';
                $esTapachula = $user->rol === 'tapachula';
                $esDorado = $user->rol === 'bodega_dorado';
            @endphp

            {{-- ============================================================= --}}
            {{--              4 TARJETAS RESUMEN SUPERIORES                    --}}
            {{-- ============================================================= --}}
            <div class="row g-3 mb-4">
                {{-- Tarjeta 1: Total Productos --}}
                <div class="col-xl-3 col-lg-6 col-md-6">
                    <div class="card border-0 shadow-sm h-100" style="border-radius: 12px;">
                        <div class="card-body d-flex align-items-center justify-content-between py-3 px-4">
                            <div>
                                <p class="text-muted mb-1 small fw-medium">Total Productos</p>
                                <h3 class="fw-bold mb-0" style="font-size: 1.75rem; color: #1f2937;">
                                    {{ number_format(($critical->getTotalStock() ?? 0) + ($attention->getTotalStock() ?? 0) + ($ok->getTotalStock() ?? 0), 0, ',') }}
                                </h3>
                            </div>
                            <div class="d-flex align-items-center justify-content-center" 
                                 style="width: 48px; height: 48px; background-color: #dbeafe; border-radius: 10px;">
                                <i class="bi bi-box-seam" style="font-size: 1.5rem; color: #3b82f6;"></i>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Tarjeta 2: Bodegas Activas --}}
                <div class="col-xl-3 col-lg-6 col-md-6">
                    <div class="card border-0 shadow-sm h-100" style="border-radius: 12px;">
                        <div class="card-body d-flex align-items-center justify-content-between py-3 px-4">
                            <div>
                                <p class="text-muted mb-1 small fw-medium">Bodegas Activas</p>
                                <h3 class="fw-bold mb-0" style="font-size: 1.75rem; color: #1f2937;">
                                    {{ count($almacenes ?? []) }}
                                </h3>
                            </div>
                            <div class="d-flex align-items-center justify-content-center" 
                                 style="width: 48px; height: 48px; background-color: #fef3c7; border-radius: 10px;">
                                <i class="bi bi-building" style="font-size: 1.5rem; color: #f59e0b;"></i>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Tarjeta 3: Items en Inventario --}}
                <div class="col-xl-3 col-lg-6 col-md-6">
                    <div class="card border-0 shadow-sm h-100" style="border-radius: 12px;">
                        <div class="card-body d-flex align-items-center justify-content-between py-3 px-4">
                            <div>
                                <p class="text-muted mb-1 small fw-medium">Items en Inventario</p>
                                <h3 class="fw-bold mb-0" style="font-size: 1.75rem; color: #1f2937;">
                                    {{ number_format(($attention->getTotalStock() ?? 0) + ($ok->getTotalStock() ?? 0), 0, ',', '.') }}
                                </h3>
                            </div>
                            <div class="d-flex align-items-center justify-content-center" 
                                 style="width: 48px; height: 48px; background-color: #d1fae5; border-radius: 10px;">
                                <i class="bi bi-check-circle" style="font-size: 1.5rem; color: #10b981;"></i>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Tarjeta 4: Criticos (<90 dias) --}}
                <div class="col-xl-3 col-lg-6 col-md-6">
                    <div class="card border-0 shadow-sm h-100" style="border-radius: 12px; background-color: #fef2f2;">
                        <div class="card-body d-flex align-items-center justify-content-between py-3 px-4">
                            <div>
                                <p class="mb-1 small fw-medium" style="color: #dc2626;">Criticos (&lt;90 dias)</p>
                                <h3 class="fw-bold mb-0" style="font-size: 1.75rem; color: #dc2626;">
                                    {{ number_format($critical->getTotalStock() ?? 0, 0, ',', '.') }}
                                </h3>
                            </div>
                            <div class="d-flex align-items-center justify-content-center" 
                                 style="width: 48px; height: 48px; background-color: #fecaca; border-radius: 10px;">
                                <i class="bi bi-exclamation-triangle" style="font-size: 1.5rem; color: #dc2626;"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- ============================================================= --}}
            {{--              SEMAFORO DE CADUCIDAD - ESTILO LIMPIO            --}}
            {{-- ============================================================= --}}
            <div class="card border-0 shadow-sm mb-4" style="border-radius: 12px;">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center gap-2 mb-4">
                        <i class="bi bi-clock-history" style="font-size: 1.25rem; color: #6b7280;"></i>
                        <h5 class="fw-semibold mb-0" style="color: #374151;">Semaforo de Caducidad</h5>
                    </div>
                    
                    <div class="row g-3">
                        {{-- Tarjeta CRITICO (Rojo) - Menos de 90 dias --}}
                        <div class="col-lg-4 col-md-6">
                            <div class="card h-100 border-0 clickable-card"
                                style="background-color: #fef2f2; border-radius: 12px; border-left: 4px solid #dc2626 !important; cursor: pointer;"
                                onclick="openSemaforoModal('critical')">
                                <div class="card-body p-4">
                                    <div class="d-flex align-items-center gap-2 mb-3">
                                        <span class="rounded-circle"
                                            style="width: 12px; height: 12px; background-color: #dc2626; display: inline-block;">
                                        </span>
                                        <span class="fw-semibold" style="color: #dc2626;">Critico</span>
                                    </div>
                                    <h2 class="fw-bold mb-2" style="color: #dc2626; font-size: 2.5rem;">
                                        {{ number_format($critical->getTotalStock() ?? 0, 0, ',', '.') }}
                                    </h2>
                                    <p class="mb-0 small" style="color: #f87171;">
                                        Menos de 90 dias
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
                            </div>
                        </div>

                        {{-- Tarjeta ATENCION (Amarillo) - Entre 90 y 120 dias --}}
                        <div class="col-lg-4 col-md-6">
                            <div class="card h-100 border-0 clickable-card"
                                style="background-color: #fefce8; border-radius: 12px; border-left: 4px solid #ca8a04 !important; cursor: pointer;"
                                onclick="openSemaforoModal('attention')">
                                <div class="card-body p-4">
                                    <div class="d-flex align-items-center gap-2 mb-3">
                                        <span class="rounded-circle"
                                            style="width: 12px; height: 12px; background-color: #ca8a04; display: inline-block;">
                                        </span>
                                        <span class="fw-semibold" style="color: #ca8a04;">Atencion</span>
                                    </div>
                                    <h2 class="fw-bold mb-2" style="color: #ca8a04; font-size: 2.5rem;">
                                        {{ number_format($attention->getTotalStock() ?? 0, 0, ',', '.') }}
                                    </h2>
                                    <p class="mb-0 small" style="color: #eab308;">
                                        Entre 90 y 120 dias
                                    </p>
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
                            </div>
                        </div>

                        {{-- Tarjeta OK (Verde) - Mas de 120 dias --}}
                        <div class="col-lg-4 col-md-12">
                            <div class="card h-100 border-0 clickable-card"
                                style="background-color: #f0fdf4; border-radius: 12px; border-left: 4px solid #16a34a !important; cursor: pointer;"
                                onclick="openSemaforoModal('ok')">
                                <div class="card-body p-4">
                                    <div class="d-flex align-items-center gap-2 mb-3">
                                        <span class="rounded-circle"
                                            style="width: 12px; height: 12px; background-color: #16a34a; display: inline-block;">
                                        </span>
                                        <span class="fw-semibold" style="color: #16a34a;">OK</span>
                                    </div>
                                    <h2 class="fw-bold mb-2" style="color: #16a34a; font-size: 2.5rem;">
                                        {{ number_format($ok->getTotalStock() ?? 0, 0, ',', '.') }}
                                    </h2>
                                    <p class="mb-0 small" style="color: #22c55e;">
                                        Mas de 120 dias
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
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            {{-- ============================================================= --}}
            {{--              FIN SEMAFORO DE CADUCIDAD                        --}}
            {{-- ============================================================= --}}

            <div class="row">
                <div class="col-lg-12">
                    <div class="row">
                        @if ($esAdmin || $esTapachula)
                            <!-- Card 1: Total Productos Bodega Tapachula -->
                           
                        @endif

                        @if ($esAdmin || $esDorado)
                          
                        @endif

                        @if ($esAdmin)
                            <!-- Card 3: Total General de Productos (solo admin) -->
                           
                        @endif

                        {{-- ------------------------------------------------------------- --}}
                        {{--       MEJORA VISUAL PARA LAS TARJETAS "POR VENCER"            --}}
                        {{-- ------------------------------------------------------------- --}}

                        <div class="w-100"></div>
                        @if ($esAdmin || $esTapachula)
                            <!-- Card 4: Por Vencer Bodega Tapachula (MEJORADO) -->
                           
                        @endif

                        @if ($esAdmin || $esDorado)
                            <!-- Card 5: Por Vencer Bodega Dorado (MEJORADO) -->
                            
                        @endif

                        <div class="w-100"></div>
                        @if ($esAdmin || $esTapachula)
                            <!-- Tarjeta de racks Tapachula (circular) -->
                    
                        @endif

                        @if ($esAdmin || $esDorado)
                            <!-- Tarjeta de racks Dorado (circular) -->
                            
                        @endif
                    </div>
                </div>
            </div>
        </section>

        {{-- ============================================================= --}}
        {{--              MODALES DEL SEMAFORO DE CADUCIDAD                  --}}
        {{-- ============================================================= --}}

        {{-- Modal CRITICO --}}
        <div class="modal fade" id="modalCritical" tabindex="-1" aria-labelledby="modalCriticalLabel" aria-hidden="true">
            <div class="modal-dialog modal-xl modal-dialog-scrollable">
                <div class="modal-content border-0 shadow" style="border-left: 5px solid #dc2626 !important;">
                    <div class="modal-header" style="background: linear-gradient(135deg, #fee2e2 0%, #fecaca 100%);">
                        <h5 class="modal-title fw-bold" id="modalCriticalLabel" style="color: #dc2626;">
                            <i class="bi bi-exclamation-triangle-fill me-2"></i>Productos Criticos
                        </h5>
                        <span class="badge bg-danger ms-2">{{ $critical->getTotalStock() ?? 0 }}</span>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                    </div>
                    {{-- Filtros --}}
                    <div class="px-3 pt-3 pb-0">
                        <div class="row g-2">
                            <div class="col-md-4">
                                <label class="form-label small fw-semibold text-muted">Almacen</label>
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
                                <label class="form-label small fw-semibold text-muted">Fecha Max. Caducidad</label>
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
                                        <th style="min-width: 120px;">Codigo</th>
                                        <th style="min-width: 120px;">Producto</th>
                                        <th style="min-width: 100px;">Almacen</th>
                                        <th style="min-width: 70px;">Rack</th>
                                        <th class="text-center" style="min-width: 60px;">Nivel</th>
                                        <th style="min-width: 100px;">Lote</th>
                                        <th class="text-center" style="min-width: 70px;">Cantidad</th>
                                        <th class="text-center" style="min-width: 95px;">Fecha Caducidad</th>
                                        <th class="text-center" style="min-width: 85px;">Dias</th>
                                        <th class="text-center" style="min-width: 85px;">Obsolescencia (%)</th>
                                    </tr>
                                </thead>
                                <tbody id="tableCriticalBody">
                                    <tr>
                                        <td colspan="11" class="text-center text-muted py-4">
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

        {{-- Modal ATENCION --}}
        <div class="modal fade" id="modalAttention" tabindex="-1" aria-labelledby="modalAttentionLabel" aria-hidden="true">
            <div class="modal-dialog modal-xl modal-dialog-scrollable">
                <div class="modal-content border-0 shadow" style="border-left: 5px solid #ca8a04 !important;">
                    <div class="modal-header" style="background: linear-gradient(135deg, #fef9c3 0%, #fef08a 100%);">
                        <h5 class="modal-title fw-bold" id="modalAttentionLabel" style="color: #a16207;">
                            <i class="bi bi-clock-fill me-2"></i>Productos en Atencion
                        </h5>
                        <span class="badge bg-warning text-dark ms-2">{{ $attention->getTotalStock() ?? 0 }}</span>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                    </div>
                    {{-- Filtros --}}
                    <div class="px-3 pt-3 pb-0">
                        <div class="row g-2">
                            <div class="col-md-4">
                                <label class="form-label small fw-semibold text-muted">Almacen</label>
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
                                <label class="form-label small fw-semibold text-muted">Fecha Max. Caducidad</label>
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
                                        <th style="min-width: 120px;">Codigo</th>
                                        <th style="min-width: 120px;">Producto</th>
                                        <th style="min-width: 100px;">Almacen</th>
                                        <th style="min-width: 70px;">Rack</th>
                                        <th class="text-center" style="min-width: 60px;">Nivel</th>
                                        <th style="min-width: 100px;">Lote</th>
                                        <th class="text-center" style="min-width: 70px;">Cantidad</th>
                                        <th class="text-center" style="min-width: 95px;">Fecha Caducidad</th>
                                        <th class="text-center" style="min-width: 85px;">Dias</th>
                                        <th class="text-center" style="min-width: 85px;">Obsolescencia (%)</th>
                                    </tr>
                                </thead>
                                <tbody id="tableAttentionBody">
                                    <tr>
                                        <td colspan="11" class="text-center text-muted py-4">
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
                                <label class="form-label small fw-semibold text-muted">Almacen</label>
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
                                <label class="form-label small fw-semibold text-muted">Fecha Max. Caducidad</label>
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
                                        <th style="min-width: 120px;">Codigo</th>
                                        <th style="min-width: 120px;">Producto</th>
                                        <th style="min-width: 100px;">Almacen</th>
                                        <th style="min-width: 70px;">Rack</th>
                                        <th class="text-center" style="min-width: 60px;">Nivel</th>
                                        <th style="min-width: 100px;">Lote</th>
                                        <th class="text-center" style="min-width: 70px;">Cantidad</th>
                                        <th class="text-center" style="min-width: 95px;">Fecha Caducidad</th>
                                        <th class="text-center" style="min-width: 85px;">Dias</th>
                                        <th class="text-center" style="min-width: 85px;">Obsolescencia (%)</th>
                                    </tr>
                                </thead>
                                <tbody id="tableOkBody">
                                    <tr>
                                        <td colspan="11" class="text-center text-muted py-4">
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
                        <td colspan="11" class="text-center text-muted py-4">
                            <i class="bi bi-inbox-fill me-2"></i>No hay productos en esta categoria
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
                            ${item.remainingDays < 0 ? 'Vencido' : item.remainingDays + ' dias'}
                        </span>
                    </td>
                    <td class="text-center">${item.obsolescencia || '-'}</td>
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
                            <td colspan="11" class="text-center text-muted py-4">
                                <i class="bi bi-inbox-fill me-2"></i>No hay productos en esta categoria
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
