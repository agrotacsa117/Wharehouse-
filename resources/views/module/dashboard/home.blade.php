@extends('layouts.main')

@section('titulo', $titulo)

@section('contenido')
<main id="main" class="main bg-light py-4">
  <div class="pagetitle mb-4">
    <h1 class="fw-bold text-primary" style="letter-spacing:1px;"><i class="fa-solid fa-gauge-high me-2"></i>{{ $titulo }}</h1>
    <nav>
      <ol class="breadcrumb bg-white rounded shadow-sm px-3 py-2">
        <li class="breadcrumb-item"><a href="{{ route('home') }}">Inicio</a></li>
        <li class="breadcrumb-item active">Panel Principal</li>
      </ol>
    </nav>
  </div>
  <section class="section dashboard">
    @php
      $user = auth()->user();
      $esAdmin = $user->rol === 'admin';
      $esTapachula = $user->rol === 'tapachula';
      $esDorado = $user->rol === 'bodega_dorado';
    @endphp
    <div class="row mb-4 justify-content-center">
      @if($esAdmin)
        <!-- Solo admin ve las tarjetas de precios individuales -->
        <div class="col-xxl-4 col-md-6 mb-4">
          <x-dashboard.card 
            icon="fa-solid fa-dollar-sign" 
            title="Precio Total Dorado" 
            :value="'$ ' . number_format($precioTotalDorado, 2)" 
            iconBg="bg-success" 
            valueClass="text-success" />
        </div>
        <div class="col-xxl-4 col-md-6 mb-4">
          <x-dashboard.card 
            icon="fa-solid fa-dollar-sign" 
            title="Precio Total Tapachula" 
            :value="'$ ' . number_format($precioTotalTapachula, 2)" 
            iconBg="bg-success" 
            valueClass="text-success" />
        </div>
      @endif
      <!-- Solo admin y otros roles distintos a tapachula y bodega_dorado ven el total general -->
      @if($esAdmin || (!$esTapachula && !$esDorado))
      <div class="col-xxl-4 col-md-12 mb-4">
        <x-dashboard.card 
          icon="fa-solid fa-sack-dollar" 
          title="Precio Total General" 
          :value="'$ ' . number_format($precioTotalGeneral, 2)" 
          iconBg="bg-success" 
          valueClass="text-success" />
      </div>
      @endif
    </div>
    <div class="row">
      <div class="col-lg-12">
        <div class="row">
          @if($esAdmin || $esTapachula)
          <!-- Card 1: Total Productos Bodega Tapachula -->
          <div class="col-xxl-3 col-md-6 mb-4">
            <div class="card info-card sales-card shadow border-0">
              <div class="card-body">
                <h5 class="card-title">Productos en <span>| Bodega Tapachula</span></h5>
                <div class="d-flex align-items-center">
                  <div class="card-icon rounded-circle d-flex align-items-center justify-content-center bg-primary text-white">
                    <i class="bi bi-box-seam"></i>
                  </div>
                  <div class="ps-3">
                    <h6 class="fw-bold text-primary">{{ number_format($totalTapachula, 0, ',', '.') }}</h6>
                    @if($totalTapachula > 0)
                      @if(isset($cambioTapachula) && $cambioTapachula > 0)
                        <span class="text-success small pt-1 fw-bold">+{{ $cambioTapachula }}%</span>
                        <span class="text-muted small pt-2 ps-1">vs mes anterior</span>
                      @elseif(isset($cambioTapachula) && $cambioTapachula < 0)
                        <span class="text-danger small pt-1 fw-bold">{{ $cambioTapachula }}%</span>
                        <span class="text-muted small pt-2 ps-1">vs mes anterior</span>
                      @else
                        <span class="text-muted small pt-1 fw-bold">Sin cambios</span>
                      @endif
                    @else
                      <span class="text-muted small pt-1 fw-bold">Sin datos previos</span>
                    @endif
                  </div>
                </div>
              </div>
            </div>
          </div>
          @endif

          @if($esAdmin || $esDorado)
          <!-- Card 2: Total Productos Bodega Dorado -->
          <div class="col-xxl-3 col-md-6 mb-4">
            <div class="card info-card revenue-card shadow border-0">
              <div class="card-body">
                <h5 class="card-title">Productos en <span>| Bodega Dorado</span></h5>
                <div class="d-flex align-items-center">
                  <div class="card-icon rounded-circle d-flex align-items-center justify-content-center bg-primary text-white">
                    <i class="bi bi-box-seam"></i>
                  </div>
                  <div class="ps-3">
                    <h6 class="fw-bold text-primary">{{ number_format($totalDorado, 0, ',', '.') }}</h6>
                    @if(isset($cambioDorado))
                      @if($cambioDorado > 0)
                        <span class="text-success small pt-1 fw-bold">+{{ $cambioDorado }}%</span>
                        <span class="text-muted small pt-2 ps-1">vs mes anterior</span>
                      @elseif($cambioDorado < 0)
                        <span class="text-danger small pt-1 fw-bold">{{ $cambioDorado }}%</span>
                        <span class="text-muted small pt-2 ps-1">vs mes anterior</span>
                      @else
                        <span class="text-muted small pt-1 fw-bold">Sin cambios</span>
                      @endif
                    @else
                      <span class="text-muted small pt-1 fw-bold">Sin datos previos</span>
                    @endif
                  </div>
                </div>
              </div>
            </div>
          </div>
          @endif

          @if($esAdmin)
          <!-- Card 3: Total General de Productos (solo admin) -->
          <div class="col-xxl-3 col-md-6 mb-4">
            <x-dashboard.card 
              icon="bi bi-boxes" 
              title="Total de | Productos General" 
              :value="number_format($totalGeneral, 0, ',', '.')"
              iconBg="bg-primary"
              valueClass="text-primary"
              :subtitle="'Tapachula: <span class=\'fw-bold\'>' . number_format($totalTapachula, 0, ',', '.') . '</span><br>Dorado: <span class=\'fw-bold\'>' . number_format($totalDorado, 0, ',', '.') . '</span>'"
            />
          </div>
          @endif

          {{-- ------------------------------------------------------------- --}}
          {{--       MEJORA VISUAL PARA LAS TARJETAS "POR VENCER"            --}}
          {{-- ------------------------------------------------------------- --}}

          <div class="w-100"></div>
          @if($esAdmin || $esTapachula)
          <!-- Card 4: Por Vencer Bodega Tapachula (MEJORADO) -->
          <div class="col-xxl-3 col-md-6 mb-4">
            <x-dashboard.card-progress 
              icon="bi bi-calendar-x-fill"
              title="Por Vencer | B. Tapachula"
              :dangerValue="number_format($porVencerYVencidosTapachula, 0, ',', '.')"
              :successValue="number_format($totalTapachula - $porVencerYVencidosTapachula, 0, ',', '.')"
              dangerLabel="Por vencer + vencidos"
              successLabel="Vigentes"
              :dangerPercent="$porcentajePorVencerTapachulaBarra"
              :total="$totalTapachula"
            />
          </div>
          @endif

          @if($esAdmin || $esDorado)
          <!-- Card 5: Por Vencer Bodega Dorado (MEJORADO) -->
          <div class="col-xxl-3 col-md-6 mb-4">
            <x-dashboard.card-progress 
              icon="bi bi-calendar-x-fill"
              title="Por Vencer | B. Dorado"
              :dangerValue="number_format($porVencerYVencidosDorado, 0, ',', '.')"
              :successValue="number_format($totalDorado - $porVencerYVencidosDorado, 0, ',', '.')"
              dangerLabel="Por vencer + vencidos"
              successLabel="Vigentes"
              :dangerPercent="$porcentajePorVencerDoradoBarra"
              :total="$totalDorado"
            />
          </div>
          @endif

          <div class="w-100"></div>
          @if($esAdmin || $esTapachula)
          <!-- Tarjeta de racks Tapachula (circular) -->
          <div class="col-xxl-3 col-md-6 mb-4">
            <x-dashboard.circular-rack 
              title="Racks | Tapachula"
              :percent="$porcentajeOcupacionTapachula"
              :ocupados="$ocupacionTapachula"
              :max="$capacidadMaxTapachula"
              :color="$porcentajeOcupacionTapachula >= 80 ? 'danger' : 'info'"
              usedLabel="Ocupado"
              freeLabel="Disponible"
            />
          </div>
          @endif

          @if($esAdmin || $esDorado)
          <!-- Tarjeta de racks Dorado (circular) -->
          <div class="col-xxl-3 col-md-6 mb-4">
            <x-dashboard.circular-rack 
              title="Racks | Dorado"
              :percent="$porcentajeOcupacionDorado"
              :ocupados="$ocupacionDorado"
              :max="$capacidadMaxDorado"
              :color="$porcentajeOcupacionDorado >= 80 ? 'danger' : 'warning'"
              usedLabel="Ocupado"
              freeLabel="Disponible"
            />
          </div>
          @endif
        </div>
      </div>
    </div>
  </section>
</main>
@endsection