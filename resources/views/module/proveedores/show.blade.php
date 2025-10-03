@extends('layouts.main')

@section('titulo', $titulo)

@section('contenido')
<main id="main" class="main">
  <div class="pagetitle">
    <h1>Eliminar un Proveedores</h1>
    
  </div><!-- End Page Title -->
  <section class="section">
    <div class="row">
      <div class="col-lg-12">
        <div class="card">
          <div class="card-body">
            <h5 class="card-title">Eliminar Proveedores</h5>
            
            <div class="alert alert-warning d-flex align-items-center justify-content-center my-4 p-4 rounded-3 shadow-sm text-center" role="alert">
              <i class="fa-solid fa-user-xmark fa-2x me-3"></i>
              <div>
                <h4 class="alert-heading mb-2">¿Estás seguro que deseas eliminar este proveedor?</h4>
                <p class="mb-1">Esta acción <span class="fw-bold text-uppercase">no se puede deshacer</span> y el proveedor será eliminado de forma permanente.</p>
                <p class="mb-0 text-danger">Confirma solo si estás completamente seguro.</p>
              </div>
            </div>
            <!-- Table with stripped rows --> 
            <table class="table table-bordered">
              <thead>
                <tr>
                  <th class="text-center">nombre</th>
                  <th class="text-center">comprobante de domicilio</th>
                  <th class="text-center">ine</th>
                  <th class="text-center">acta constitutiva</th>
                  <th class="text-center">rfc</th>
                  <th class="text-center">direccion</th>
                </tr>
              </thead>
              <tbody>
                  <tr class="text-center">
                    <td>{{$items->nombre}}</td>
                    <td>{{$items->comp_domicilio}}</td>
                    <td>{{$items->ine}}</td>
                    <td>{{$items->acta_constitutiva}}</td>
                    <td>{{$items->rfc}}</td>
                    <td>{{$items->direccion}}</td>
                  </tr>
              </tbody>
            </table>
            <br>
            <form action="{{route("proveedores.destroy", $items->id)}}" method="POST">
              @csrf
              @method('DELETE')
              <button class="btn btn-danger mt-3">Eliminar proveedor</button>
              <a href="{{route("proveedores")}}" class="btn btn-info mt-3">Cancelar
                </a>
            </form>
          </div>
        </div>
      </div>
    </div>
  </section>

</main>
@endsection