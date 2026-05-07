<aside id="sidebar" class="sidebar bg-white shadow-sm border-end">
    <ul class="sidebar-nav" id="sidebar-nav">

        <li class="nav-item mb-2">
            <a class="nav-link {{ request()->routeIs('home') ? '' : 'collapsed' }}" href="{{ route('home') }}"
                style="background-color:#dc3545 !important;">
                <i class="bi bi-grid align-middle me-2 fs-5"></i>
                <span class="align-middle fw-bold">Inicio</span>
            </a>
        </li>



        @php
            // Determinar si el grupo de Productos debe mostrarse.
            // Se muestra si el usuario es admin, tapachula o bodega_dorado,
            // ya que estos roles tienen acceso a al menos un sub-ítem de productos.
            $showProductGroup = in_array(auth()->user()->rol, ['admin', 'tapachula', 'bodega_dorado']);
            $productRelatedRoutes = ['productos', 'productos.vencer', 'reportes_productos'];
        @endphp



        @if (auth()->user()->rol === 'admin')
            <li class="nav-item mb-2">
                <a class="nav-link {{ request()->routeIs('usuarios') ? '' : 'collapsed' }}"
                    href="{{ route('usuarios') }}">
                    <i class="fa-solid fa-users align-middle me-2 fs-5"></i> {{-- Considera fa-users-cog o bi-people-fill --}}
                    <span class="align-middle fw-bold">Usuarios</span>
                </a>
            </li>
        @endif




        @if (auth()->user()->rol === 'admin')
            <li class="nav-item mb-2">
                <a class="nav-link {{ request()->routeIs('warehouse-managment.get') ? '' : 'collapsed' }}"
                    href="{{ route('warehouse-managment.get') }}">
                    <i class="fa-solid fa-warehouse align-middle me-2 fs-5"></i> {{-- Considera fa-truck-ramp-box o bi-person-rolodex --}}
                    <span class="align-middle fw-bold">Bodegas</span>
                </a>
            </li>
        @endif





        @if (auth()->user()->rol === 'admin')
            <li class="nav-item mb-2">
                <a class="nav-link {{ request()->routeIs('warehouse-movements.get') ? '' : 'collapsed' }}"
                    href="{{ route('warehouse-movements.get') }}">
                    <i class="fa-solid fa-right-left align-middle me-2 fs-5"></i> {{-- Considera fa-truck-ramp-box o bi-person-rolodex --}}
                    <span class="align-middle fw-bold">Movimientos</span>
                </a>
            </li>
        @endif

        @if (auth()->user()->rol === 'admin')
            <li class="nav-item mb-2">
                <a class="nav-link {{ request()->routeIs('reports.get') ? '' : 'collapsed' }}"
                    href="{{ route('reports.get') }}">
                    <i class="fa-solid fa-chart-bar align-middle me-2 fs-5"></i> {{-- Considera fa-truck-ramp-box o bi-person-rolodex --}}
                    <span class="align-middle fw-bold">Reportes</span>
                </a>
            </li>
        @endif
        </li>


    </ul>
</aside>
