@extends('layouts.main')

@section('titulo', $titulo)

@section('contenido')
<main id="main" class="main bg-light py-4">
  <div class="pagetitle d-flex justify-content-between align-items-center mb-4">
    <h1 class="fw-bold text-primary"><i class="fa-solid fa-users me-2"></i>Usuarios</h1>
  </div>
  <section class="section">
    <div class="row justify-content-center">
      <div class="col-lg-12">
        <div class="card shadow border-0 mb-4">
          <div class="card-header bg-primary text-white d-flex align-items-center">
            <h5 class="card-title mb-0"><i class="fa-solid fa-user-gear me-2"></i>Administrar Cuentas de Usuario</h5>
            <a href="{{ route("usuarios.create") }}" class="btn btn-success ms-auto">
              <i class="fa-solid fa-user-plus me-2"></i> Nuevo Usuario
            </a>
          </div>
          <div class="card-body bg-white">
            <p class="mb-4 text-muted fs-6">
              Aquí puedes gestionar las cuentas de usuario, asignar roles y controlar su estado de actividad en el sistema.
            </p>
            <div class="table-responsive rounded shadow-sm">
              <table class="table table-hover align-middle bg-white rounded overflow-hidden datatable-export">
                <thead class="table-primary">
                  <tr>
                    <th class="text-start">Correo Electrónico</th>
                    <th class="text-start">Nombre de Usuario</th>
                    <th class="text-center">Rol</th>
                    <th class="text-center">Estado</th>
                    <th class="text-center">Acciones</th>
                  </tr>
                </thead>
                <tbody id="tbody-usuarios">
                   @include('module.usuarios.tbody')
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
</main>

{{-- El modal se incluye aquí al final del contenido principal --}}
@include('module.usuarios.modal_cambiar_password')
@endsection

@push('scripts')
    <script>
      // Funciones JavaScript
      function recargar_tbody(){
        $.ajax({
          type : "GET",
          url : "{{ route('usuarios.tbody') }}",
          success : function(respuesta){
            $('#tbody-usuarios').html(respuesta);
          }
        });
      }

      function cambiar_estado(id, estado) {
        $.ajax({
          type: "GET",
          url : `usuarios/cambiar-estado/${id}/${estado}`,
          success: function(respuesta){
            if(respuesta == 1){
              Swal.fire({
                title: 'Éxito!',
                text: 'Cambio de estado del usuario realizado.',
                icon: 'success',
                confirmButtonText:'Aceptar'
              });
              recargar_tbody();
            } else {
              Swal.fire({
                title: 'Fallo!',
                text: 'No se pudo aplicar el cambio de estado.',
                icon: 'error',
                confirmButtonText:'Aceptar'
              });
            }
          },
          error : function(xhr, status, error) {
            Swal.fire({
              title: 'Error de Conexión!',
              text: 'Hubo un problema al intentar cambiar el estado.',
              icon: 'error',
              confirmButtonText:'Aceptar'
            });
          }
        });
      }

      function agregar_id_usuario(id) {
        $('#id_usuario').val(id);
      }

      function cambio_password(){
        let id = $('#id_usuario').val();
        let password = $('#password').val();

        if (!password || password.length < 6) {
            Swal.fire('Advertencia', 'La contraseña debe tener al menos 6 caracteres.', 'warning');
            return false;
        }

        $.ajax({
          type: "GET",
          url: `usuarios/cambiar-password/${id}/${password}`,
          success :function(respuesta){
            if(respuesta == 1){
               Swal.fire({
                title: 'Éxito!',
                text: 'Contraseña cambiada exitosamente.',
                icon: 'success',
                confirmButtonText:'Aceptar'
              });
              $('#frmPassword')[0].reset();
              $('#cambiarPasswordModal').modal('hide');
            } else {
              Swal.fire({
                title: 'Fallo!',
                text: 'No se pudo cambiar la contraseña.',
                icon: 'error',
                confirmButtonText:'Aceptar'
              });
            }
          },
          error : function(xhr, status, error) {
            Swal.fire({
              title: 'Error de Conexión!',
              text: 'Hubo un problema al intentar cambiar la contraseña.',
              icon: 'error',
              confirmButtonText:'Aceptar'
            });
          }
        });

        return false;
      }

      $(document).ready(function(){
        // Eventos
        $(document).on("change", ".user-active-toggle", function(){
          let id = $(this).data("user-id");
          let estado = $(this).is(":checked") ? 1 : 0;
          cambiar_estado(id, estado);
        });

        $('#frmPassword').on('submit', function(e) {
            e.preventDefault();
            cambio_password();
        });
      });
    </script>
@endpush