<script>
    function agregar_id_usuario(id) {
        // Obtener el elemento checkbox
        const checkbox = document.getElementById(id);
        
        // Obtener el formulario padre
        const formulario = checkbox.closest('form');
        
        // Crear un input oculto para el user_id si no existe
        let inputUserId = formulario.querySelector('input[name="user_id"]');
        if (!inputUserId) {
            inputUserId = document.createElement('input');
            inputUserId.type = 'hidden';
            inputUserId.name = 'user_id';
            inputUserId.value = {{ Auth::id() }};
            formulario.appendChild(inputUserId);
        }
        
        // Actualizar el estado activo basado en el checkbox
        const inputActivo = formulario.querySelector('input[name="activo"]');
        if (inputActivo) {
            inputActivo.value = checkbox.checked ? '1' : '0';
        }
    }
</script>@extends('layouts.main')

@section('titulo', $titulo)

@section('contenido')
<main id="main" class="main">
  <div class="pagetitle">
    <h1>Eliminar Producto</h1>
    
  </div><!-- End Page Title -->
  <section class="section">
    <div class="row">
      <div class="col-lg-12">
        <div class="card">
          <div class="card-body">
            <h5 class="card-title">Eliminar producto del stock</h5>
            <p>
              Una vez que el producto sea eliminado, no podra ser recuperado!!!!
            </p>
            <table class="table table-bordered datatable">
              <thead>
                <tr>
                  <th class="text-center">Clave</th>
                  <th class="text-center">Nombre</th>
                  <th class="text-center">Cantidad</th>
                  <th class="text-center">Colocado</th>
                  <th class="text-center">Bodega</th>
                  <th class="date-center">Fecha de Ingreso</th>
                  <th class="date-center">Fecha de Caducidad</th>
                  <th class="text-center">Bandera</th>
                  <th class="text-center">Activo</th>
                </tr>
              </thead>
              <tbody>
                  <tr class="text-center">
                    <td>{{$items->clave_categorias}}</td>
                    <td>{{$items->nombre_categorias}}</td>
                    <td>{{$items->cantidad}}</td>
                    <td>{{$items->colocado}}</td>
                    <td>{{$items->rol_user}}</td>
                    <td>{{$items->fecha_ingreso}}</td>
                    <td>{{$items->fecha_caducidad}}</td>
                    <td></td>
                    <td>
                      <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" id="{{ $items->id }}" 
                            {{ $items->activo ? 'checked' : '' }}  >
                        </div>
                    </td>
                  </tr>
                 
              </tbody>
            </table>
            <!-- End Table with stripped rows -->
            <hr>
            <form action="{{ route('productos.destroy', $items->id) }}" method="POST">
                @csrf
                @method('DELETE')
                <button class="btn btn-danger">Eliminar producto</button>
                <a href="{{ route('productos') }}" class="btn btn-info">Cancelar</a>
            </form>
          </div>
        </div>
      </div>
    </div>
  </section>

</main>
@endsection

