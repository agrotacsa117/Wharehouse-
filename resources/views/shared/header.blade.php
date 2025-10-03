<header id="header" class="header fixed-top d-flex align-items-center bg-primary shadow-lg border-bottom border-3 border-info"  style="min-height:70px; background-color:#dc3545 !important;" >
  <div class="container-fluid px-4" >
    <div class="d-flex align-items-center justify-content-between w-100" >
      <a href="{{ route('home') }}" class="logo d-flex align-items-center text-decoration-none">
        <img src="{{ asset('imagen/logo.png') }}" alt="Logo Banco de Alimentos" class="img-fluid me-3" style="max-height: 48px; filter: drop-shadow(0 2px 6px rgba(0, 0, 0, 0.12));">
        <span class="d-none d-lg-block fw-bold text-white fs-4 text-shadow" style="letter-spacing:1px; ">TACSA</span>
      </a>
      <i class="bi bi-list toggle-sidebar-btn"></i>
      <nav class="header-nav ms-auto">
        <ul class="d-flex align-items-center mb-0 gap-3">
          <li class="nav-item dropdown pe-2">
            <a class="nav-link nav-profile d-flex align-items-center pe-3 text-white bg-success bg-gradient rounded-4 shadow-sm px-3 py-2 fw-bold transition-all" href="#" data-bs-toggle="dropdown" style="font-size:1.1rem;">
              <i class="fa-solid fa-id-card-clip me-2 fs-5"></i>
              <span class="d-none d-md-block dropdown-toggle ps-2">{{Auth::user()->name}}</span>
            </a>
            <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow profile p-0 overflow-hidden shadow-lg border-0 mt-2" style="min-width: 260px;">
              <li class="dropdown-header text-center bg-light bg-gradient py-3 rounded-top-3 border-bottom">
                <h6 class="mb-0 text-primary fw-bold" style="font-size:1.1rem; letter-spacing:0.5px;">{{Auth::user()->name}}</h6>
                <span class="text-secondary small">
                  @php
                    $rol = Auth::user()->rol;
                    if($rol === 'admin') {
                      echo 'ADMINISTRADOR';
                    } elseif($rol === 'tapachula') {
                      echo 'BODEGA TAPACHULA';
                    } elseif($rol === 'bodega_dorado') {
                      echo 'BODEGA DORADO';
                    } else {
                      echo $rol;
                    }
                  @endphp
                </span>
              </li>
              <li><hr class="dropdown-divider my-1"></li>
              <li>
                <a class="dropdown-item d-flex align-items-center bg-danger bg-gradient text-white fw-bold rounded-3 px-3 py-2" href="{{route('logout')}}">
                  <i class="bi bi-box-arrow-right me-2 fs-5"></i>
                  <span class="fs-6">Cerrar Sesión</span>
                </a>
              </li>
            </ul>
          </li>
        </ul>
      </nav>
    </div>
  </div>
</header>