@extends('layouts.main')
@section('titulo', $titulo)

@section('contenido')
<main id="main" class="main bg-light py-4">
    <div class="pagetitle mb-4">
      <h1 class="fw-bold text-primary"><i class="fa-solid fa-user-pen me-2"></i>Editar Usuario</h1>
    </div>
    <section class="section">
      <div class="row justify-content-center">
        <div class="col-lg-7">
          <div class="card shadow border-0">
            <div class="card-body p-4">
              <h5 class="card-title mb-4 text-secondary"><i class="fa-solid fa-user-gear me-2"></i>Datos del Usuario</h5>
              <form action="{{route('usuarios.update', $items->id)}}" method="POST" autocomplete="off">
                @csrf
                @method('PUT')
                <div class="mb-3">
                  <label for="name" class="form-label">Nombre del Usuario</label>
                  <input type="text" class="form-control" required name="name" id="name" value="{{$items->name}}">
                </div>
                <div class="mb-3">
                  <label for="email" class="form-label">Correo</label>
                  <input type="email" class="form-control" required name="email" id="email" value="{{$items->email}}">
                </div>
                <div class="mb-3">
                  <label for="rol" class="form-label">Rol</label>
                  <select name="rol" id="rol" class="form-select" required>
                    <option value="">Selecciona el rol</option>
                    <option value="admin" @if($items->rol=='admin') selected @endif>Administrador</option>
                    <option value="tapachula" @if($items->rol=='tapachula') selected @endif>Tapachula</option>
                    <option value="bodega_dorado" @if($items->rol=='bodega_dorado') selected @endif>Bodega Dorado</option>
                  </select>
                </div>
                <div class="d-flex gap-2 mt-4">
                  <button class="btn btn-success flex-fill"><i class="fa-solid fa-save me-2"></i>Actualizar</button>
                  <a href="{{route('usuarios')}}" class="btn btn-outline-secondary flex-fill"><i class="fa-solid fa-arrow-left me-2"></i>Cancelar</a>
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>
    </section>
</main>
@endsection