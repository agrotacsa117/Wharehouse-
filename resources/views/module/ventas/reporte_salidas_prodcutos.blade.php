@extends('layouts.main')

@section('titulo', $titulo)

@section('contenido')
<main id="main" class="main">
    <div class="pagetitle">
        {{-- Icono para el título de la página --}}
        <h1><i class="bi bi-file-earmark-text me-2"></i>Reporte de Salidas de Productos</h1>
        {{-- Breadcrumb (opcional, si tu layout lo maneja) --}}
        {{-- <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="/">Inicio</a></li>
                <li class="breadcrumb-item active">Reporte de Salidas</li>
            </ol>
        </nav> --}}
    </div><!-- End Page Title -->

    <section class="section">
        <div class="row">
            <div class="col-lg-12">
                <div class="card shadow-sm"> {{-- Sombra sutil para la card principal --}}
                    <div class="card-header bg-light"> {{-- Fondo ligero para el header de la card --}}
                        <div class="d-flex justify-content-between align-items-center">
                            <h5 class="card-title mb-0"><i class="bi bi-table me-2"></i>Detalle de Salidas Registradas</h5>
                            <div class="text-end">  
                                {{-- Formato de fecha más legible y completo --}}
                            </div>
                        </div>
                    </div>
                    <div class="card-body pt-3"> {{-- Padding top para separar del header --}}
                        <p class="mb-3">
                            Este reporte muestra un listado detallado de todas las salidas de productos, incluyendo información relevante como fechas de caducidad y usuarios responsables.
                        </p>
                        {{-- No es necesario el <hr> si ya tenemos un card-header bien definido --}}

                        <div class="d-flex justify-content-end mb-2">
                            <button id="export-excel" class="btn btn-success btn-sm me-2"><i class="fa-solid fa-file-excel me-1"></i> Excel</button>
                            {{-- <button id="export-pdf" class="btn btn-danger btn-sm"><i class="fa-solid fa-file-pdf me-1"></i> PDF</button> --}}
                        </div>

                        <form method="GET" class="mb-4 p-3 rounded bg-light border" action="{{ route('reporte.salidas') }}">
                            <div class="row g-3 align-items-end">
                                <div class="col-md-3">
                                    <label for="fecha_inicio" class="form-label mb-1">Desde:</label>
                                    <input type="date" name="fecha_inicio" id="fecha_inicio" class="form-control form-control-sm" value="{{ request('fecha_inicio') }}">
                                </div>
                                <div class="col-md-3">
                                    <label for="fecha_fin" class="form-label mb-1">Hasta:</label>
                                    <input type="date" name="fecha_fin" id="fecha_fin" class="form-control form-control-sm" value="{{ request('fecha_fin') }}">
                                </div>
                                <div class="col-md-3">
                                    <label for="usuario" class="form-label mb-1">Usuario:</label>
                                    <input type="text" name="usuario" id="usuario" class="form-control form-control-sm" value="{{ request('usuario') }}" placeholder="Nombre o usuario">
                                </div>
                                <div class="col-md-3 d-flex align-items-end">
                                    <button type="submit" class="btn btn-primary w-100"><i class="fa-solid fa-filter me-2"></i>Filtrar</button>
                                </div>
                            </div>
                        </form>

                        <div class="table-responsive"> {{-- Para que la tabla sea responsive --}}
                            <table id="tabla-salidas" class="table table-bordered table-striped table-hover datatable"> {{-- Clases para mejorar la apariencia de la tabla --}}
                                <thead class="table-light"> {{-- Encabezado con fondo claro --}}
                                    <tr>
                                        <th class="text-center">#</th>
                                        <th class="text-center">Fecha Salida</th>
                                        <th class="text-center">Usuario</th>
                                        <th class="text-center">Bodega destino</th>
                                        <th class="text-center">Comunidad/Institución</th>
                                        <th class="text-center">Ticket PDF</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php $num = 1; @endphp
                                    @forelse($tickets as $ticket_pdf => $grupo)
                                        @php
                                            $primera = $grupo->first();
                                        @endphp
                                        <tr class="align-middle">
                                            <td class="text-center">{{ $num++ }}</td>
                                            <td class="text-center small">
                                                {{-- Uso de Carbon para asegurar formato y zona horaria --}}
                                                {{ \Carbon\Carbon::parse($primera->fecha_salida)->setTimezone('America/Mexico_City')->isoFormat('DD/MM/YY') }}
                                            </td>
                                            <td>{{ $primera->nombre_usuario }}</td>
                                            <td>{{ $primera->bodega_destino ?: '-' }}</td>
                                            <td>{{ $primera->donado_a ?: '-' }}</td>
                                            <td class="text-center">
                                                @if($ticket_pdf)
                                                    <a href="{{ route('salidas.ticket_grupal', ['ticket_pdf' => $ticket_pdf]) }}" class="btn btn-outline-primary btn-sm">
                                                        <i class="bi bi-file-earmark-pdf"></i> Ticket
                                                    </a>
                                                @else
                                                    <span class="text-muted">-</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="6" class="text-center text-muted fst-italic py-3">
                                                <i class="bi bi-exclamation-circle me-2"></i>No hay salidas registradas para mostrar en este reporte.
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                        {{-- Aquí podrías agregar botones de exportación si es necesario --}}
                        {{-- <div class="text-end mt-3">
                            <button class="btn btn-primary"><i class="bi bi-download me-2"></i>Exportar PDF</button>
                            <button class="btn btn-success"><i class="bi bi-file-earmark-excel me-2"></i>Exportar Excel</button>
                        </div> --}}
                    </div>
                </div>
            </div>
        </div>
    </section>
</main>
@endsection

@push('styles')
{{-- Estilos adicionales si fueran necesarios --}}
<style>
    .table code {
        color: #0d6efd; /* Color primario de Bootstrap para las claves */
        background-color: #e9ecef; /* Fondo sutil para `code` */
        padding: .2em .4em;
        border-radius: .25rem;
    }
    .badge.bg-warning.text-dark { /* Asegurar buen contraste para warning */
        color: #000 !important;
    }
</style>
@endpush

@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.7.0/jspdf.plugin.autotable.min.js"></script>
<script>
    // Exportar a Excel
    document.getElementById('export-excel').addEventListener('click', function() {
        var wb = XLSX.utils.table_to_book(document.getElementById('tabla-salidas'), {sheet:"Salidas"});
        XLSX.writeFile(wb, 'reporte_salidas.xlsx');
    });
    // Redirección automática al terminar/cancelar impresión del ticket desde el reporte
    if (window.opener && window.location.pathname.includes('salidas/ticket-grupal')) {
        window.onload = function() {
            window.print();
        };
        window.onafterprint = function() {
            window.close();
            if (window.opener) {
                window.opener.location.href = "{{ route('reporte.salidas') }}";
            }
        };
    }
</script>
@endpush