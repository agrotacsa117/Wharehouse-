@extends('layouts.main')

@section('titulo', $titulo)

@section('contenido')
<main id="main" class="main">
  <div class="pagetitle d-flex justify-content-between align-items-center"> {{-- Añadido flexbox para alinear título y posibles elementos --}}
    <h1>Categorías</h1>
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
            <h5 class="card-title mb-0">Administrar Categorías de Productos</h5> {{-- Título mejorado, mb-0 para eliminar margen inferior --}}
            <a href="{{ route("categorias.create") }}" class="btn btn-success">
              <i class="fa-solid fa-plus me-2"></i> Agregar Nueva Categoría {{-- Icono y espaciado --}}
            </a>
          </div>
          <div class="card-body">
            <p class="mb-4 text-muted">
              Gestiona las categorías a las que pertenecen tus productos para una mejor organización.
            </p>
            
            <div class="table-responsive"> {{-- Contenedor para hacer la tabla desplazable en pantallas pequeñas --}}
              <table class="table table-bordered table-hover datatable-export"> {{-- table-hover para efecto visual al pasar el ratón --}}
                <thead>
                  <tr>
                    <th class="text-start">Clave de Categoría</th> {{-- Texto más descriptivo --}}
                    <th class="text-start">Nombre de Categoría</th> {{-- Texto más descriptivo --}}
                    <th class="text-center">Acciones</th>
                  </tr>
                </thead>
                <tbody>
                  @forelse ($items as $item)
                    <tr class="align-middle"> {{-- Alinea verticalmente el contenido de la fila --}}
                      <td class="text-start">{{$item->clave}}</td>
                      <td class="text-start">{{$item->nombre}}</td>
                      <td class="text-center">
                        <div class="d-flex justify-content-center gap-2"> {{-- Flexbox para alinear y espaciar los botones --}}
                          {{-- Botón Editar (ruta y estilo original respetados) --}}
                          <a href="{{route("categorias.edit", $item->id)}}" class="btn btn-sm btn-outline-warning" title="Editar Categoría">
                            <i class="fa-solid fa-pencil-alt"></i> {{-- Icono de lápiz más moderno --}}
                          </a>
                          {{-- Botón Eliminar con formulario DELETE y SweetAlert2 para confirmación --}}
                          <form action="{{ route('categorias.destroy', $item->id) }}" method="POST" class="d-inline form-eliminar-categoria">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-outline-danger" title="Eliminar Categoría">
                              <i class="fa-solid fa-trash"></i>
                            </button>
                          </form>
                        </div>
                      </td>
                    </tr>
                  @empty
                    <tr>
                      <td colspan="3" class="text-center text-muted py-4"> {{-- colspan abarca todas las columnas --}}
                        <i class="fa-solid fa-tags me-2"></i> No se encontraron categorías registradas.
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
    // Selecciona todos los formularios con la clase 'form-eliminar-categoria'
    const deleteForms = document.querySelectorAll('.form-eliminar-categoria');

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