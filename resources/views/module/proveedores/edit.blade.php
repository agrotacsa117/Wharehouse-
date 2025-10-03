@extends('layouts.main')
@section('titulo', $titulo)

@section('contenido')
<main id="main" class="main bg-light py-4">
    <div class="pagetitle mb-4">
      <h1 class="fw-bold text-primary"><i class="fa-solid fa-truck me-2"></i>Editar Proveedor</h1>
    </div>
    <section class="section">
      <div class="row justify-content-center">
        <div class="col-lg-7">
          <div class="card shadow border-0">
            <div class="card-body p-4">
              <h5 class="card-title mb-4 text-secondary"><i class="fa-solid fa-user-gear me-2"></i>Datos del Proveedor</h5>
              <form action="{{route('proveedores.update', $items->id)}}" method="POST" autocomplete="off">
                @csrf
                @method('PUT')
                <div class="mb-3">
                  <label for="nombre" class="form-label">Nombre del Proveedor</label>
                  <input type="text" class="form-control" required name="nombre" id="nombre" value="{{$items->nombre}}">
                </div>
                <div class="mb-3">
                  <label for="comp_domicilio" class="form-label">Comprobante de Domicilio</label>
                  <input type="text" class="form-control" required name="comp_domicilio" id="comp_domicilio" value="{{$items->comp_domicilio}}">
                </div>
                <div class="mb-3">
                  <label for="ine" class="form-label">INE</label>
                  <input type="text" class="form-control" required name="ine" id="ine" value="{{$items->ine}}">
                </div>
                <div class="mb-3">
                  <label for="acta_constitutiva" class="form-label">Acta Constitutiva</label>
                  <input type="text" class="form-control" required name="acta_constitutiva" id="acta_constitutiva" value="{{$items->acta_constitutiva}}">
                </div>
                <div class="mb-3">
                  <label for="rfc" class="form-label">RFC</label>
                  <input type="text" class="form-control" required name="rfc" id="rfc" value="{{$items->rfc}}">
                </div>
                <div class="mb-3">
                  <label for="direccion" class="form-label">Dirección</label>
                  <input type="text" class="form-control" required name="direccion" id="direccion" value="{{$items->direccion}}">
                </div>
                <div class="d-flex gap-2 mt-4">
                  <button class="btn btn-success flex-fill"><i class="fa-solid fa-save me-2"></i>Actualizar</button>
                  <a href="{{route('proveedores')}}" class="btn btn-outline-secondary flex-fill"><i class="fa-solid fa-arrow-left me-2"></i>Cancelar</a>
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>
    </section>
</main>
@endsection