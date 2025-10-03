@extends('layouts.main')
@section('titulo', $titulo)

@section('contenido')
<main id="main" class="main bg-light py-4">
    <div class="pagetitle d-flex justify-content-between align-items-center mb-4">
      <h1 class="fw-bold text-primary"><i class="fa-solid fa-user-plus me-2"></i>Agregar Usuario</h1>
      {{-- <a href="{{ route('usuarios') }}" class="btn btn-outline-secondary btn-sm"><i class="bi bi-arrow-left me-2"></i> Volver a Usuarios</a> --}}
    </div>
    <section class="section">
      <div class="row justify-content-center">
        <div class="col-lg-7 col-xl-6">
          <div class="card shadow border-0">
            <div class="card-header bg-primary text-white">
              <h5 class="card-title mb-0"><i class="fa-solid fa-user-plus me-2"></i>Crear Nuevo Usuario</h5>
            </div>
            <div class="card-body bg-white">
              @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="fa-solid fa-triangle-exclamation me-2"></i> {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
              @endif
              @if ($errors->any())
                <div class="alert alert-danger">
                  <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                      <li>{{ $error }}</li>
                    @endforeach
                  </ul>
                </div>
              @endif
              <p class="mb-4 text-muted fs-6">Complete los campos para registrar un nuevo usuario en el sistema.</p>
              <form action="{{route('usuarios.store')}}" method="POST">
                @csrf
                <div class="mb-3">
                    <label for="name" class="form-label">Nombre del Usuario</label>
                    <input type="text" class="form-control" id="name" name="name" required placeholder="Ej: Juan Pérez">
                </div>
                <div class="mb-3">
                    <label for="email" class="form-label">Correo Electrónico</label>
                    <input type="email" class="form-control" id="email" name="email" required placeholder="Ej: usuario@example.com">
                </div>
                <div class="mb-3">
                    <label for="password" class="form-label">Contraseña</label>
                    <input type="password" class="form-control" id="password" name="password" required minlength="6" placeholder="Mínimo 6 caracteres">
                    <div class="form-text">La contraseña debe tener al menos 6 caracteres.</div>
                </div>
                <div class="mb-4">
                    <label for="rol" class="form-label">Rol del Usuario</label>
                    <select name="rol" id="rol" class="form-select" required>
                        <option value="">Selecciona el rol</option>
                        <option value="admin">Administrador</option>
                        <option value="tapachula">Bodega Tapachula</option>
                        <option value="bodega_dorado">Bodega El Dorado</option>
                    </select>
                </div>
                <div class="d-flex justify-content-start gap-2">
                    <button type="submit" class="btn btn-primary">
                        <i class="fa-solid fa-save me-2"></i> Guardar Usuario
                    </button>
                    <a href="{{route('usuarios')}}" class="btn btn-secondary">
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