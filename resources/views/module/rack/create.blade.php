@extends('layouts.main')
@section('titulo', $titulo)

@section('contenido')
<main id="main" class="main bg-light py-4">
    <div class="pagetitle d-flex justify-content-between align-items-center mb-4">
      <h1 class="fw-bold text-primary"><i class="fa-solid fa-tags me-2"></i>Agregar Rack o Aduana</h1>
      <a href="{{ route('rack.index') }}" class="btn btn-outline-secondary btn-sm">
        <i class="fa-solid fa-arrow-left me-2"></i> Volver a Rack o Aduana
      </a>
    </div>
    <section class="section">
      <div class="row justify-content-center">
        <div class="col-lg-7 col-xl-6">
          <div class="card shadow border-0">
            <div class="card-header bg-primary text-white">
              <h5 class="card-title mb-0"><i class="fa-solid fa-tag me-2"></i>Formulario de Nueva Rack o Aduana</h5>
            </div>
            <div class="card-body bg-white">
              <p class="mb-4 text-muted fs-6">Complete los siguientes campos para registrar una nueva Rack o Aduana.</p>
              <form action="{{ route('rack.store') }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label for="rack_aduana" class="form-label">Nombre del Rack o Aduana</label>
                    <input type="text" class="form-control @error('rack_aduana') is-invalid @enderror" 
                           id="rack_aduana" name="rack_aduana" value="{{ old('rack_aduana') }}" required placeholder="Ej: RACK-01">
                    @error('rack_aduana')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="mb-4">
                    <label for="cantidad_max" class="form-label">Cantidad Máxima</label>
                    <input type="number" class="form-control @error('cantidad_max') is-invalid @enderror" 
                           id="cantidad_max" name="cantidad_max" value="{{ old('cantidad_max') }}" required placeholder="Ej: 100">
                    @error('cantidad_max')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="mb-3">
                    <label for="bodega" class="form-label">Bodega Asignar</label>
                    <select class="form-select @error('bodega') is-invalid @enderror" id="bodega" name="bodega" required>
                        <option value="">Selecciona una bodega</option>
                        <option value="tapachula" {{ old('bodega') == 'tapachula' ? 'selected' : '' }}>Tapachula</option>
                        <option value="bodega_dorado" {{ old('bodega') == 'bodega_dorado' ? 'selected' : '' }}>Bodega Dorado</option>
                    </select>
                    @error('bodega')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="d-flex justify-content-start gap-2">
                    <button type="submit" class="btn btn-primary">
                        <i class="fa-solid fa-save me-2"></i> Guardar Rack
                    </button>
                    <a href="{{ route('rack.index') }}" class="btn btn-secondary">
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