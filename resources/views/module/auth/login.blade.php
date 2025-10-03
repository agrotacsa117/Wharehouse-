@extends('layouts.login')
@section('titulo',  $titulo )
    
@section('contenido')

<main class="bg-light min-vh-100 d-flex flex-column justify-content-center align-items-center py-4">
  <div class="container">
    <section class="section register d-flex flex-column align-items-center justify-content-center py-4">
      <div class="row justify-content-center">
        <div class="col-lg-6 col-md-8 col-12 d-flex flex-column align-items-center justify-content-center"> <!-- Aumenta el ancho -->
          <div class="d-flex justify-content-center py-4">
            <a href="#" class="logo d-flex align-items-center w-auto text-decoration-none">
              <img src="{{asset('imagen/logo.png')}}" alt="Logo BAMX" class="img-fluid me-2" style="max-height: 130px;"> <!-- Logo más grande -->
            </a>
          </div>
          <div class="card shadow border-0 mb-3 w-100">
            <div class="card-body p-5 bg-white"> <!-- Padding más grande -->
              <div class="pt-2 pb-3">
                <h5 class="card-title text-center pb-0 fs-2 text-primary"><i class="fa-solid fa-right-to-bracket me-2"></i>Iniciar Sesión</h5> <!-- Título más grande -->
                <p class="text-center small fs-5">Ingrese su correo y  contraseña</p> <!-- Texto más grande -->
              </div>
              <form class="row g-4 needs-validation" novalidate method="POST" action="{{route('logear')}}"> <!-- Espaciado mayor -->
                @csrf
                <div class="col-12">
                  <label for="email" class="form-label fs-5">Correo</label>
                  <div class="input-group has-validation">
                    <span class="input-group-text bg-light fs-5"><i class="fa-solid fa-envelope"></i></span>
                    <input type="text" name="email" class="form-control form-control-lg" id="email" required placeholder="usuario@server.dominio">
                    <div class="invalid-feedback">Escribe tu correo!</div>
                  </div>
                </div>
                <div class="col-12">
                  <label for="password" class="form-label fs-5">Contraseña</label>
                  <div class="input-group has-validation">
                    <span class="input-group-text bg-light fs-5"><i class="fa-solid fa-lock"></i></span>
                    <input type="password" name="password" class="form-control form-control-lg" id="password" required placeholder="contraseña">
                    <div class="invalid-feedback">Ingresa tu contraseña correcta!</div>
                  </div>
                </div>
                <div class="col-12">
                  <button class="btn btn-primary w-100 fw-bold btn-lg" type="submit">
                    <i class="fa-solid fa-arrow-right-to-bracket me-2"></i>Iniciar Sesión
                  </button>
                </div>
              </form>
              <div class="mt-4">
                @if($errors->any())
                  <div class="alert alert-danger p-2">
                    <ul class="mb-0 ps-3">
                      @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                      @endforeach
                    </ul>
                  </div>
                @endif
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
  </div>
</main>
    
@endsection

