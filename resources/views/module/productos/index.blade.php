@extends('layouts.main')

@section('titulo', $titulo)

@section('contenido')
<main id="main" class="main bg-light py-4">
  <div class="pagetitle d-flex justify-content-between align-items-center mb-4">
    <h1 class="fw-bold text-primary"><i class="fa-solid fa-basket-shopping me-2"></i>Productos</h1>
  </div>
  <section class="section">
    <div class="row justify-content-center">
      <div class="col-lg-12">
        <div class="card shadow border-0 mb-4">
          <div class="card-header bg-primary text-white d-flex align-items-center">
            <h5 class="card-title mb-0"><i class="fa-solid fa-warehouse me-2"></i>Administrar Productos e Inventario</h5>
            @if(in_array(auth()->user()->rol, ['admin', 'tapachula', 'bodega_dorado']))
              <a href="{{route('productos.create')}}" class="btn btn-success ms-auto">
                <i class="fa-solid fa-plus-circle me-2"></i> Ingresar Nuevo Producto
              </a>
            @endif
          </div>
          <div class="card-body bg-white">
            <p class="mb-4 text-muted fs-6">
              Aquí puedes gestionar el stock de productos, sus detalles y estados de caducidad.
            </p>
            <div class="table-responsive rounded shadow-sm">
              <table class="table table-hover align-middle bg-white rounded overflow-hidden datatable-export">
                <thead class="table-primary">
                  <tr>
                    <th class="text-start">Proveedor</th>
                    <th class="text-start">Clave</th>
                    <th class="text-start">Nombre</th>
                    <th class="text-center">Cantidad</th>
                    <th class="text-center">Precio Unitario</th>
                    <th class="text-center">Precio Total</th>
                    <th class="text-start">Rack/Aduana</th>
                    <th class="text-start">Bodega</th>
                    <th class="text-center">Fecha Ingreso</th>
                    <th class="text-center">Fecha Caducidad</th>
                    <th class="text-center">Estado de Caducidad</th>
                    @if(auth()->user()->rol === 'admin')
                      <th class="text-center">Activo</th>
                    @endif
                    <th class="text-center">Acciones</th>
                  </tr>
                </thead>
                <tbody>
                  @forelse ($items as $item)
                    <tr class="@if($loop->even) bg-light @endif align-middle">
                      <td class="text-start">{{ $item->nombre_proveedores}}</td>
                      <td class="text-start">{{ $item->clave_categorias }}</td>
                      <td class="text-start">{{ $item->nombre_categorias }}</td>
                      <td class="text-center fw-bold text-success">{{ $item->cantidad }}</td>
                      <td class="text-center text-primary">$ {{ number_format($item->precio, 2) }}</td>
                      <td class="text-center text-primary">$ {{ number_format($item->precio_total, 2) }}</td>
                      <td class="text-start">
                        @if($item->rack)
                          {{ $item->rack->rack_aduana }}
                        @else
                          -
                        @endif
                      </td>
                      <td class="text-start">{{ ucfirst(str_replace('_', ' ', $item->rol)) }}</td>
                      <td class="text-center">{{ \Carbon\Carbon::parse($item->fecha_ingreso)->format('d/m/Y') }}</td>
                      <td class="text-center">{{ \Carbon\Carbon::parse($item->fecha_caducidad)->format('d/m/Y') }}</td>
                      <td class="text-center">
                          <?php
                              $hoy = \Carbon\Carbon::today();
                              $fechaCaducidad = \Carbon\Carbon::parse($item->fecha_caducidad);
                              $dias = $hoy->diffInDays($fechaCaducidad, false); // false para incluir signo (positivo si futuro, negativo si pasado)
                              $mensajeCaducidad = '';
                              $colorBadge = '';
                              
                              if ($dias < 0) {
                                  $colorBadge = 'secondary'; // Gris para vencido
                                  $mensajeCaducidad = 'Vencido hace ' . abs($dias) . ' días';
                              } elseif ($dias == 0) {
                                  $colorBadge = 'danger'; // Rojo para hoy
                                  $mensajeCaducidad = 'Vence hoy';
                              } elseif ($dias <= 7) {
                                  $colorBadge = 'danger'; // Rojo para 1-7 días
                                  $mensajeCaducidad = 'Vence en ' . $dias . ($dias == 1 ? ' día' : ' días');
                              } elseif ($dias <= 14) {
                                  $colorBadge = 'warning'; // Amarillo para 8-14 días (2 semanas)
                                  $mensajeCaducidad = 'Vence en ' . $dias . ' días';
                              } elseif ($dias <= 28) {
                                  $colorBadge = 'success'; // Verde para 15-28 días (de 2 a 4 semanas)
                                  $mensajeCaducidad = 'Vence en ' . $dias . ' días';
                              } else {
                                  $colorBadge = 'success'; // Verde para más de 4 semanas
                                  $mensajeCaducidad = 'Vence en ' . $dias . ' días';
                              }
                          ?>
                          <span class="badge bg-{{ $colorBadge }} d-block py-2"> {{-- d-block para ocupar ancho, py-2 para padding --}}
                              {{ $mensajeCaducidad }}
                          </span>
                          <small class="text-muted mt-1 d-block">Ingresado por: {{ $item->nombre_usuario }}</small> {{-- d-block para nueva línea --}}
                      </td>
                      @if(auth()->user()->rol === 'admin')
                        <td class="text-center">
                          <div class="form-check form-switch d-inline-block">
                            <input class="form-check-input product-active-toggle" type="checkbox" role="switch" 
                                   id="toggle-{{ $item->id }}" data-product-id="{{ $item->id }}" {{ $item->activo ? 'checked' : '' }}>
                            <label class="form-check-label visually-hidden" for="toggle-{{ $item->id }}">Activo</label>
                          </div>
                        </td>
                      @endif
                      <td class="text-center">
                        <div class="d-flex justify-content-center gap-2">
                          @if(auth()->user()->rol === 'admin')
                          <a href="{{route('productos.edit', $item->id)}}" class="btn btn-sm btn-outline-warning" title="Editar Producto">
                              <i class="fa-solid fa-pencil-alt"></i>
                          </a>
                          <form action="{{ route('productos.destroy', $item->id) }}" method="POST" class="d-inline form-eliminar-producto">
                              @csrf
                              @method('DELETE')
                              <button type="submit" class="btn btn-sm btn-outline-danger" title="Eliminar Producto">
                                  <i class="fa-solid fa-trash-can"></i>
                              </button>
                          </form>
                          @else
                              <span class="badge bg-secondary text-white">Sin permisos</span>
                          @endif
                        </div>
                      </td>
                    </tr>
                  @empty
                    <tr>
                      <td colspan="@if(auth()->user()->rol === 'admin') 13 @else 12 @endif" class="text-center text-muted py-4">
                        <i class="fa-solid fa-box-open me-2"></i> No se encontraron productos registrados.
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
  </section>
</main>
@endsection
@push('scripts')
    <script>
      // Función para cambiar el estado del producto (activo/inactivo)
      function cambiar_estado(id, estado) {
        $.ajax({
          type: "GET", // Mantenemos GET según el código original
          url : `productos/cambiar-estado/${id}/${estado}`, // Uso de template literals para la URL
          success: function(respuesta){
            if(respuesta == 1){
              Swal.fire({
                title: 'Éxito!',
                text: 'El estado del producto ha sido actualizado.',
                icon: 'success',
                confirmButtonText:'Aceptar'
              });
              // Opcional: recargar la tabla si el cambio en el backend afecta más que solo el estado visible
              // location.reload(); // Recarga completa, menos eficiente
            } else {
              Swal.fire({
                title: 'Fallo!',
                text: 'No se pudo cambiar el estado del producto.',
                icon: 'error',
                confirmButtonText:'Aceptar'
              });
              // Revertir el estado del switch si la operación falló en el backend
              $(`#toggle-${id}`).prop('checked', !estado); 
            }
          },
          error: function(xhr, status, error) {
            Swal.fire({
              title: 'Error de Conexión!',
              text: 'Hubo un problema al intentar cambiar el estado del producto.',
              icon: 'error',
              confirmButtonText:'Aceptar'
            });
            // Revertir el estado del switch si hay un error de conexión
            $(`#toggle-${id}`).prop('checked', !estado);
          }
        });
      }

      $(document).ready(function(){
        // Usamos delegación de eventos para el switch de estado '.product-active-toggle'
        // Esto es crucial si el tbody se recarga dinámicamente, asegurando que los nuevos elementos también tengan el evento.
        $(document).on("change", ".product-active-toggle", function(){
          let id = $(this).data("product-id"); // Obtener el ID del producto desde el atributo data-product-id
          let estado = $(this).is(":checked") ? 1 : 0;
          cambiar_estado(id, estado);
        });

        // Confirmación SweetAlert2 para eliminar producto
        document.querySelectorAll('.form-eliminar-producto').forEach(form => {
          form.addEventListener('submit', function (event) {
            event.preventDefault();
            Swal.fire({
              title: '¿Estás seguro?',
              text: "¡No podrás revertir esto!",
              icon: 'warning',
              showCancelButton: true,
              confirmButtonColor: '#d33',
              cancelButtonColor: '#3085d6',
              confirmButtonText: 'Sí, eliminarlo!',
              cancelButtonText: 'Cancelar'
            }).then((result) => {
              if (result.isConfirmed) {
                form.submit();
              }
            });
          });
        });

        // Mostrar mensaje de éxito al crear producto
        @if(session('success'))
        Swal.fire({
          title: '¡Éxito!',
          text: @json(session('success')),
          icon: 'success',
          confirmButtonText: 'Aceptar',
        });
        @endif
      });
    </script>
@endpush