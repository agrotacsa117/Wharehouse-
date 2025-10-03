@extends('layouts.main')
@section('titulo', $titulo)

@section('contenido')
<main id="main" class="main">
    <div class="pagetitle">
      <h1>Eliminar Categoria</h1>
      
    </div>
    <section class="section">
      <div class="row">
        <div class="col-lg-12">

          <div class="card">
            <div class="card-body">
              <h5 class="card-title">¿Seguro que quieres elimonar la categoria?</h5>
              
              <div class="alert alert-warning d-flex align-items-center justify-content-center my-4 p-4 rounded-3 shadow-sm text-center" role="alert">
                <i class="fa-solid fa-tag fa-2x me-3"></i>
                <div>
                  <h4 class="alert-heading mb-2">¿Estás seguro que deseas eliminar esta categoría?</h4>
                  <p class="mb-1">Esta acción <span class="fw-bold text-uppercase">no se puede deshacer</span> y la categoría será eliminada de forma permanente.</p>
                  <p class="mb-0 text-danger">Confirma solo si estás completamente seguro.</p>
                </div>
              </div>

              <form action="{{route("categorias.destroy", $items->id)}}" method="POST">
                @csrf
                @method('DELETE')
                <label for="clave">Clave de la categoria</label>
                <input type="text" class="from-control" readonly 
                name="clave" id="clave" value="{{$items->clave}}">
                <button class="btn btn-danger mt-3">Eliminar</button>
                <a href="{{route("categorias")}}" class="btn btn-info mt-3">Cancelar</a>
              </form>
            </div>
          </div>

        </div>
      </div>
    </section>

  </main>
@endsection