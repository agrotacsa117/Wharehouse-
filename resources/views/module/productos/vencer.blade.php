@extends('layouts.main')

@section('titulo', $titulo)

@section('contenido')
<main id="main" class="main bg-light py-4">
    <div class="pagetitle d-flex justify-content-between align-items-center mb-4">
        <h1 class="fw-bold text-danger"><i class="fa-solid fa-triangle-exclamation me-2"></i>Productos a Punto de Vencer o Vencidos</h1>
        {{-- <a href="{{ route('productos') }}" class="btn btn-outline-secondary btn-sm"><i class="fa-solid fa-arrow-left me-2"></i> Volver a Productos</a> --}}
    </div>
    <section class="section">
        <div class="row justify-content-center">
            <div class="col-lg-12">
                <div class="card shadow border-0 mb-4">
                    <div class="card-header bg-danger text-white d-flex align-items-center">
                        <h5 class="card-title mb-0"><i class="fa-solid fa-calendar-xmark me-2"></i>Listado de Productos Próximos a Caducar o Vencidos</h5>
                    </div>
                    <div class="card-body bg-white">
                        <p class="mb-4 text-muted fs-6">
                            Esta tabla muestra los productos que han caducado o están próximos a caducar (con 7 días o menos).
                        </p>
                        <div class="table-responsive rounded shadow-sm">
                        <table class="table table-hover align-middle bg-white rounded overflow-hidden datatable-export">
                                <thead class="table-danger">
                                    <tr>
                                        <th class="text-start">Proveedor</th>
                                        <th class="text-start">Clave</th>
                                        <th class="text-start">Nombre</th>
                                        <th class="text-center">Cantidad</th>
                                        <th class="text-start">Rack/Aduana</th>
                                        <th class="text-start">Bodega</th>
                                        <th class="text-center">Fecha de Ingreso</th>
                                        <th class="text-center">Fecha de Caducidad</th>
                                        <th class="text-center">Estado de Caducidad</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($items as $item)
                                        <?php
                                            $hoy = \Carbon\Carbon::today();
                                            $fechaCaducidad = \Carbon\Carbon::parse($item->fecha_caducidad);
                                            $dias = $hoy->diffInDays($fechaCaducidad, false);
                                            $rowClass = '';
                                            $badgeClass = '';
                                            $textoEstado = '';
                                            if ($dias < 0) {
                                                $rowClass = 'table-danger';
                                                $badgeClass = 'bg-dark';
                                                $textoEstado = 'Vencido hace ' . abs($dias) . ' días';
                                            } elseif ($dias == 0) {
                                                $rowClass = 'table-danger';
                                                $badgeClass = 'bg-danger';
                                                $textoEstado = 'Vence hoy';
                                            } elseif ($dias <= 7) {
                                                $rowClass = 'table-warning';
                                                $badgeClass = 'bg-danger';
                                                $textoEstado = 'Vence en ' . $dias . ' día' . ($dias != 1 ? 's' : '');
                                            } elseif ($dias <= 14) {
                                                $rowClass = 'table-warning';
                                                $badgeClass = 'bg-warning text-dark';
                                                $textoEstado = 'Vence en ' . $dias . ' días';
                                            } else {
                                                $rowClass = '';
                                                $badgeClass = 'bg-success';
                                                $textoEstado = 'Vence en ' . $dias . ' días';
                                            }
                                        ?>
                                        <tr class="{{ $rowClass }} align-middle">
                                            <td class="text-start fw-bold">{{ $item->proveedor->nombre ?? 'N/A' }}</td>
                                            <td class="text-start">{{ $item->clave_categorias }}</td>
                                            <td class="text-start">{{ $item->nombre_categorias }}</td>
                                            <td class="text-center text-danger fw-bold">{{ $item->cantidad }}</td>
                                            <td class="text-start">{{ $item->colocado }}</td>
                                            <td class="text-start">{{ ucfirst(str_replace('_', ' ', $item->rol)) }}</td>
                                            <td class="text-center">{{ \Carbon\Carbon::parse($item->fecha_ingreso)->format('d/m/Y') }}</td>
                                            <td class="text-center">{{ $fechaCaducidad->format('d/m/Y') }}</td>
                                            <td class="text-center">
                                                <span class="badge {{ $badgeClass }} d-block py-2 fs-6 shadow-sm">
                                                    <i class="fa-solid fa-triangle-exclamation me-1"></i>{{ $textoEstado }}
                                                </span>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="9" class="text-center text-muted py-4">
                                                <i class="fa-solid fa-calendar-check me-2"></i> No se encontraron productos próximos a caducar o vencidos.
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</main>
@endsection
