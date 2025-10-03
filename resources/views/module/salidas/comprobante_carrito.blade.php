@extends('layouts.main')

@section('titulo', 'Ticket de Salida de Almacén')

@section('contenido')
<main id="main" class="main">
    <!--<div class="logo-impresion text-center">
        <img src="{{ asset('imagen/NiceAdmin/img/logo.png') }}" alt="Logo" class="logo-impresion">
    </div>-->
    <div class="pagetitle">
        <h1><i class="bi bi-receipt me-2"></i>Ticket de Salida de Almacén</h1>
    </div>
    <section class="section">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <div class="card shadow-sm">
                    <div class="card-header bg-light d-flex justify-content-between align-items-center">
                        <span class="fw-bold">Usuario: <span class="text-primary">{{ $usuario->name }}</span></span>
                    </div>
                    <div class="card-body">
                        <div class="mb-3 row justify-content-start align-items-center">
                            <div class="col-auto ps-4">
                                <div class="d-flex align-items-center mt-1">
                                    <label class="col-form-label mb-0 pe-2 fw-semibold">Fecha de salida:</label>
                                    <span>
                                        @php
                                            $fechaSalida = isset($carrito) && count($carrito) > 0 && isset($carrito[0]['created_at']) ? $carrito[0]['created_at'] : (isset($carrito[0]['fecha_salida']) ? $carrito[0]['fecha_salida'] : null);
                                        @endphp
                                        {{ $fechaSalida ? \Carbon\Carbon::parse($fechaSalida)->format('d/m/Y') : now()->format('d/m/Y') }}
                                    </span>
                                </div>
                            </div>
                            <div class="col-auto ps-5">
                                <div class="d-flex align-items-center">
                                    <label class="col-form-label mb-0 pe-2 fw-semibold">Bodega de salida:</label>
                                    @php
                                        // Si el usuario es bodega tapachula o bodega_dorado, mostrar su rol
                                        $rolUsuario = strtolower($usuario->rol);
                                        $bodegas = collect($carrito)->pluck('rol')->unique()->filter()->map(function($b){ return ucfirst(str_replace('_', ' ', $b)); })->implode(', ');
                                    @endphp
                                    <span>
                                        @if(in_array($rolUsuario, ['bodega tapachula', 'bodega_dorado', 'bodega tapachula', 'bodega_dorado']))
                                            {{ ucfirst(str_replace('_', ' ', $rolUsuario)) }}
                                        @else
                                            {{ $bodegas ?: '-' }}
                                        @endif
                                    </span>
                                </div>
                            </div>
                            <div class="col-auto ps-5">
                                <div class="d-flex align-items-center">
                                    <label class="col-form-label mb-0 pe-2 fw-semibold">Bodega destino:</label>
                                    <span>{{ !empty($bodegaDestino) ? ucfirst($bodegaDestino) : 'No especificada' }}</span>
                                </div>
                                <div class="d-flex align-items-center mt-1">
                                    <label class="col-form-label mb-0 pe-2 fw-semibold">Comunidad/Institución:</label>
                                    <span>{{ !empty($donadoA) ? $donadoA : '-' }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped">
                                <thead class="table-light">
                                    <tr>
                                        <th>#</th>
                                        <th>Clave</th>
                                        <th>Categoría</th>
                                        <th>Cantidad</th>
                                        <th>Rack/Aduana</th>
                                    </tr>
                                </thead>
                                @php
                                    // Usar la clave real del array como id para eliminar
                                @endphp
                                <tbody>
                                    @forelse($carrito as $id => $item)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td><code>{{ $item['clave'] }}</code></td>
                                        <td>{{ $item['nombre'] }}</td>
                                        <td class="fw-bold">{{ $item['cantidad'] }}</td>
                                        <td>
                                            {{ $item['colocado'] ?? '-' }}
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="5" class="text-center text-muted fst-italic py-3">
                                            <i class="bi bi-exclamation-circle me-2"></i>No hay productos en el carrito de salida.
                                        </td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                        <div class="row mt-5">
                            <div class="col-4 text-center">
                                <span class="fw-bold">Autorizo</span>
                                <div style="height:60px;"></div>
                                <hr class="my-1" style="border-top:1.5px solid #000; width:80%; margin:auto;">
                            </div>
                            <div class="col-4 text-center">
                                <span class="fw-bold">Recibido</span>
                                <div style="height:60px;"></div>
                                <hr class="my-1" style="border-top:1.5px solid #000; width:80%; margin:auto;">
                            </div>
                            <div class="col-4 text-center">
                                <span class="fw-bold">Autorizado</span>
                                <div style="height:60px;"></div>
                                <hr class="my-1" style="border-top:1.5px solid #000; width:80%; margin:auto;">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</main>
@endsection

@push('styles')
<style>
@media print {
    body * { visibility: hidden !important; }
    .main, .main * { visibility: visible !important; }
    .main { position: absolute; left: 0; top: 0; width: 100%; }
    .btn, .card-header, .card-header * { display: none !important; visibility: hidden !important; }
    /* .pagetitle, .pagetitle * { display: none !important; visibility: hidden !important; } */
    /* .logo-impresion { display: block !important; visibility: visible !important; margin-bottom: 10px; } */
    @page {
        size: auto;
        margin-bottom: 2cm;
    }
}
/* .logo-impresion {
    display: block;
    margin: 0 auto 10px auto;
    max-width: 180px;
} */
</style>
@endpush

@push('scripts')
<script>
    // Imprimir y redirigir según el origen
    window.onload = function() {
        window.print();
    };
    window.onafterprint = function() {
        window.location.href = "{{ route('reporte.salidas') }}";
    };
    // Numeración de páginas para impresión PDF
    document.addEventListener('DOMContentLoaded', function() {
        if (typeof window.print === 'function') {
            // Solo navegadores modernos soportan esto en impresión
            if (window.matchMedia('print').matches) {
                document.querySelectorAll('.pageNumber').forEach(function(el) {
                    el.textContent = '';
                });
                document.querySelectorAll('.totalPages').forEach(function(el) {
                    el.textContent = '';
                });
            }
        }
    });
</script>
@endpush
