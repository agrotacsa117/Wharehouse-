@extends('layouts.main')

@section('titulo', $titulo)

@section('contenido')
<main id="main" class="main">
  <div class="pagetitle d-flex justify-content-between align-items-center"> {{-- Añadido flexbox para alinear título y posibles elementos --}}
    <h1>Rack o Aduana</h1>
    {{-- Opcional: Un botón para volver atrás rápidamente --}}
    {{-- <a href="{{ route('algun.lugar.anterior') }}" class="btn btn-secondary btn-sm">
      <i class="fa-solid fa-arrow-left me-2"></i> Volver
    </a> --}}
  </div><!-- End Page Title -->

  <section class="section">
    <div class="row">
      <div class="col-lg-12">
        <div class="card">
          <div class="card-header d-flex justify-content-between align-items-center"> {{-- Encabezado de la tarjeta con alineación --}}
            <h5 class="card-title mb-0">Administrar Rack/Aduana Para El Productos</h5> {{-- Título mejorado, mb-0 para eliminar margen inferior --}}
            <a href="{{ route('rack.create') }}" class="btn btn-success">
              <i class="fa-solid fa-plus me-2"></i> Agregar Nuevo Rack/Aduana {{-- Icono y espaciado --}}
            </a>
          </div>
          <div class="card-body">
            @if(session('success'))
              <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="fa-solid fa-check-circle me-2"></i> {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
              </div>
            @endif
            <p class="mb-4 text-muted">
              Gestiona el Rack/Aduana a las que pertenecen tus productos para una mejor organización.
            </p>
            
            <div class="table-responsive"> {{-- Contenedor para hacer la tabla desplazable en pantallas pequeñas --}}
              <table class="table table-bordered table-hover datatable-export"> {{-- table-hover para efecto visual al pasar el ratón --}}
                <thead>
                  <tr>
                    <th class="text-start">Rack o  aduana</th> {{-- Texto más descriptivo --}}
                    <th class="text-start">Cantidad Maxima</th> {{-- Texto más descriptivo --}}
                    <th class="text-center">Bodega</th>
                    <th class="text-center">Acciones</th>
                  </tr>
                </thead>
                <tbody>
                  @forelse ($items as $item)
                    <tr class="align-middle">
                      <td class="text-start">{{ $item->rack_aduana }}</td>
                      <td class="text-start">{{ $item->cantidad_max }}</td>
                      <td class="text-center">
                        @if($item->bodega === 'tapachula')
                          BODEGA TAPACHULA
                        @elseif($item->bodega === 'bodega_dorado')
                          BODEGA DORADO
                        @else
                          {{ $item->bodega ?? '-' }}
                        @endif
                      </td>
                      <td class="text-center">
                        <div class="d-flex justify-content-center gap-2">
                          <a href="{{ route('rack.edit', $item->id) }}" class="btn btn-sm btn-outline-warning" title="Editar Rack">
                            <i class="fa-solid fa-pencil-alt"></i>
                          </a>
                          <form action="{{ route('rack.destroy', $item->id) }}" method="POST" class="d-inline form-eliminar-rack">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-outline-danger" title="Eliminar Rack">
                              <i class="fa-solid fa-trash"></i>
                            </button>
                          </form>
                        </div>
                      </td>
                    </tr>
                  @empty
                    <tr>
                      <td colspan="3" class="text-center text-muted py-4"> {{-- colspan abarca todas las columnas --}}
                        <i class="fa-solid fa-tags me-2"></i> No se encontraron Rack o Aduana registradas.
                      </td>
                    </tr>
                  @endforelse
                </tbody>
              </table>
            </div>
            <!-- End Table -->
          </div>
        </div>
      </div>
    </div>
  </section>

</main>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    // Selecciona todos los formularios con la clase 'form-eliminar-rack'
    const deleteForms = document.querySelectorAll('.form-eliminar-rack');

    deleteForms.forEach(form => {
        form.addEventListener('submit', function (event) {
            event.preventDefault(); // Previene el envío del formulario por defecto

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
                    // Si el usuario confirma, envía el formulario
                    form.submit();
                }
            });
        });
    });
});
</script>
@endpush