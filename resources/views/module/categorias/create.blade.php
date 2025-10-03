@extends('layouts.main')
@section('titulo', $titulo)

@section('contenido')
<main id="main" class="main bg-light py-4">
    <div class="pagetitle d-flex justify-content-between align-items-center mb-4">
      <h1 class="fw-bold text-primary"><i class="fa-solid fa-tags me-2"></i>Agregar Categoría</h1>
      <a href="{{ route('categorias.index') }}" class="btn btn-outline-secondary btn-sm">
        <i class="fa-solid fa-arrow-left me-2"></i> Volver a Categorías
      </a>
    </div>
    <section class="section">
      <div class="row justify-content-center">
        <div class="col-lg-7 col-xl-6">
          <div class="card shadow border-0">
            <div class="card-header bg-primary text-white">
              <h5 class="card-title mb-0"><i class="fa-solid fa-tag me-2"></i>Formulario de Nueva Categoría</h5>
            </div>
            <div class="card-body bg-white">
              <p class="mb-4 text-muted fs-6">Complete los siguientes campos para registrar una nueva categoría.</p>
              <form action="{{route('categorias.store')}}" method="POST">
                @csrf
                <div class="mb-3">
                    <label for="clave" class="form-label">Clave de la Categoría</label>
                    <input type="text" class="form-control @error('clave') is-invalid @enderror" 
                           id="clave" name="clave" value="{{ old('clave') }}" required placeholder="Ej: PROD-AZ">
                    @error('clave')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="mb-4">
                    <label for="nombre" class="form-label">Nombre de la Categoría</label>
                    <input type="text" class="form-control @error('nombre') is-invalid @enderror" 
                           id="nombre" name="nombre" value="{{ old('nombre') }}" required placeholder="Ej: Productos de Aseo">
                    @error('nombre')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="d-flex justify-content-start gap-2">
                    <button type="submit" class="btn btn-primary">
                        <i class="fa-solid fa-save me-2"></i> Guardar Categoría
                    </button>
                    <a href="{{route('categorias.index')}}" class="btn btn-secondary">
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