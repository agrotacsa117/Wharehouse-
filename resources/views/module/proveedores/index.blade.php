@extends('layouts.main')

@section('titulo', $titulo)

@section('contenido')
<main id="main" class="main bg-light py-4">
  <div class="pagetitle d-flex justify-content-between align-items-center mb-4">
    <h1 class="fw-bold text-primary"><i class="fa-solid fa-truck me-2"></i>Proveedores</h1>
  </div>
  <section class="section">
    <div class="row justify-content-center">
      <div class="col-lg-12">
        <div class="card shadow border-0 mb-4">
          <div class="card-header bg-primary text-white d-flex align-items-center">
            <h5 class="card-title mb-0"><i class="fa-solid fa-users me-2"></i>Administrar Proveedores</h5>
            <a href="{{ route('proveedores.create') }}" class="btn btn-success ms-auto">
              <i class="fa-solid fa-plus me-2"></i> Agregar Nuevo Proveedor
            </a>
          </div>
          <div class="card-body bg-white">
            <p class="mb-4 text-muted fs-6">
              Gestiona la información de todos los proveedores de tus productos.
            </p>
            <div class="table-responsive rounded shadow-sm">
              <table class="table table-bordered table-hover align-middle bg-white rounded overflow-hidden datatable-export">
                <thead class="table-primary">
                  <tr>
                    <th class="text-start">Nombre</th>
                    <th class="text-start">Comprobante Domicilio</th>
                    <th class="text-start">INE</th>
                    <th class="text-start">Acta Constitutiva</th>
                    <th class="text-start">RFC</th>
                    <th class="text-start">Dirección</th>
                    <th class="text-center">Acciones</th>
                  </tr>
                </thead>
                <tbody>
                  @forelse ($items as $item)
                    <tr class="@if($loop->even) bg-light @endif align-middle">
                      <td class="text-start">{{$item->nombre}}</td>
                      <td class="text-start">{{$item->comp_domicilio}}</td>
                      <td class="text-start">{{$item->ine}}</td>
                      <td class="text-start">{{$item->acta_constitutiva}}</td>
                      <td class="text-start">{{$item->rfc}}</td>
                      <td class="text-start">{{$item->direccion}}</td>
                      <td class="text-center">
                        <div class="d-flex justify-content-center gap-2">
                          <a href="{{ route("proveedores.edit", $item->id) }}" class="btn btn-sm btn-outline-warning" title="Editar Proveedor">
                            <i class="fa-solid fa-pencil-alt"></i>
                          </a>
                          <form action="{{ route('proveedores.destroy', $item->id) }}" method="POST" class="d-inline form-eliminar-proveedor">
                              @csrf
                              @method('DELETE')
                              <button type="submit" class="btn btn-sm btn-outline-danger" title="Eliminar Proveedor">
                                  <i class="fa-solid fa-trash-can"></i>
                              </button>
                          </form>
                        </div>
                      </td>
                    </tr>
                  @empty
                    <tr>
                      <td colspan="7" class="text-center text-muted py-4">
                        <i class="fa-solid fa-box-open me-2"></i> No se encontraron proveedores registrados.
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
document.addEventListener('DOMContentLoaded', function () {
    // Selecciona todos los formularios con la clase 'form-eliminar-proveedor'
    const deleteForms = document.querySelectorAll('.form-eliminar-proveedor');

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