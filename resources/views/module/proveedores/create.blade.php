@extends('layouts.main')
@section('titulo', $titulo)

@section('contenido')
<main id="main" class="main bg-light py-4">
    <div class="pagetitle d-flex justify-content-between align-items-center mb-4">
      <h1 class="fw-bold text-primary"><i class="fa-solid fa-user-tie me-2"></i>Agregar Proveedor</h1>
    </div>
    <section class="section">
      <div class="row justify-content-center">
        <div class="col-lg-7 col-xl-6">
          <div class="card shadow border-0">
            <div class="card-header bg-primary text-white">
              <h5 class="card-title mb-0"><i class="fa-solid fa-user-plus me-2"></i>Agregar Nuevo Proveedor</h5>
            </div>
            <div class="card-body bg-white">
              <form action="{{route('proveedores.store')}}" method="POST">
                @csrf
                <div class="mb-3">
                    <label for="nombre" class="form-label">Nombre del Proveedor</label>
                    <input type="text" class="form-control" required name="nombre" id="nombre">
                </div>
                <div class="mb-3">
                    <label for="comp_domicilio" class="form-label">Comprobante de Domicilio</label>
                    <input type="text" class="form-control" required name="comp_domicilio" id="comp_domicilio">
                </div>
                <div class="mb-3">
                    <label for="ine" class="form-label">INE</label>
                    <input type="text" class="form-control" required name="ine" id="ine">
                </div>
                <div class="mb-3">
                    <label for="acta_constitutiva" class="form-label">Acta Constitutiva</label>
                    <input type="text" class="form-control" required name="acta_constitutiva" id="acta_constitutiva">
                </div>
                <div class="mb-3">
                    <label for="rfc" class="form-label">RFC</label>
                    <input type="text" class="form-control" required name="rfc" id="rfc">
                </div>
                <div class="mb-4">
                    <label for="direccion" class="form-label">Dirección</label>
                    <input type="text" class="form-control" required name="direccion" id="direccion">
                </div>
                <div class="d-flex justify-content-start gap-2">
                    <button class="btn btn-primary">
                        <i class="fa-solid fa-save me-2"></i> Guardar
                    </button>
                    <a href="{{route('proveedores')}}" class="btn btn-secondary">
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