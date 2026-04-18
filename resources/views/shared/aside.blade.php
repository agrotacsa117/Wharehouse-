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


        @php
            $warehouseRelatedRoutes = [
                'warehouses.create',
                'location.store',
                'warehouse-type.get',
                'warehouse-managment.get',
            ];
        @endphp

        <li class="nav-item mb-2">
            <a class="nav-link {{ request()->routeIs(...$warehouseRelatedRoutes) ? '' : 'collapsed' }}"
                data-bs-target="#almacen-nav" data-bs-toggle="collapse" href="#">

                <i class="fa-solid fa-warehouse align-middle me-2 fs-5"></i>
                <span class="align-middle fw-bold">Gestión de Almacén</span>
                <i class="bi bi-chevron-down ms-auto align-middle"></i>
            </a>

            <ul id="almacen-nav"
                class="nav-content collapse {{ request()->routeIs(...$warehouseRelatedRoutes) ? 'show' : '' }}"
                data-bs-parent="#sidebar-nav">

                {{-- Registrar almacén --}}
                @if (auth()->user()->rol === 'admin')
                    <li>
                        <a href="{{ route('warehouses.create') }}"
                            class="{{ request()->routeIs('warehouses.create') ? 'active' : '' }}">
                            <span class="sub-item-text">Registrar almacén</span>
                        </a>
                    </li>
                @endif

                {{-- Registrar ubicación --}}
                @if (auth()->user()->rol === 'admin')
                    <li>
                        <a href="{{ route('location.store') }}"
                            class="{{ request()->routeIs('location.store') ? 'active' : '' }}">
                            <span class="sub-item-text">Registrar ubicación</span>
                        </a>
                    </li>
                @endif

                {{-- Registrar tipo de almacén --}}
                @if (auth()->user()->rol === 'admin')
                    <li>
                        <a href="{{ route('warehouse-type.get') }}"
                            class="{{ request()->routeIs('warehouse-type.get') ? 'active' : '' }}">
                            <span class="sub-item-text">Registrar tipo de almacén</span>
                        </a>
                    </li>
                @endif

                @if (auth()->user()->rol === 'admin')
                    <li>
                        <a href="{{ route('warehouse-managment.get') }}"
                            class="{{ request()->routeIs('warehouse-managment.get') ? 'active' : '' }}">
                            <span class="sub-item-text">Administrar almacén</span>
                        </a>
                    </li>
                @endif

                @if (in_array(auth()->user()->rol, ['admin', 'tapachula', 'bodega_dorado']))
                    <li>
                        <a href="{{ route('inventory.management') }}"
                            class="{{ request()->routeIs('inventory.management') ? 'active' : '' }}">
                            <span class="sub-item-text">Gestión de Inventario</span>
                        </a>
                    </li>
                @endif

            </ul>


          

          @if (auth()->user()->rol === 'admin')
        <li class="nav-item mb-2">
            <a class="nav-link {{ request()->routeIs('warehouse-movements.get') ? '' : 'collapsed' }}"
                href="{{ route('warehouse-movements.get') }}">
                <i class="fa-solid fa-right-left align-middle me-2 fs-5"></i> {{-- Considera fa-truck-ramp-box o bi-person-rolodex --}}
                <span class="align-middle fw-bold">Movimientos</span>
            </a>
        </li>
        @endif
        </li>


    </ul>
</aside>
