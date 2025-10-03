@extends('layouts.main')

@section('titulo', 'Comprobante de Salida de Almacén')

@section('contenido')
<main id="main" class="main">
    <div class="pagetitle">
        <h1><i class="bi bi-receipt me-2"></i>Comprobante de Salida de Almacén</h1>
    </div>
    <section class="section">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="card shadow-sm">
                    <div class="card-header bg-light d-flex justify-content-between align-items-center">
                        <span class="fw-bold">Folio: <span class="text-primary">{{ $salida->id }}</span></span>
                        <button onclick="window.print()" class="btn btn-outline-primary btn-sm"><i class="bi bi-printer me-1"></i> Imprimir</button>
                    </div>
                    <div class="card-body">
                        <div class="mb-3 row">
                            <label class="col-sm-4 col-form-label">Fecha de Salida:</label>
                            <div class="col-sm-8">
                                {{ \Carbon\Carbon::parse($salida->fecha_salida)->format('d/m/Y H:i') }}
                            </div>
                        </div>
                        <div class="mb-3 row">
                            <label class="col-sm-4 col-form-label">Usuario Responsable:</label>
                            <div class="col-sm-8">
                                {{ $salida->nombre_usuario }}
                            </div>
                        </div>
                        <div class="mb-3 row">
                            <label class="col-sm-4 col-form-label">Clave Categoría:</label>
                            <div class="col-sm-8">
                                <code>{{ $salida->clave_categorias }}</code>
                            </div>
                        </div>
                        <div class="mb-3 row">
                            <label class="col-sm-4 col-form-label">Nombre Categoría:</label>
                            <div class="col-sm-8">
                                {{ $salida->nombre_categoria }}
                            </div>
                        </div>
                        <div class="mb-3 row">
                            <label class="col-sm-4 col-form-label">Cantidad:</label>
                            <div class="col-sm-8 fw-bold">
                                {{ $salida->cantidad }}
                            </div>
                        </div>
                        <div class="mb-3 row">
                            <label class="col-sm-4 col-form-label">Fecha de Caducidad:</label>
                            <div class="col-sm-8">
                                @if($salida->fecha_caducidad)
                                    {{ \Carbon\Carbon::parse($salida->fecha_caducidad)->format('d/m/Y') }}
                                @else
                                    <span class="text-muted fst-italic">Sin fecha</span>
                                @endif
                            </div>
                        </div>
                        <div class="mb-3 row">
                            <label class="col-sm-4 col-form-label">Colocado:</label>
                            <div class="col-sm-8">
                                {{ $salida->colocado ?: '-' }}
                            </div>
                        </div>
                        <div class="mb-3 row">
                            <label class="col-sm-4 col-form-label">Observaciones:</label>
                            <div class="col-sm-8">
                                {{ $salida->observaciones ?: '-' }}
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
    body * { visibility: hidden; }
    .main, .main * { visibility: visible; }
    .main { position: absolute; left: 0; top: 0; width: 100%; }
    .btn, .card-header button { display: none !important; }
}
</style>
@endpush
