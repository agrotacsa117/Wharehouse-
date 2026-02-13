<aside id="sidebar" class="sidebar bg-white shadow-sm border-end">
    <ul class="sidebar-nav" id="sidebar-nav">

        <li class="nav-item mb-2">
            <a class="nav-link {{ request()->routeIs('home') ? '' : 'collapsed' }}" href="{{ route('home') }}" style="background-color:#dc3545 !important;">
                <i class="bi bi-grid align-middle me-2 fs-5"></i>
                <span class="align-middle fw-bold">Inicio</span>
            </a>
        </li>
        
        @auth
        @if(in_array(auth()->user()->rol, ['admin', 'tapachula', 'bodega_dorado']))
        <li class="nav-item mb-2">
            <a class="nav-link {{ request()->routeIs('ventas-nueva','reporte.salidas') ? '' : 'collapsed' }}" data-bs-target="#salidas-nav" data-bs-toggle="collapse"  href="#">
                <i class="fa-solid fa-truck-fast align-middle me-2 fs-5"></i>
                <span class="align-middle fw-bold">Salida del Almacen</span>
                <i class="bi bi-chevron-down ms-auto align-middle"></i>
            </a>
            <ul id="salidas-nav" class="nav-content collapse {{ request()->routeIs('ventas-nueva','reporte.salidas') ? 'show' : '' }}" data-bs-parent="#sidebar-nav">
                <li>
                    <a href="{{ route('salida-productos') }}" class="{{ request()->routeIs('salida-productos') ? 'active' : '' }}">
                        <span class="sub-item-text">Salida de Productos</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('reporte.salidas') }}" class="{{ request()->routeIs('reporte.salidas') ? 'active' : '' }}">
                        <span class="sub-item-text">Reporte de Salidas</span>
                    </a>
                </li>
            </ul>
        </li>
        @endif

        @if(auth()->user()->rol === 'admin')
        <li class="nav-item mb-2">
            <a class="nav-link {{ request()->routeIs('categorias.index') ? '' : 'collapsed' }}" href="{{ route('categorias.index') }}">
                <i class="fa-solid fa-layer-group align-middle me-2 fs-5"></i>
                <span class="align-middle fw-bold">Categorías</span>
            </a>
        </li>
        <li class="nav-item mb-2">
            <a class="nav-link {{ request()->routeIs('rack.index') ? '' : 'collapsed' }}" href="{{ route('rack.index') }}">
                <i class="fa-solid fa-boxes-stacked align-middle me-2 fs-5"></i>
                <span class="align-middle fw-bold">Asignar Rack</span>
            </a>
        </li>
        @endif
        @endauth

        @php
            // Determinar si el grupo de Productos debe mostrarse.
            // Se muestra si el usuario es admin, tapachula o bodega_dorado,
            // ya que estos roles tienen acceso a al menos un sub-ítem de productos.
            $showProductGroup = in_array(auth()->user()->rol, ['admin', 'tapachula', 'bodega_dorado']);
            $productRelatedRoutes = ['productos', 'productos.vencer', 'reportes_productos'];
        @endphp

        @if($showProductGroup)
        <li class="nav-item mb-2">
            <a class="nav-link {{ request()->routeIs(...$productRelatedRoutes) ? '' : 'collapsed' }}" data-bs-target="#productos-nav" data-bs-toggle="collapse" href="#">
                <i class="fa-solid fa-basket-shopping align-middle me-2 fs-5"></i>
                <span class="align-middle fw-bold">Productos</span>
                <i class="bi bi-chevron-down ms-auto align-middle"></i>
            </a>
            <ul id="productos-nav" class="nav-content collapse {{ request()->routeIs(...$productRelatedRoutes) ? 'show' : '' }}" data-bs-parent="#sidebar-nav">
                {{-- Sub-item visible para admin, tapachula, bodega_dorado --}}
                @if(in_array(auth()->user()->rol, ['admin', 'tapachula', 'bodega_dorado']))
                <li>
                    <a href="{{ route('productos') }}" class="{{ request()->routeIs('productos') ? 'active' : '' }}">
                        <span class="sub-item-text">Administrar Productos</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('productos.vencer') }}" class="{{ request()->routeIs('productos.vencer') ? 'active' : '' }}">
                        <span class="sub-item-text">Productos a Punto de Vencer</span>
                    </a>
                </li>
                @endif
                
                {{-- Sub-item visible solo para admin --}}
                @if(auth()->user()->rol === 'admin')
                <li>
                    <a href="{{ route('reportes_productos') }}" class="{{ request()->routeIs('reportes_productos') ? 'active' : '' }}">
                        <span class="sub-item-text">Reporte de Productos</span>
                    </a>
                </li>
                @endif
            </ul>
        </li>
        @endif

        @if(auth()->user()->rol === 'admin')
        <li class="nav-item mb-2">
            <a class="nav-link {{ request()->routeIs('proveedores') ? '' : 'collapsed' }}" href="{{ route('proveedores') }}">
                <i class="fa-solid fa-truck align-middle me-2 fs-5"></i> {{-- Considera fa-truck-ramp-box o bi-person-rolodex --}}
                <span class="align-middle fw-bold">Proveedores</span>
            </a>
        </li>
        @endif

        @if(auth()->user()->rol === 'admin')
        <li class="nav-item mb-2">
            <a class="nav-link {{ request()->routeIs('usuarios') ? '' : 'collapsed' }}" href="{{ route('usuarios') }}">
                <i class="fa-solid fa-users align-middle me-2 fs-5"></i> {{-- Considera fa-users-cog o bi-people-fill --}}
                <span class="align-middle fw-bold">Usuarios</span>
            </a>
        </li>
        @endif

        @if(auth()->user()->rol === 'admin')
        <li class="nav-item mb-2">
            <a class="nav-link" href="{{ route('warehouses.create') }}">
                <i class="fa-solid fa-users align-middle me-2 fs-5"></i> {{-- Considera fa-users-cog o bi-people-fill --}}
                <span class="align-middle fw-bold">Registrar almacén</span>
            </a>
        </li>
        @endif
    </ul>
</aside>