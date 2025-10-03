@extends('layouts.main')

@section('titulo', $titulo)

@section('contenido')
<main id="main" class="main">
  <div class="pagetitle d-flex justify-content-between align-items-center"> {{-- Añadido flexbox para alinear título y posibles elementos --}}
    <h1>Editar Producto</h1>
    {{-- Opcional: Un botón para volver atrás rápidamente en el encabezado de la página --}}
    <a href="{{ route("productos") }}" class="btn btn-secondary btn-sm">
      <i class="fa-solid fa-arrow-left me-2"></i> Volver a Productos
    </a>
  </div><!-- End Page Title -->

  <section class="section">
    <div class="row">
      <div class="col-lg-8 mx-auto"> {{-- Columna más pequeña y centrada para formularios --}}
        <div class="card">
          <div class="card-body">
            <h5 class="card-title">Actualizar Datos del Producto</h5>
            <p class="mb-4 text-muted">Modifique la información del producto y guarde los cambios.</p>
            
            <form action="{{ route('productos.update', $item->id) }}" method="POST">
                @csrf
                @method('PUT') {{-- Importante para las peticiones PUT en Laravel --}}

                <div class="mb-3">
                    <label for="categoria_id" class="form-label">Categoría</label>
                    <select name="categoria_id" id="categoria_id" class="form-select @error('categoria_id') is-invalid @enderror" required>
                        <option value="">Selecciona una categoría</option>
                        @foreach ($categorias as $categoria)
                            <option value="{{ $categoria->id }}" {{ (old('categoria_id', $item->categoria_id) == $categoria->id) ? 'selected' : '' }}>
                                {{ $categoria->nombre }}
                            </option>
                        @endforeach
                    </select>
                    @error('categoria_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="proveedor_id" class="form-label">Proveedor</label>
                    <select name="proveedor_id" id="proveedor_id" class="form-select @error('proveedor_id') is-invalid @enderror" required>
                        <option value="">Selecciona un proveedor</option>
                        @foreach ($proveedores as $proveedor)
                            <option value="{{ $proveedor->id }}" {{ (old('proveedor_id', $item->proveedor_id) == $proveedor->id) ? 'selected' : '' }}>
                                {{ $proveedor->nombre }}
                            </option>
                        @endforeach
                    </select>
                    @error('proveedor_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="mb-3">
                    <label for="rack_id" class="form-label">Rack</label>
                    <select name="rack_id" id="rack_id" class="form-select @error('rack_id') is-invalid @enderror" required>
                        <option value="">Selecciona un rack</option>
                        @foreach ($racks as $rack)
                            @php
                                $ocupadas = $rack->productosCount->where('cantidad', '>', 0)->count();
                                $disponible = $rack->cantidad_max - $ocupadas;
                                $rackLleno = $rack->cantidad_max !== null && $disponible <= 0;
                            @endphp
                            <option value="{{ $rack->id }}"
                                @if($rackLleno) style="color: #dc3545; font-weight: bold;" @endif
                                {{ old('rack_id', $item->rack_id) == $rack->id ? 'selected' : '' }}>
                                {{ $rack->rack_aduana }}
                                @if(auth()->user()->rol === 'admin')
                                    (Bodega: {{ $rack->bodega }})
                                @endif
                                @if($rackLleno)
                                    - [LLENO]
                                @else
                                    (Disponible: {{ $disponible }} de {{ $rack->cantidad_max }})
                                @endif
                            </option>
                        @endforeach
                    </select>
                    @error('rack_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="cantidad" class="form-label">Cantidad</label>
                    <input type="number" class="form-control @error('cantidad') is-invalid @enderror" id="cantidad" name="cantidad" value="{{ old('cantidad', $item->cantidad) }}" required min="1" placeholder="Solo números enteros, ej: 100">
                    @error('cantidad')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="precio" class="form-label">Precio Unitario</label>
                    <input type="number" class="form-control @error('precio') is-invalid @enderror" id="precio" name="precio" value="{{ old('precio', $item->precio) }}" required step="0.01" min="0.01" placeholder="Ej: 25.50">
                    @error('precio')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="precio_total" class="form-label">Precio Total</label>
                    <input type="number" class="form-control" id="precio_total" name="precio_total" value="{{ old('precio_total', $item->precio_total) }}" step="0.01" min="0" readonly placeholder="Calculado automáticamente">
                    <div class="form-text text-muted">Este campo se calcula automáticamente (Cantidad x Precio Unitario).</div>
                </div>
                <div class="mb-3">
                    <label for="fecha_ingreso" class="form-label">Fecha de Ingreso</label>
                    <input type="date" class="form-control @error('fecha_ingreso') is-invalid @enderror" id="fecha_ingreso" name="fecha_ingreso"
                           value="{{ old('fecha_ingreso', \Carbon\Carbon::parse($item->fecha_ingreso)->format('Y-m-d')) }}" required>
                    @error('fecha_ingreso')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-4"> {{-- mb-4 para un poco más de espacio antes de los botones --}}
                    <label for="fecha_caducidad" class="form-label">Fecha de Caducidad</label>
                    <input type="date" class="form-control @error('fecha_caducidad') is-invalid @enderror" id="fecha_caducidad" name="fecha_caducidad"
                           value="{{ old('fecha_caducidad', \Carbon\Carbon::parse($item->fecha_caducidad)->format('Y-m-d')) }}" required>
                    <div class="form-text text-muted">Asegúrate de ingresar una fecha de caducidad válida.</div>
                    <div id="preview-estado-caducidad" class="mt-2"></div>
                    @error('fecha_caducidad')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="d-flex justify-content-start gap-2"> {{-- Contenedor flex para alinear botones --}}
                    <button type="submit" class="btn btn-success">
                        <i class="fa-solid fa-sync-alt me-2"></i> Actualizar Producto
                    </button>
                    <a href="{{ route("productos") }}" class="btn btn-secondary"> {{-- Cambiado a btn-secondary --}}
                        <i class="fa-solid fa-ban me-2"></i> Cancelar
                    </a>
                </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </section>
</main>
@endsection

@push('scripts')
<script>
    // Calcular precio total cuando cambia cantidad o precio
    document.getElementById('cantidad').addEventListener('input', calcularPrecioTotal);
    document.getElementById('precio').addEventListener('input', calcularPrecioTotal);

    function calcularPrecioTotal() {
        const cantidad = parseFloat(document.getElementById('cantidad').value) || 0;
        const precio = parseFloat(document.getElementById('precio').value) || 0;
        const precioTotal = cantidad * precio;
        document.getElementById('precio_total').value = precioTotal.toFixed(2);
    }

    // Vista previa del estado de caducidad en tiempo real
    function actualizarPreviewEstadoCaducidad() {
        const input = document.getElementById('fecha_caducidad');
        const preview = document.getElementById('preview-estado-caducidad');
        if (!input || !preview) return;
        const fecha = input.value;
        if (!fecha) { preview.innerHTML = ''; return; }
        const hoy = new Date();
        const fechaCad = new Date(fecha);
        hoy.setHours(0,0,0,0);
        fechaCad.setHours(0,0,0,0);
        const diff = Math.floor((fechaCad - hoy) / (1000*60*60*24));
        let color = '', mensaje = '';
        if (diff < 0) {
            color = 'secondary'; mensaje = 'Vencido hace ' + Math.abs(diff) + ' días';
        } else if (diff === 0) {
            color = 'danger'; mensaje = 'Vence hoy';
        } else if (diff <= 7) {
            color = 'danger'; mensaje = 'Vence en ' + diff + (diff === 1 ? ' día' : ' días');
        } else if (diff <= 14) {
            color = 'warning'; mensaje = 'Vence en ' + diff + ' días';
        } else if (diff <= 28) {
            color = 'success'; mensaje = 'Vence en ' + diff + ' días';
        } else {
            color = 'success'; mensaje = 'Vence en ' + diff + ' días';
        }
        preview.innerHTML = `<span class="badge bg-${color} py-2 px-3">${mensaje}</span>`;
    }
    document.getElementById('fecha_caducidad').addEventListener('input', actualizarPreviewEstadoCaducidad);
    document.addEventListener('DOMContentLoaded', () => {
        calcularPrecioTotal();
        actualizarPreviewEstadoCaducidad();
    });

    // Modal visual para confirmar cambio de bodega
    const rackSelect = document.getElementById('rack_id');
    const racksData = @json($racks->mapWithKeys(fn($r) => [$r->id => $r->bodega]));
    let rackOriginal = '{{ $item->rack_id }}';
    let bodegaOriginal = racksData[rackOriginal] ?? null;
    let rackPendiente = null;
    let modalCambioBodega = new bootstrap.Modal(document.getElementById('modalCambioBodega'));
    rackSelect.addEventListener('change', function() {
        const rackNuevo = this.value;
        const bodegaNueva = racksData[rackNuevo] ?? null;
        if (bodegaOriginal && bodegaNueva && bodegaOriginal !== bodegaNueva) {
            rackPendiente = rackNuevo;
            modalCambioBodega.show();
        }
    });
    document.getElementById('cancelarCambioBodega').addEventListener('click', function() {
        rackSelect.value = rackOriginal;
        rackPendiente = null;
    });
    document.getElementById('confirmarCambioBodega').addEventListener('click', function() {
        if (rackPendiente) {
            rackOriginal = rackPendiente;
            bodegaOriginal = racksData[rackOriginal] ?? null;
            rackPendiente = null;
        }
        modalCambioBodega.hide();
    });
</script>
@endpush

<!-- Modal de confirmación de cambio de bodega -->
<div class="modal fade" id="modalCambioBodega" tabindex="-1" aria-labelledby="modalCambioBodegaLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header bg-warning">
        <h5 class="modal-title" id="modalCambioBodegaLabel"><i class="fa-solid fa-triangle-exclamation me-2"></i>Cambiar de Bodega</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
      </div>
      <div class="modal-body text-center">
        <p class="mb-3 fs-5">El rack seleccionado pertenece a otra <span class="fw-bold text-primary">bodega</span>.<br>¿Deseas mover el producto a esa bodega?</p>
        <div class="alert alert-warning mb-0"><i class="fa-solid fa-circle-info me-2"></i>Esta acción actualizará la bodega del producto.</div>
      </div>
      <div class="modal-footer justify-content-center">
        <button type="button" class="btn btn-secondary" id="cancelarCambioBodega" data-bs-dismiss="modal">Cancelar</button>
        <button type="button" class="btn btn-success" id="confirmarCambioBodega">Sí, mover producto</button>
      </div>
    </div>
  </div>
</div>