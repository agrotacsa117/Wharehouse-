@extends('layouts.main')

@section('titulo', $titulo)

@section('contenido')
<main id="main" class="main bg-light py-4">
  <div class="pagetitle d-flex justify-content-between align-items-center mb-4">
    <h1 class="fw-bold text-primary"><i class="fa-solid fa-clipboard-list me-2"></i>Reporte de Productos</h1>
    {{-- <a href="{{ route('productos') }}" class="btn btn-outline-secondary btn-sm"><i class="fa-solid fa-arrow-left me-2"></i> Volver</a> --}}
  </div>

  <section class="section">
    <div class="row justify-content-center">
      <div class="col-lg-12">
        <div class="card shadow border-0 mb-4">
          <div class="card-header bg-primary text-white d-flex align-items-center">
            <h5 class="card-title mb-0"><i class="fa-solid fa-boxes-stacked me-2"></i>Listado General de Productos</h5>
          </div>
          <div class="card-body bg-white">
            <p class="mb-4 text-muted fs-6">
              Visualiza el inventario completo de productos, incluyendo detalles y estado de caducidad.
            </p>
            <form method="GET" class="mb-4 p-3 rounded bg-light border" action="{{ route('reportes_productos') }}">
              <input type="hidden" name="filtro" value="listado">
              <div class="row g-3 align-items-end">
                <div class="col-md-3">
                  <label for="fecha_inicio_listado" class="form-label mb-1">Desde:</label>
                  <input type="date" name="fecha_inicio_listado" id="fecha_inicio_listado" class="form-control form-control-sm" value="{{ request('fecha_inicio_listado') }}">
                </div>
                <div class="col-md-3">
                  <label for="fecha_fin_listado" class="form-label mb-1">Hasta:</label>
                  <input type="date" name="fecha_fin_listado" id="fecha_fin_listado" class="form-control form-control-sm" value="{{ request('fecha_fin_listado') }}">
                </div>
                <div class="col-md-3">
                  <label for="bodega" class="form-label mb-1">Bodega:</label>
                  <select name="bodega" id="bodega" class="form-select form-select-sm">
                    <option value="">Todas</option>
                    <option value="tapachula" {{ request('bodega') == 'tapachula' ? 'selected' : '' }}>Tapachula</option>
                    <option value="bodega_dorado" {{ request('bodega') == 'bodega_dorado' ? 'selected' : '' }}>Bodega Dorado</option>
                  </select>
                </div>
                <div class="col-md-3 d-flex align-items-end">
                  <button type="submit" class="btn btn-primary w-100"><i class="fa-solid fa-filter me-2"></i>Filtrar</button>
                </div>
              </div>
            </form>
            <div class="table-responsive rounded shadow-sm">
              <table class="table table-bordered table-hover align-middle bg-white rounded overflow-hidden datatable-export">
                <thead class="table-primary">
                  <tr>
                    <th class="text-start">Proveedor</th>
                    <th class="text-start">Clave</th>
                    <th class="text-start">Nombre</th>
                    <th class="text-center">Cantidad</th>
                    <th class="text-start">Rack/Aduana</th>
                    <th class="text-start">Bodega</th>
                    <th class="text-center">Fecha de Ingreso</th>
                    <th class="text-center">Fecha de Caducidad</th>
                    <th class="text-center">Precio Unitario</th>
                    <th class="text-center">Precio Total</th>
                  </tr>
                </thead>
                <tbody>
                  @forelse ($items as $item)
                    @if ((!request('bodega') || $item->rol === request('bodega')) && $item->cantidad > 0)
                    <tr class="@if($loop->even) bg-light @endif">
                      <td class="text-start">{{ $item->nombre_proveedores}}</td>
                      <td class="text-start">{{ $item->clave_categorias }}</td>
                      <td class="text-start">{{ $item->nombre_categorias }}</td>
                      <td class="text-center fw-bold text-success">
                        {{ $item->cantidad }}
                      </td>
                      <td class="text-start">{{ $item->colocado }}</td>
                      <td class="text-start">{{ ucfirst(str_replace('_', ' ', $item->rol)) }}</td>
                      <td class="text-center">{{ \Carbon\Carbon::parse($item->fecha_ingreso)->format('d/m/Y') }}</td>
                      <td class="text-center">{{ \Carbon\Carbon::parse($item->fecha_caducidad)->format('d/m/Y') }}</td>
                      <td class="text-center text-primary">${{ number_format($item->precio, 2) }}</td>
                      <td class="text-center text-primary">${{ number_format($item->cantidad * $item->precio, 2) }}</td>
                    </tr>
                    @endif
                  @empty
                    <tr>
                      <td colspan="10" class="text-center text-muted py-4">
                        <i class="fa-solid fa-box-open me-2"></i> No se encontraron productos registrados.
                      </td>
                    </tr>
                  @endforelse
                </tbody>
              </table>
            </div>
          </div>
        </div>
        <div class="card shadow border-0 mt-5">
          <div class="card-header bg-info text-white d-flex align-items-center">
            <h5 class="card-title mb-0"><i class="fa-solid fa-chart-pie me-2"></i>Resumen de Cantidades por Bodega y Categoría</h5>
          </div>
          <div class="card-body bg-white">
            <p class="mb-4 text-muted fs-6">
              Visualiza el total de productos por artículo, distribuidos entre las bodegas.
            </p>
            <div class="d-flex justify-content-end mb-2">
              <button id="export-excel" class="btn btn-success btn-sm me-2"><i class="fa-solid fa-file-excel me-1"></i> Excel</button>
              <button id="export-pdf" class="btn btn-danger btn-sm"><i class="fa-solid fa-file-pdf me-1"></i> PDF</button>
            </div>
            <div class="table-responsive rounded shadow-sm">
              <table id="tabla-resumen" class="table table-hover align-middle bg-white rounded overflow-hidden">
                <thead class="table-info">
                  <tr>
                    <th class="text-start">Clave Artículo</th>
                    <th class="text-start">Nombre Artículo</th>
                    <th class="text-center">Total Bodega Tapachula</th>
                    <th class="text-center">Precio Total Tapachula</th>
                    <th class="text-center">Total Bodega Dorado</th>
                    <th class="text-center">Precio Total Dorado</th>
                    <th class="text-center">Total General</th>
                    <th class="text-center">Precio Total General</th>
                  </tr>
                </thead>
                <tbody>
                  @php
                    $rowIndex = 0;
                    foreach ($todasCategorias as $clave => $nombre) {
                        $productos = $itemsResumen->where('clave_categorias', $clave);
                        $totalGeneral = 0;
                        $totalTapachula = 0;
                        $totalDorado = 0;
                        $precioTotalTapachula = 0;
                        $precioTotalDorado = 0;
                        foreach ($productos as $prod) {
                            $suma = $prod->cantidad;
                            $totalGeneral += $suma;
                            if ($prod->rol === 'tapachula') {
                                $totalTapachula += $suma;
                                $precioTotalTapachula += $suma * $prod->precio;
                            } elseif ($prod->rol === 'bodega_dorado') {
                                $totalDorado += $suma;
                                $precioTotalDorado += $suma * $prod->precio;
                            }
                        }
                        $precioTotalGeneral = $precioTotalTapachula + $precioTotalDorado;
                  @endphp
                  <tr class="@if($rowIndex % 2 === 1) bg-light @endif">
                    <td class="text-start">{{ $clave }}</td>
                    <td class="text-start">{{ $nombre }}</td>
                    <td class="text-center">{{ $totalTapachula }}</td>
                    <td class="text-center text-primary">${{ number_format($precioTotalTapachula, 2) }}</td>
                    <td class="text-center">{{ $totalDorado }}</td>
                    <td class="text-center text-primary">${{ number_format($precioTotalDorado, 2) }}</td>
                    <td class="text-center fw-bold text-success">{{ $totalGeneral }}</td>
                    <td class="text-center text-primary fw-bold">${{ number_format($precioTotalGeneral, 2) }}</td>
                  </tr>
                  @php
                    $rowIndex++;
                    }
                  @endphp
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
</main>
@endsection

@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.7.0/jspdf.plugin.autotable.min.js"></script>
<script>
// Exportar a Excel
  document.getElementById('export-excel').addEventListener('click', function() {
    var wb = XLSX.utils.table_to_book(document.getElementById('tabla-resumen'), {sheet:"Resumen"});
    XLSX.writeFile(wb, 'resumen_productos.xlsx');
  });
// Exportar a PDF
  document.getElementById('export-pdf').addEventListener('click', function() {
    var { jsPDF } = window.jspdf;
    var doc = new jsPDF();
    doc.autoTable({ html: '#tabla-resumen', theme: 'grid', headStyles: { fillColor: [41,128,185] } });
    doc.save('resumen_productos.pdf');
  });
</script>
@endpush