@extends('layouts.main')
@section('titulo', $titulo)

@section('contenido')
<main id="main" class="main">
    <div class="pagetitle d-flex justify-content-between align-items-center"> {{-- Añadido flexbox para alinear título y posibles elementos --}}
      <h1>Actualizar Categoría</h1>
      {{-- Opcional: Un botón para volver atrás rápidamente en el encabezado de la página --}}
      <a href="{{ route('categorias.index') }}" class="btn btn-secondary btn-sm">
        <i class="fa-solid fa-arrow-left me-2"></i> Volver a Categorías
      </a>
    </div><!-- End Page Title -->

    <section class="section">
      <div class="row">
        <div class="col-lg-8 mx-auto"> {{-- Columna más pequeña y centrada para formularios --}}

          <div class="card">
            <div class="card-body">
              <h5 class="card-title">Formulario de Actualización de Categoría</h5>
              <p class="mb-4 text-muted">Modifique los datos de la categoría y guarde los cambios.</p>
              
              <form action="{{route('categorias.update', $items->id)}}" method="POST">
                @csrf
                @method("PUT") {{-- Importante para las peticiones PUT en Laravel --}}

                <div class="mb-3"> {{-- Contenedor para cada campo con margen inferior --}}
                    <label for="clave" class="form-label">Clave de la Categoría</label>
                    <input type="text" class="form-control @error('clave') is-invalid @enderror" 
                           id="clave" name="clave" value="{{ old('clave', $items->clave) }}" required placeholder="Ej: PROD-AZ">
                    @error('clave')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-4"> {{-- mb-4 para un poco más de espacio antes de los botones --}}
                    <label for="nombre" class="form-label">Nombre de la Categoría</label>
                    <input type="text" class="form-control @error('nombre') is-invalid @enderror" 
                           id="nombre" name="nombre" value="{{ old('nombre', $items->nombre) }}" required placeholder="Ej: Productos de Aseo">
                    @error('nombre')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="d-flex justify-content-start gap-2"> {{-- Contenedor flex para alinear botones --}}
                    <button type="submit" class="btn btn-success">
                        <i class="fa-solid fa-sync-alt me-2"></i> Actualizar Categoría {{-- Icono de actualizar --}}
                    </button>
                    <a href="{{route('categorias.index')}}" class="btn btn-secondary"> {{-- Cambiado a btn-secondary para coherencia --}}
                        <i class="fa-solid fa-ban me-2"></i> Cancelar {{-- Icono de cancelar --}}
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