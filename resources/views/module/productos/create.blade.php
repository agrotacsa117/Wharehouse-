@extends('layouts.main')
@section('titulo', $titulo)

@section('contenido')
<main id="main" class="main bg-light py-4">
  <div class="pagetitle d-flex justify-content-between align-items-center mb-4">
    <h1 class="fw-bold text-primary"><i class="fa-solid fa-box-open me-2"></i>Ingresar Nuevo Producto</h1>
    <a href="{{ route('productos') }}" class="btn btn-outline-secondary btn-sm">
      <i class="fa-solid fa-arrow-left me-2"></i> Volver a Productos
    </a>
  </div>
  <section class="section">
    <div class="row justify-content-center">
      <div class="col-lg-7 col-xl-6">
        <div class="card shadow border-0">
          <div class="card-header bg-primary text-white">
            <h5 class="card-title mb-0"><i class="fa-solid fa-boxes-stacked me-2"></i>Formulario de Nuevo Producto</h5>
          </div>
          <div class="card-body bg-white">
            @if(session('error'))
                <div class="alert alert-danger">
                    <i class="fa-solid fa-triangle-exclamation me-2"></i>{{ session('error') }}
                </div>
            @endif
            <p class="mb-4 text-muted fs-6">Complete los siguientes campos para registrar un nuevo producto en el inventario.</p>
            <form action="{{ route('productos.store') }}" method="POST">
                @csrf

                <div class="mb-3">
                    <label for="clave_categoria" class="form-label">Buscar Categoría por Clave</label>
                    <input type="text" id="clave_categoria" class="form-control @error('clave_categoria') is-invalid @enderror" placeholder="Ej: CAT-001" autocomplete="off">
                    <input type="hidden" name="categoria_id" id="categoria_id">
                    <div id="categoria_nombre" class="form-text text-success fw-bold"></div>
                    @error('categoria_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="proveedor_id" class="form-label">Proveedor</label>
                    <select name="proveedor_id" id="proveedor_id" class="form-select @error('proveedor_id') is-invalid @enderror" required>
                        <option value="">Selecciona un proveedor</option>
                        @foreach ($proveedores as $item)
                            <option value="{{ $item->id }}" {{ old('proveedor_id') == $item->id ? 'selected' : '' }}>
                                {{ $item->nombre }}
                            </option>
                        @endforeach
                    </select>
                    @error('proveedor_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                @if(auth()->user()->rol === 'admin')
                <div class="mb-3">
                    <label for="bodega" class="form-label">Bodega</label>
                    <select name="bodega" id="bodega" class="form-select @error('bodega') is-invalid @enderror" required>
                        <option value="">Selecciona una bodega</option>
                        <option value="tapachula" {{ old('bodega') == 'tapachula' ? 'selected' : '' }}>Bodega Tapachula</option>
                        <option value="bodega_dorado" {{ old('bodega') == 'bodega_dorado' ? 'selected' : '' }}>Bodega Dorado</option>
                    </select>
                    @error('bodega')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                @endif

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
                                {{ old('rack_id') == $rack->id ? 'selected' : '' }}>
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
                    <input type="number" class="form-control @error('cantidad') is-invalid @enderror" id="cantidad" name="cantidad" value="{{ old('cantidad') }}" required min="1" placeholder="Solo números enteros, ej: 100">
                    @error('cantidad')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="precio" class="form-label">Precio Unitario</label>
                    <input type="number" class="form-control @error('precio') is-invalid @enderror" id="precio" name="precio" value="{{ old('precio') }}" step="0.01" min="0.01" required placeholder="Ej: 25.50">
                    @error('precio')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="precio_total" class="form-label">Precio Total</label>
                    <input type="number" class="form-control" id="precio_total" name="precio_total" step="0.01" min="0" readonly placeholder="Calculado automáticamente">
                    <div class="form-text text-muted">Este campo se calcula automáticamente (Cantidad x Precio Unitario).</div>
                </div>

                <div class="mb-3">
                    <label for="fecha_ingreso" class="form-label">Fecha de Ingreso</label>
                    <input type="date" class="form-control @error('fecha_ingreso') is-invalid @enderror" id="fecha_ingreso" name="fecha_ingreso" value="{{ old('fecha_ingreso', now()->format('Y-m-d')) }}" required>
                    @error('fecha_ingreso')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-4"> {{-- mb-4 para un poco más de espacio antes de los botones --}}
                    <label for="fecha_caducidad" class="form-label">Fecha de Caducidad</label>
                    <input type="date" class="form-control @error('fecha_caducidad') is-invalid @enderror" id="fecha_caducidad" name="fecha_caducidad" value="{{ old('fecha_caducidad') }}" required>
                    <div class="form-text text-muted">Asegúrate de ingresar una fecha de caducidad válida.</div>
                    @error('fecha_caducidad')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="d-flex justify-content-start gap-2">
                    <button type="submit" class="btn btn-primary">
                        <i class="fa-solid fa-save me-2"></i> Guardar Producto
                    </button>
                    <a href="{{ route('productos') }}" class="btn btn-secondary">
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

    document.addEventListener('DOMContentLoaded', calcularPrecioTotal);

    // Array de categorías para búsqueda rápida por clave
    const categorias = @json($categorias);
    const inputClave = document.getElementById('clave_categoria');
    const inputId = document.getElementById('categoria_id');
    const divNombre = document.getElementById('categoria_nombre');

    inputClave.addEventListener('input', function() {
        const clave = this.value.trim().toLowerCase();
        let encontrada = null;
        categorias.forEach(cat => {
            if (cat.clave && cat.clave.toLowerCase() === clave) {
                encontrada = cat;
            }
        });
        if (encontrada) {
            divNombre.textContent = 'Categoría: ' + encontrada.nombre;
            divNombre.classList.remove('text-danger');
            divNombre.classList.add('text-success');
            inputId.value = encontrada.id;
        } else if (clave.length > 0) {
            divNombre.textContent = 'No existe una categoría con esa clave.';
            divNombre.classList.remove('text-success');
            divNombre.classList.add('text-danger');
            inputId.value = '';
        } else {
            divNombre.textContent = '';
            divNombre.classList.remove('text-success', 'text-danger');
            inputId.value = '';
        }
    });

    document.querySelector('form').addEventListener('submit', function(e) {
        if (!inputId.value) {
            e.preventDefault();
            Swal.fire({
                title: 'Categoría inválida',
                text: 'Debes ingresar una clave de categoría válida antes de guardar el producto.',
                icon: 'error',
                confirmButtonText: 'Aceptar'
            });
            inputClave.focus();
        }
    });

    // Validación de rack y bodega
    const bodegaSelect = document.getElementById('bodega');
    const rackSelect = document.getElementById('rack_id');
    const btnGuardar = document.querySelector('button[type="submit"]');
    let racksBodegas = {};
    @foreach ($racks as $rack)
        racksBodegas[{{ $rack->id }}] = "{{ $rack->bodega }}";
    @endforeach
    function validarRackBodega() {
        if (!bodegaSelect || !rackSelect) return;
        const bodega = bodegaSelect.value;
        const rackId = rackSelect.value;
        const advertencia = document.getElementById('rack-bodega-advertencia');
        if (bodega && rackId && racksBodegas[rackId] && racksBodegas[rackId] !== bodega) {
            btnGuardar.disabled = true;
            if (!advertencia) {
                const div = document.createElement('div');
                div.id = 'rack-bodega-advertencia';
                div.className = 'alert alert-danger mt-2';
                div.innerHTML = 'El rack seleccionado no pertenece a la bodega elegida. Selecciona un rack válido.';
                rackSelect.parentNode.appendChild(div);
            }
        } else {
            btnGuardar.disabled = false;
            if (advertencia) advertencia.remove();
        }
    }
    if (bodegaSelect && rackSelect) {
        bodegaSelect.addEventListener('change', validarRackBodega);
        rackSelect.addEventListener('change', validarRackBodega);
        document.addEventListener('DOMContentLoaded', validarRackBodega);
    }

    // Establecer fecha de ingreso con la fecha local del usuario si está vacío
    document.addEventListener('DOMContentLoaded', function() {
        var fechaIngreso = document.getElementById('fecha_ingreso');
        if (fechaIngreso && !fechaIngreso.value) {
            const hoy = new Date();
            const yyyy = hoy.getFullYear();
            const mm = String(hoy.getMonth() + 1).padStart(2, '0');
            const dd = String(hoy.getDate()).padStart(2, '0');
            fechaIngreso.value = `${yyyy}-${mm}-${dd}`;
        }
    });
</script>
@endpush