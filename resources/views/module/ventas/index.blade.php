@extends('layouts.main')

@section('titulo', $titulo)

@section('contenido')
<main id="main" class="main bg-light py-4">
  <div class="pagetitle mb-4">
    <h1 class="fw-bold text-primary"><i class="bi bi-truck me-2"></i>Salidas de Productos</h1>
  </div>
  <section class="section">
    <div class="row justify-content-center">
      <div class="col-lg-12">
        <div class="card shadow border-0 mb-4">
          <div class="card-body bg-white">
            <h5 class="card-title mb-2 text-primary"><i class="bi bi-plus-circle me-2"></i>Registrar Nueva Salida de Productos</h5>
            <p class="card-subtitle mb-3 text-muted fs-6">
              Selecciona productos de la lista de disponibles para agregarlos al carrito de salidas.
            </p>
            <!-- Mensajes flash con iconos -->
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show d-flex align-items-center" role="alert">
                    <i class="bi bi-check-circle-fill me-2"></i>
                    <div>{{ session('success') }}</div>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show d-flex align-items-center" role="alert">
                    <i class="bi bi-exclamation-triangle-fill me-2"></i>
                    <div>{{ session('error') }}</div>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif
            <div class="card shadow-sm mb-4 border-0">
                <div class="card-header bg-primary text-white">
                    <h5 class="card-title mb-0"><i class="bi bi-box-seam me-2"></i>Productos Disponibles</h5>
                </div>
                <div class="card-body pt-3">
                    <div class="table-responsive rounded shadow-sm">
                        <table class="table table-hover align-middle bg-white rounded overflow-hidden datatable-export">
                            <thead class="table-light">
                                <tr>
                                    <th class="text-center">Clave</th>
                                    <th class="text-start">Nombre</th>
                                    <th class="text-center">Bodega</th>
                                    <th class="text-center">Cantidad Disp.</th>
                                    <th class="text-center">Fecha Caducidad</th>
                                    <th class="text-center">Rack/Aduana</th>
                                    <th class="text-center" style="min-width: 280px;">Registrar Salida</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($items as $item)
                                    <tr class="align-middle">
                                        <td class="text-center">{{ $item->clave_categorias }}</td>
                                        <td>{{ $item->nombre_categorias }}</td>
                                        <td class="text-center">{{ ucfirst(str_replace('_', ' ', $item->rol_producto)) }}</td>
                                        <td class="text-center fw-bold text-success">{{ $item->cantidad }}</td>
                                        @php
                                            $fechaCaducidad = \Carbon\Carbon::parse($item->fecha_caducidad);
                                            $hoy = now();
                                            $diasRestantes = $hoy->diffInDays($fechaCaducidad, false);
                                            $color = '';
                                            $textoColor = 'white';
                                            if ($fechaCaducidad->isPast()) {
                                                $color = '#6c757d';
                                                $etiqueta = 'CADUCADO';
                                            } elseif ($diasRestantes <= 0) {
                                                $color = '#dc3545';
                                                $etiqueta = 'CADUCA HOY';
                                            } elseif ($diasRestantes <= 7) {
                                                $color = '#dc3545';
                                                $etiqueta = intval($diasRestantes) . ' días';
                                            } elseif ($diasRestantes <= 14) {
                                                $color = '#ffc107';
                                                $textoColor = 'black';
                                                $etiqueta = intval($diasRestantes) . ' días';
                                            } else {
                                                $color = '#198754';
                                                $etiqueta = intval($diasRestantes) . ' días';
                                            }
                                        @endphp
                                        <td class="text-center">
                                            <span class="badge shadow-sm" style="background-color: {{ $color }}; color: {{ $textoColor }}; font-size: 0.95em;">
                                                {{ $fechaCaducidad->format('d/m/Y') }} ({{ $etiqueta }})
                                            </span>
                                        </td>
                                        <td class="text-center">{{ $item->colocado ?: '-' }}</td>
                                        <td>
                                             <form action="{{ route('salida-productos.agregar', $item->id) }}" method="POST" class="d-inline">
                                                 @csrf
                                                  <div class="input-group input-group-sm">
                                                      <input type="number" name="cantidad" min="1" max="{{ $item->cantidad }}"
                                                             value="1" class="form-control" style="max-width: 70px;" aria-label="Cantidad">
                                                      <input type="text" name="observaciones" class="form-control"
                                                             placeholder="Observaciones (opcional)" style="min-width: 120px;" aria-label="Observaciones">
                                                      <button type="submit" class="btn btn-success">
                                                          <i class="bi bi-plus-lg"></i>
                                                      </button>
                                                  </div>
                                             </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center text-muted fst-italic py-3">
                                            No hay productos disponibles para mostrar.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            @if(count($salidas) > 0)
            <div class="card shadow-sm mt-4 border-0">
                <div class="card-header bg-info text-white">
                    <h5 class="card-title mb-0"><i class="bi bi-cart3 me-2"></i>Carrito de Salidas</h5>
                </div>
                <div class="card-body pt-3">
                    <form id="formBodegaDestino" action="{{ route('salida-productos.finalizar') }}" method="POST" class="mb-3">
                        @csrf
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="bodega_destino" class="form-label fw-semibold">Bodega destino:</label>
                                <select name="bodega_destino" id="bodega_destino" class="form-select" required>
                                    <option value="" disabled selected>Seleccione una bodega</option>
                                    <option value="ninguna">Ninguna</option>
                                    @php
                                        $userRol = strtolower(auth()->user()->rol);
                                        $bodegasSalida = collect($salidas)->pluck('rol')->unique()->map(function($b){ return strtolower(trim($b)); });
                                    @endphp
                                    @foreach(\App\Models\Bodega::listaBodegas() as $bodega)
                                        @php $bodegaLower = strtolower(trim($bodega)); @endphp
                                        @if(!in_array($userRol, ['tapachula', 'bodega_dorado']) || !$bodegasSalida->contains($bodegaLower))
                                            <option value="{{ $bodega }}">{{ ucfirst($bodega) }}</option>
                                        @endif
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label for="donado_a" class="form-label fw-semibold">Comunidad/Institución (opcional):</label>
                                <input type="text" name="donado_a" id="donado_a" class="form-control" maxlength="100" placeholder="Nombre de la persona o asociación">
                            </div>
                        </div>
                        <div class="mt-3 d-flex flex-column flex-md-row justify-content-end gap-2">
                            <button type="submit" class="btn btn-success btn-lg w-100">
                                <i class="bi bi-cart-check-fill me-2"></i> Procesar Salidas
                            </button>
                        </div>
                    </form>
                    <div class="table-responsive rounded shadow-sm mt-3">
                        <table class="table table-hover table-sm align-middle bg-white rounded overflow-hidden">
                            <thead class="table-info text-primary">
                                <tr>
                                    <th class="text-center">Clave</th>
                                    <th class="text-start">Nombre</th>
                                    <th class="text-center">Bodega</th>
                                    <th class="text-center" style="width: 120px;">Cantidad</th>
                                    <th class="text-center">Stock Actual</th>
                                    @if(!isset($es_comprobante) || !$es_comprobante)
                                    <th class="text-center">Acción</th>
                                    @endif
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($salidas as $id => $salida)
                                    <tr class="align-middle">
                                        <td class="text-center"><span class="badge bg-secondary">{{ $salida['clave'] }}</span></td>
                                        <td class="fw-semibold">{{ $salida['nombre'] }}</td>
                                        <td class="text-center">{{ ucfirst(str_replace('_', ' ', $salida['rol'])) }}</td>
                                        <td class="text-center">
                                            <input type="number" form="formActualizarCantidades" name="cantidades[{{ $id }}]" min="1" max="{{ $salida['stock'] + $salida['cantidad'] }}" value="{{ $salida['cantidad'] }}" class="form-control form-control-sm text-center d-inline-block @if($salida['cantidad'] > $salida['stock']) border-danger @endif" style="width:80px;">
                                            @if($salida['cantidad'] > $salida['stock'])
                                                <span class="badge bg-danger mt-1">Excede stock</span>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            <span class="badge bg-light text-dark">{{ $salida['stock'] }}</span>
                                        </td>
                                        @if(!isset($es_comprobante) || !$es_comprobante)
                                        <td class="text-center">
                                            <form action="{{ route('salida-productos.eliminar', $id) }}" method="POST" class="d-inline eliminar-producto-form">
                                                @csrf
                                                <button type="submit" class="btn btn-outline-danger btn-sm" title="Eliminar del carrito">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </form>
                                        </td>
                                        @endif
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        <form id="formActualizarCantidades" action="{{ route('salida-productos.actualizarGlobal') }}" method="POST" class="mt-2 d-flex justify-content-end gap-2">
                            @csrf
                            <button type="submit" class="btn btn-warning">
                                <i class="bi bi-arrow-repeat me-1"></i> Actualizar cantidades
                            </button>
                        </form>
                    </div>
                </div>
            </div>
            @else
            <div class="alert alert-info mt-4 d-flex align-items-center" role="alert">
                <i class="bi bi-info-circle-fill me-2"></i>
                <div>
                    El carrito de salidas está vacío. Agrega productos de la lista superior.
                </div>
            </div>
            @endif
          </div>
        </div>
      </div>
    </div>
  </section>
</main>
@endsection

@push('scripts')
<script>
  // Inicializar DataTables si es necesario (ya tienes la clase 'datatable')
  // $(document).ready(function() {
  //     $('.datatable').DataTable();
  // });

  // Pequeña mejora para el input de observaciones:
  document.querySelectorAll('input[name="cantidad"]').forEach(input => {
    input.addEventListener('change', function() {
      // Podrías intentar focusear el input de observaciones si es relevante
      // const obsInput = this.closest('.input-group').querySelector('input[name="observaciones"]');
      // if (obsInput) obsInput.focus();
    });
  });

  // Validación extra para admin: no permitir procesar si bodega destino coincide con alguna de salida
  document.getElementById('formBodegaDestino').addEventListener('submit', function(e) {
    var userRol = '{{ strtolower(auth()->user()->rol) }}';
    if(userRol === 'admin') {
      var bodegaDestino = document.getElementById('bodega_destino').value.trim().toLowerCase();
      var bodegasSalida = [
        @foreach($salidas as $salida)
          '{{ strtolower(trim($salida["rol"])) }}',
        @endforeach
      ];
      if(bodegasSalida.includes(bodegaDestino)) {
        // Mostrar alerta visual Bootstrap
        var alerta = document.getElementById('alerta-bodega');
        if(!alerta) {
          alerta = document.createElement('div');
          alerta.id = 'alerta-bodega';
          alerta.className = 'alert alert-danger alert-dismissible fade show mt-2';
          alerta.innerHTML = '<i class="bi bi-exclamation-triangle-fill me-2"></i> No puedes procesar la salida: la bodega de destino y la bodega de salida coinciden.' +
            '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>';
          var form = document.getElementById('formBodegaDestino');
          form.parentNode.insertBefore(alerta, form);
        }
        e.preventDefault();
        return false;
      }
    }
  });
</script>
@endpush