<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>TACSA - Gestion de Almacenes</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --tacsa-red: #DC2626;
            --tacsa-red-dark: #B91C1C;
            --tacsa-red-light: rgba(220, 38, 38, 0.08);
            --tacsa-red-light2: rgba(220, 38, 38, 0.15);
            --tacsa-green: #16a34a;
            --tacsa-green-light: rgba(22, 163, 74, 0.1);
            --tacsa-amber: #d97706;
            --tacsa-amber-light: rgba(217, 119, 6, 0.1);
            --tacsa-blue: #2563eb;
            --tacsa-blue-light: rgba(37, 99, 235, 0.1);
            --text-primary: #1a1a1a;
            --text-secondary: #6b7280;
            --border-color: #e5e7eb;
            --bg-body: #f4f4f5;
            --bg-card: #ffffff;
        }

        * {
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
            background-color: var(--bg-body);
            color: var(--text-primary);
            min-height: 100vh;
            margin: 0;
        }

        /* ══════════════════════════════════
           TOP BAR
        ══════════════════════════════════ */
        .topbar {
            background: var(--tacsa-red);
            padding: 0.75rem 2rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .topbar-brand {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            color: #ffffff;
            font-weight: 700;
            font-size: 1.125rem;
            text-decoration: none;
        }

        .topbar-brand .logo-sm {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            border: 2px solid #ffffff;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.625rem;
            font-weight: 700;
            color: #ffffff;
        }

        .topbar-nav {
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .topbar-nav a {
            color: rgba(255, 255, 255, 0.8);
            text-decoration: none;
            font-size: 0.8125rem;
            font-weight: 500;
            padding: 0.375rem 0.75rem;
            border-radius: 6px;
            transition: all 0.15s;
        }

        .topbar-nav a:hover,
        .topbar-nav a.active {
            color: #ffffff;
            background: rgba(255, 255, 255, 0.15);
        }

        /* ══════════════════════════════════
           PAGE WRAPPER
        ══════════════════════════════════ */
        .page-wrapper {
            max-width: 1100px;
            margin: 0 auto;
            padding: 2rem 1.5rem 3rem;
        }

        /* ══════════════════════════════════
           PAGE HEADER
        ══════════════════════════════════ */
        .page-header {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 1rem;
            margin-bottom: 1.75rem;
        }

        .page-header h1 {
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--text-primary);
            margin: 0;
        }

        .page-header p {
            font-size: 0.875rem;
            color: var(--text-secondary);
            margin: 0.25rem 0 0;
        }

        .btn-new-warehouse {
            height: 42px;
            padding: 0 1.25rem;
            font-size: 0.8125rem;
            font-weight: 600;
            border-radius: 8px;
            border: none;
            background: var(--tacsa-red);
            color: #ffffff;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            transition: background-color 0.15s;
            cursor: pointer;
            text-decoration: none;
        }

        .btn-new-warehouse:hover {
            background: var(--tacsa-red-dark);
            color: #ffffff;
        }

        /* ══════════════════════════════════
           STAT CARDS
        ══════════════════════════════════ */
        .stat-cards {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 1rem;
            margin-bottom: 1.75rem;
        }

        .stat-card {
            background: var(--bg-card);
            border: 1px solid var(--border-color);
            border-radius: 10px;
            padding: 1.25rem;
            display: flex;
            align-items: flex-start;
            gap: 1rem;
        }

        .stat-icon {
            width: 44px;
            height: 44px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.25rem;
            flex-shrink: 0;
        }

        .stat-icon.red {
            background: var(--tacsa-red-light);
            color: var(--tacsa-red);
        }

        .stat-icon.green {
            background: var(--tacsa-green-light);
            color: var(--tacsa-green);
        }

        .stat-icon.amber {
            background: var(--tacsa-amber-light);
            color: var(--tacsa-amber);
        }

        .stat-icon.blue {
            background: var(--tacsa-blue-light);
            color: var(--tacsa-blue);
        }

        .stat-info h3 {
            font-size: 1.375rem;
            font-weight: 700;
            margin: 0;
            line-height: 1;
        }

        .stat-info span {
            font-size: 0.75rem;
            color: var(--text-secondary);
        }

        /* ══════════════════════════════════
           TABLE CARD
        ══════════════════════════════════ */
        .table-card {
            background: var(--bg-card);
            border: 1px solid var(--border-color);
            border-radius: 12px;
            overflow: hidden;
        }

        /* ── Toolbar ── */
        .table-toolbar {
            padding: 1.25rem 1.5rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 1rem;
            flex-wrap: wrap;
            border-bottom: 1px solid var(--border-color);
        }

        .table-toolbar .section-title {
            display: flex;
            align-items: center;
            gap: 0.625rem;
            margin: 0;
        }

        .table-toolbar .section-title .bar {
            width: 4px;
            height: 22px;
            background: var(--tacsa-red);
            border-radius: 9999px;
            flex-shrink: 0;
        }

        .table-toolbar .section-title h2 {
            font-size: 1rem;
            font-weight: 600;
            color: var(--tacsa-red);
            margin: 0;
        }

        .toolbar-actions {
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .search-box {
            position: relative;
        }

        .search-box i {
            position: absolute;
            left: 0.875rem;
            top: 50%;
            transform: translateY(-50%);
            color: var(--text-secondary);
            font-size: 0.875rem;
        }

        .search-box input {
            height: 38px;
            width: 260px;
            padding-left: 2.5rem;
            padding-right: 1rem;
            border-radius: 8px;
            border: 1px solid var(--border-color);
            font-size: 0.8125rem;
            color: var(--text-primary);
            background: var(--bg-card);
            transition: border-color 0.2s, box-shadow 0.2s;
        }

        .search-box input::placeholder {
            color: #9ca3af;
        }

        .search-box input:focus {
            border-color: var(--tacsa-red);
            box-shadow: 0 0 0 3px rgba(220, 38, 38, 0.1);
            outline: none;
        }

        .filter-select {
            height: 38px;
            border-radius: 8px;
            border: 1px solid var(--border-color);
            font-size: 0.8125rem;
            padding: 0 2rem 0 0.75rem;
            color: var(--text-primary);
            background: var(--bg-card);
            transition: border-color 0.2s;
            cursor: pointer;
        }

        .filter-select:focus {
            border-color: var(--tacsa-red);
            box-shadow: 0 0 0 3px rgba(220, 38, 38, 0.1);
            outline: none;
        }

        /* ── Table ── */
        .table-wrapper {
            overflow-x: auto;
        }

        .tacsa-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 0.8125rem;
        }

        .tacsa-table thead th {
            background: #fafafa;
            padding: 0.875rem 1rem;
            font-weight: 600;
            font-size: 0.75rem;
            text-transform: uppercase;
            letter-spacing: 0.04em;
            color: var(--text-secondary);
            border-bottom: 1px solid var(--border-color);
            white-space: nowrap;
        }

        .tacsa-table tbody td {
            padding: 1rem;
            border-bottom: 1px solid #f3f4f6;
            vertical-align: middle;
        }

        .tacsa-table tbody tr {
            transition: background 0.1s;
        }

        .tacsa-table tbody tr:hover {
            background: #fafafa;
        }

        .tacsa-table tbody tr:last-child td {
            border-bottom: none;
        }

        /* Cell helpers */
        .cell-key {
            font-weight: 600;
            color: var(--tacsa-red);
        }

        .cell-name {
            font-weight: 500;
        }

        .badge-type {
            display: inline-block;
            padding: 0.25rem 0.625rem;
            font-size: 0.6875rem;
            font-weight: 600;
            border-radius: 9999px;
            background: var(--tacsa-red-light);
            color: var(--tacsa-red);
        }

        .badge-active {
            display: inline-flex;
            align-items: center;
            gap: 0.375rem;
            padding: 0.25rem 0.625rem;
            font-size: 0.6875rem;
            font-weight: 600;
            border-radius: 9999px;
            background: var(--tacsa-green-light);
            color: var(--tacsa-green);
        }

        .badge-active .dot {
            width: 6px;
            height: 6px;
            border-radius: 50%;
            background: var(--tacsa-green);
        }

        .badge-inactive {
            display: inline-flex;
            align-items: center;
            gap: 0.375rem;
            padding: 0.25rem 0.625rem;
            font-size: 0.6875rem;
            font-weight: 600;
            border-radius: 9999px;
            background: #f3f4f6;
            color: var(--text-secondary);
        }

        .badge-inactive .dot {
            width: 6px;
            height: 6px;
            border-radius: 50%;
            background: var(--text-secondary);
        }

        /* Action buttons in table */
        .action-btn {
            width: 34px;
            height: 34px;
            border-radius: 8px;
            border: 1px solid var(--border-color);
            background: var(--bg-card);
            color: var(--text-secondary);
            display: inline-flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.15s;
            font-size: 0.875rem;
        }

        .action-btn:hover {
            border-color: #d1d5db;
            background: #f9fafb;
        }

        .action-btn.edit:hover {
            color: var(--tacsa-blue);
            border-color: var(--tacsa-blue);
            background: var(--tacsa-blue-light);
        }

        .action-btn.delete:hover {
            color: var(--tacsa-red);
            border-color: var(--tacsa-red);
            background: var(--tacsa-red-light);
        }

        .action-btn.view:hover {
            color: var(--tacsa-green);
            border-color: var(--tacsa-green);
            background: var(--tacsa-green-light);
        }

        .actions-cell {
            display: flex;
            gap: 0.375rem;
        }

        /* ── Table footer ── */
        .table-footer {
            padding: 1rem 1.5rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            border-top: 1px solid var(--border-color);
            font-size: 0.8125rem;
            color: var(--text-secondary);
        }

        .pagination-btns {
            display: flex;
            gap: 0.375rem;
        }

        .page-btn {
            width: 34px;
            height: 34px;
            border-radius: 6px;
            border: 1px solid var(--border-color);
            background: var(--bg-card);
            color: var(--text-secondary);
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 0.8125rem;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.15s;
        }

        .page-btn:hover {
            border-color: #d1d5db;
            background: #f9fafb;
        }

        .page-btn.active {
            background: var(--tacsa-red);
            color: #ffffff;
            border-color: var(--tacsa-red);
        }

        /* ══════════════════════════════════
           MODALS (shared)
        ══════════════════════════════════ */
        .modal-content {
            border: none;
            border-radius: 12px;
        }

        .modal-header {
            border-bottom: 1px solid var(--border-color);
            padding: 1.25rem 1.5rem;
        }

        .modal-header .section-title {
            display: flex;
            align-items: center;
            gap: 0.625rem;
            margin: 0;
        }

        .modal-header .section-title .bar {
            width: 4px;
            height: 22px;
            background: var(--tacsa-red);
            border-radius: 9999px;
        }

        .modal-header .section-title h5 {
            font-size: 1.0625rem;
            font-weight: 600;
            color: var(--tacsa-red);
            margin: 0;
        }

        .modal-body {
            padding: 1.5rem;
        }

        .modal-footer {
            border-top: 1px solid var(--border-color);
            padding: 1rem 1.5rem;
        }

        /* ── Form fields (reused) ── */
        .field-label {
            font-size: 0.8125rem;
            font-weight: 500;
            color: var(--text-primary);
            margin-bottom: 0.375rem;
        }

        .field-label .required {
            color: var(--tacsa-red);
            margin-left: 2px;
        }

        .tacsa-input,
        .tacsa-select {
            height: 44px;
            border-radius: 8px;
            border: 1px solid var(--border-color);
            padding: 0 1rem;
            font-size: 0.8125rem;
            color: var(--text-primary);
            background-color: var(--bg-card);
            transition: border-color 0.2s, box-shadow 0.2s;
            width: 100%;
        }

        .tacsa-input::placeholder {
            color: #9ca3af;
        }

        .tacsa-input:focus,
        .tacsa-select:focus {
            border-color: var(--tacsa-red);
            box-shadow: 0 0 0 3px rgba(220, 38, 38, 0.12);
            outline: none;
        }

        .section-separator {
            border: none;
            height: 1px;
            background: var(--tacsa-red-light2);
            margin: 1.5rem 0;
        }

        .modal-section-title {
            display: flex;
            align-items: center;
            gap: 0.625rem;
            margin-bottom: 1rem;
        }

        .modal-section-title .bar {
            width: 4px;
            height: 20px;
            background: var(--tacsa-red);
            border-radius: 9999px;
        }

        .modal-section-title span {
            font-size: 0.9375rem;
            font-weight: 600;
            color: var(--tacsa-red);
        }

        /* ── Buttons ── */
        .btn-tacsa-cancel {
            height: 42px;
            padding: 0 1.25rem;
            font-size: 0.8125rem;
            font-weight: 500;
            border-radius: 8px;
            border: 1px solid var(--border-color);
            background: var(--bg-card);
            color: var(--text-primary);
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            cursor: pointer;
            transition: all 0.15s;
        }

        .btn-tacsa-cancel:hover {
            background: #f9fafb;
            border-color: #d1d5db;
        }

        .btn-tacsa-save {
            height: 42px;
            padding: 0 1.25rem;
            font-size: 0.8125rem;
            font-weight: 600;
            border-radius: 8px;
            border: none;
            background: var(--tacsa-red);
            color: #ffffff;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            cursor: pointer;
            transition: background 0.15s;
        }

        .btn-tacsa-save:hover {
            background: var(--tacsa-red-dark);
        }

        .btn-tacsa-delete {
            height: 42px;
            padding: 0 1.25rem;
            font-size: 0.8125rem;
            font-weight: 600;
            border-radius: 8px;
            border: none;
            background: var(--tacsa-red);
            color: #ffffff;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            cursor: pointer;
            transition: background 0.15s;
        }

        .btn-tacsa-delete:hover {
            background: var(--tacsa-red-dark);
        }

        /* ── Delete modal ── */
        .delete-icon-wrapper {
            width: 64px;
            height: 64px;
            border-radius: 50%;
            background: var(--tacsa-red-light);
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1.25rem;
        }

        .delete-icon-wrapper i {
            font-size: 1.75rem;
            color: var(--tacsa-red);
        }

        .delete-text {
            text-align: center;
        }

        .delete-text h5 {
            font-size: 1.0625rem;
            font-weight: 600;
            margin-bottom: 0.5rem;
        }

        .delete-text p {
            font-size: 0.875rem;
            color: var(--text-secondary);
            margin: 0;
        }

        .delete-text .item-name {
            font-weight: 600;
            color: var(--tacsa-red);
        }

        /* ── Detail modal ── */
        .detail-row {
            display: flex;
            padding: 0.75rem 0;
            border-bottom: 1px solid #f3f4f6;
        }

        .detail-row:last-child {
            border-bottom: none;
        }

        .detail-label {
            width: 160px;
            flex-shrink: 0;
            font-size: 0.8125rem;
            font-weight: 500;
            color: var(--text-secondary);
        }

        .detail-value {
            font-size: 0.8125rem;
            font-weight: 500;
            color: var(--text-primary);
        }

        /* ══════════════════════════════════
           RESPONSIVE
        ══════════════════════════════════ */
        @media (max-width: 992px) {
            .stat-cards {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        @media (max-width: 576px) {
            .page-wrapper {
                padding: 1.25rem 1rem;
            }

            .stat-cards {
                grid-template-columns: 1fr;
            }

            .topbar {
                padding: 0.75rem 1rem;
            }

            .topbar-nav {
                display: none;
            }

            .page-header {
                flex-direction: column;
            }

            .toolbar-actions {
                flex-direction: column;
                width: 100%;
            }

            .search-box input {
                width: 100%;
            }

            .table-footer {
                flex-direction: column;
                gap: 0.75rem;
                text-align: center;
            }
        }
    </style>
</head>

<body>

    <!-- ══════════ TOP BAR ══════════ -->
    <nav class="topbar">
        <a href="#" class="topbar-brand">
            <div class="logo-sm">T</div>
            TACSA
        </a>
        <div class="topbar-nav">
            <a href="{{ route('home') }}">Inicio</a>
            <a href="#" class="active">Almacenes</a>
            <a href="#">Inventario</a>
            <a href="#">Ubicaciones</a>
        </div>
    </nav>

    <!-- ══════════ PAGE ══════════ -->
    <div class="page-wrapper">

        <!-- Page header -->
        <div class="page-header">
            <div>
                <h1>Gestion de Almacenes</h1>
                <p>Administra, edita y elimina los almacenes registrados en el sistema.</p>
            </div>
            <a href="{{ route('warehouses.create') }}" class="btn-new-warehouse" title="Ir al formulario de registro">
                <i class="bi bi-plus-lg"></i>
                Nuevo Almacen
            </a>
        </div>

        <!-- Stat cards -->
        <div class="stat-cards">
            <div class="stat-card">
                <div class="stat-icon red"><i class="bi bi-building"></i></div>
                <div class="stat-info">
                    <h3>{{ $totalWarehouses }}</h3>
                    <span>Total Almacenes</span>
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-icon blue"><i class="bi bi-geo-alt"></i></div>
                <div class="stat-info">
                    <h3>{{ $totalLocation }}</h3>
                    <span>Ubicaciones</span>
                </div>
            </div>
        </div>

        <!-- Table card -->
        <div class="table-card">

            <!-- Toolbar -->
            <div class="table-toolbar">
                <div class="section-title">
                    <div class="bar"></div>
                    <h2>Listado de Almacenes</h2>
                </div>
                <div class="toolbar-actions">
                    <select class="filter-select" id="filterType">
                        <option value="">Todos los tipos</option>
                        <option value="Materia Prima">Materia Prima</option>
                        <option value="Producto Terminado">Producto Terminado</option>
                        <option value="Refacciones">Refacciones</option>
                    </select>
                    <div class="search-box">
                        <i class="bi bi-search"></i>
                        <input type="text" id="searchInput" placeholder="Buscar por clave o nombre...">
                    </div>
                </div>
            </div>

            <!-- Table -->
            <div class="table-wrapper">
                <table class="tacsa-table">
                    <thead>
                        <tr>
                            <th>Clave</th>
                            <th>Nombre</th>
                            <th>Creado</th>
                            <th>Actualizado</th>
                            <th>Responsable</th>
                            <th>Modificado por</th>
                            <th>Telefono</th>
                            <th>Ubicacion</th>
                            <th>Email</th>
                            <th>Tipo</th>
                            <th>Estado</th>
                            <th style="text-align:center;">Acciones</th>
                        </tr>
                    </thead>
                    <tbody id="tableBody">
                        <!-- Rows rendered by JS -->
                        @foreach ($warehouses as $warehouse)
                            <tr>
                                <td>{{ $warehouse->getWarehouseKey() }}</td>
                                <td>{{ $warehouse->getWarehouseName() }}</td>
                                <td>{{ $warehouse->getCreatedAt()->format('Y-m-d H:i') }}</td>
                                <td>{{ $warehouse->getUpdatedAt()->format('Y-m-d H:i') }}</td>
                                <td>{{ $warehouse->getWarehouseManager() }}</td>
                                <td>{{ $warehouse->getUserName() }}</td>
                                <td>{{ $warehouse->getPhoneNumber() }}</td>
                                <td>{{ $warehouse->getHeadquartersName() }}</td>
                                <td>{{ $warehouse->getEmail() }}</td>
                                <td>{{ $warehouse->getCategoryWarehouse() }}</td>
                                <td>Activo</td>
                                <td>
                                    <div class="actions-cell">
                                        <button class="action-btn view" title="Ver detalle"
                                            onclick='viewWarehouse(@json($warehouse->toArray()))'>
                                            <i class="bi bi-eye"></i>
                                        </button>
                                        <button class="action-btn edit" title="Editar"
                                            onclick='openEdit(@json($warehouse->toArray()))'>
                                            <i class="bi bi-pencil"></i>
                                        </button>
                                        <button class="action-btn delete" title="Eliminar"
                                            onclick='openDelete({{$warehouse->getId()}})'>
                                            <i class="bi bi-trash3"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Empty state -->
            <div id="emptyState" class="text-center py-5 d-none">
                <i class="bi bi-inbox" style="font-size: 2.5rem; color: #d1d5db;"></i>
                <p class="mt-2" style="color: var(--text-secondary); font-size: 0.875rem;">No se encontraron almacenes
                    con ese criterio.</p>
            </div>

            <!-- Footer -->
            <div class="table-footer">
                <span>Mostrando <strong id="showCount">0</strong> de <strong id="totalCount">0</strong> almacenes</span>
                <div class="pagination-btns">
                    <button class="page-btn"><i class="bi bi-chevron-left"></i></button>
                    <button class="page-btn active">1</button>
                    <button class="page-btn">2</button>
                    <button class="page-btn"><i class="bi bi-chevron-right"></i></button>
                </div>
            </div>
        </div>
    </div>

    <!-- ══════════════════════════════════
         MODAL: Detalle
    ══════════════════════════════════ -->
    <div class="modal fade" id="detailModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <div class="section-title">
                        <div class="bar"></div>
                        <h5>Detalle del Almacen</h5>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                </div>
                <div class="modal-body" id="detailBody">
                    <!-- filled by JS -->
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn-tacsa-cancel" data-bs-dismiss="modal">
                        <i class="bi bi-x-lg"></i> Cerrar
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- ══════════════════════════════════
         MODAL: Editar
    ══════════════════════════════════ -->
    <div class="modal fade" id="editModal" tabindex="-1">
        <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <div class="section-title">
                        <div class="bar"></div>
                        <h5>Editar Almacen</h5>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                </div>
                <form id="editForm" novalidate>
                    <div class="modal-body" style="max-height: 70vh; overflow-y: auto;">

                        <!-- Seccion: Info General -->
                        <div class="modal-section-title">
                            <div class="bar"></div>
                            <span>Informacion General</span>
                        </div>
                        <div class="row g-3 mb-0">
                            <div class="col-md-6">
                                <label for="editKey" class="field-label">Clave del Almacen <span
                                        class="required">*</span></label>
                                <input type="text" class="tacsa-input" id="editKey" required readonly
                                    style="background:#f9fafb; cursor:not-allowed;">
                            </div>
                            <div class="col-md-6">
                                <label for="editName" class="field-label">Nombre del Almacen <span
                                        class="required">*</span></label>
                                <input type="text" class="tacsa-input" id="editName"
                                    placeholder="Nombre del almacen" required>
                            </div>
                        </div>

                        <hr class="section-separator">

                        <!-- Seccion: Contacto -->
                        <div class="modal-section-title">
                            <div class="bar"></div>
                            <span>Contacto y Responsable</span>
                        </div>
                        <div class="row g-3 mb-0">
                            <div class="col-12">
                                <label for="editResponsable" class="field-label">Responsable <span
                                        class="required">*</span></label>
                                <input type="text" class="tacsa-input" id="editResponsable"
                                    placeholder="Nombre completo" required>
                            </div>
                            <div class="col-md-6">
                                <label for="editPhone" class="field-label">Telefono <span
                                        class="required">*</span></label>
                                <input type="tel" class="tacsa-input" id="editPhone" placeholder="10 digitos"
                                    required>
                            </div>
                            <div class="col-md-6">
                                <label for="editEmail" class="field-label">Email <span
                                        class="required">*</span></label>
                                <input type="email" class="tacsa-input" id="editEmail"
                                    placeholder="correo@ejemplo.com" required>
                            </div>
                        </div>

                        <hr class="section-separator">

                        <!-- Seccion: Ubicacion -->
                        <div class="modal-section-title">
                            <div class="bar"></div>
                            <span>Ubicacion</span>
                        </div>
                        <div class="row g-3 mb-0">
                            <div class="col-12">
                                <label for="editLocation" class="field-label">Sede <span
                                        class="required">*</span></label>
                                <select class="tacsa-select" id="editLocation" required>
                                    <option value="">Seleccione una sede</option>
                                    @foreach ($allLocation as $location)
                                        <option value="{{ $location->getId() }}">
                                            {{ $location->getHeadquartersName() }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <hr class="section-separator">

                        <!-- Seccion: Configuracion -->
                        <div class="modal-section-title">
                            <div class="bar"></div>
                            <span>Configuracion del Almacen</span>
                        </div>
                        <div class="row g-3 mb-0">
                            <div class="col-12">
                                <label for="editType" class="field-label">Tipo de Almacen <span
                                        class="required">*</span></label>
                                <select class="tacsa-select" id="editType" required>
                                    <option value="">Seleccione el tipo</option>
                                    @foreach ($allWarehouseType as $warehouseType)
                                        <option value="{{ $warehouseType->getId() }}">
                                            {{ $warehouseType->getCategoryWarehouse() }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn-tacsa-cancel" data-bs-dismiss="modal">
                            <i class="bi bi-x-lg"></i> Cancelar
                        </button>
                        <button type="submit" class="btn-tacsa-save">
                            <i class="bi bi-check-lg"></i> Actualizar Almacen
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- ══════════════════════════════════
         MODAL: Eliminar
    ══════════════════════════════════ -->
    <div class="modal fade" id="deleteModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered modal-sm">
            <div class="modal-content">
                <div class="modal-body py-4">
                    <div class="delete-icon-wrapper">
                        <i class="bi bi-exclamation-triangle"></i>
                    </div>
                    <div class="delete-text">
                        <h5>Eliminar Almacen</h5>
                        <p>Esta a punto de eliminar el almacen <span class="item-name" id="deleteName"></span>. Esta
                            accion no se puede deshacer.</p>
                    </div>
                </div>
                <div class="modal-footer justify-content-center" style="border:none; padding-top:0;">
                    <button type="button" class="btn-tacsa-cancel" data-bs-dismiss="modal">
                        <i class="bi bi-x-lg"></i> Cancelar
                    </button>
                    <button type="button" class="btn-tacsa-delete" id="btnConfirmDelete">
                        <i class="bi bi-trash3"></i> Eliminar
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- ══════════ TOAST ══════════ -->
    <div class="position-fixed bottom-0 end-0 p-3" style="z-index:1100;">
        <div id="toastMsg" class="toast align-items-center border-0" role="alert" style="border-radius:10px;">
            <div class="d-flex">
                <div class="toast-body" id="toastText" style="font-size:0.8125rem; font-weight:500;"></div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto"
                    data-bs-dismiss="toast"></button>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        let deleteTargetId = null;

        const tableBody = document.getElementById('tableBody');
        const searchInput = document.getElementById('searchInput');
        const filterType = document.getElementById('filterType');
        const showCount = document.getElementById('showCount');
        const totalCount = document.getElementById('totalCount');
        const emptyState = document.getElementById('emptyState');
        const tableWrapper = document.querySelector('.table-wrapper');

        // Warehouses data from server
        let warehouses = {!! json_encode($warehousesJson) !!};
        let warehouseTypes = {!! json_encode($allWarehouseTypeJson) !!};

        // Modals
        const editModal = new bootstrap.Modal(document.getElementById('editModal'));
        const deleteModal = new bootstrap.Modal(document.getElementById('deleteModal'));
        const detailModal = new bootstrap.Modal(document.getElementById('detailModal'));

        // Toast
        const toast = new bootstrap.Toast(document.getElementById('toastMsg'), {
            delay: 3000
        });

        function showToast(msg, type) {
            const el = document.getElementById('toastMsg');
            const text = document.getElementById('toastText');
            el.className = 'toast align-items-center border-0 text-white ' + (type === 'success' ? 'bg-success' :
                'bg-danger');
            el.style.borderRadius = '10px';
            text.textContent = msg;
            toast.show();
        }

        // ── Render table ──
        function renderTable() {
            const query = searchInput.value.toLowerCase().trim();
            const fType = filterType.value;

            let filtered = warehouses.filter(w => {
                const matchSearch = !query || w.key.toLowerCase().includes(query) || w.name.toLowerCase().includes(
                    query);
                const matchType = !fType || w.type === fType;
                return matchSearch && matchType;
            });

            totalCount.textContent = warehouses.length;
            showCount.textContent = filtered.length;

            if (filtered.length === 0) {
                tableWrapper.classList.add('d-none');
                emptyState.classList.remove('d-none');
            } else {
                tableWrapper.classList.remove('d-none');
                emptyState.classList.add('d-none');
            }

            tableBody.innerHTML = filtered.map(w => `
            <tr>
                <td><span class="cell-key">${w.key}</span></td>
                <td><span class="cell-name">${w.name}</span></td>
                <td>${w.createdAt}</td>
                <td>${w.updatedAt}</td>
                <td>${w.responsable}</td>
                <td>${w.userName}</td>
                <td>${w.phone}</td>
                <td>${w.location}</td>
                <td>${w.email}</td>
                <td><span class="badge-type">${w.type}</span></td>
                <td>
                    ${w.active
                        ? '<span class="badge-active"><span class="dot"></span>Activo</span>'
                        : '<span class="badge-inactive"><span class="dot"></span>Inactivo</span>'
                    }
                </td>
                <td style="text-align:center;">
                    <div class="actions-cell">
                        <button class="action-btn view" title="Ver detalle" onclick="viewWarehouse(${w.id})">
                            <i class="bi bi-eye"></i>
                        </button>
                        <button class="action-btn edit" title="Editar" onclick="openEdit(${w.id})">
                            <i class="bi bi-pencil"></i>
                        </button>
                        <button class="action-btn delete" title="Eliminar" onclick="openDelete(${w.id})">
                            <i class="bi bi-trash3"></i>
                        </button>
                    </div>
                </td>
            </tr>
        `).join('');
        }

        searchInput.addEventListener('input', renderTable);
        filterType.addEventListener('change', renderTable);

        // ── View detail ──
        function viewWarehouse(id) {
            const warehouse = warehouses.find(x => x.id === id);
            if (!warehouse) return;

            document.getElementById('detailBody').innerHTML = `
            <div class="detail-row"><div class="detail-label">Clave</div><div class="detail-value">${warehouse.key}</div></div>
            <div class="detail-row"><div class="detail-label">Nombre</div><div class="detail-value">${warehouse.name}</div></div>
            <div class="detail-row"><div class="detail-label">Responsable</div><div class="detail-value">${warehouse.responsable}</div></div>
            <div class="detail-row"><div class="detail-label">Telefono</div><div class="detail-value">${warehouse.phone}</div></div>
            <div class="detail-row"><div class="detail-label">Email</div><div class="detail-value">${warehouse.email}</div></div>
            <div class="detail-row"><div class="detail-label">Ubicacion</div><div class="detail-value">${warehouse.location}</div></div>
            <div class="detail-row"><div class="detail-label">Tipo</div><div class="detail-value"><span class="badge-type">${warehouse.type}</span></div></div>
            <div class="detail-row"><div class="detail-label">Fecha de creación</div><div class="detail-value">${warehouse.createdAt}</div></div>
            <div class="detail-row"><div class="detail-label">Ultima actualización</div><div class="detail-value">${warehouse.updatedAt}</div></div>
            <div class="detail-row"><div class="detail-label">Modificado por</div><div class="detail-value">${warehouse.userName}</div></div>
        `;
            detailModal.show();
        }

        // ── Open edit modal ──
        function openEdit(id) {
            const warehouse = warehouses.find(x => x.id === id);
            if (!warehouse) return;

            document.getElementById('editKey').value = warehouse.key;
            document.getElementById('editName').value = warehouse.name;
            document.getElementById('editResponsable').value = warehouse.responsable;
            document.getElementById('editPhone').value = warehouse.phone;
            document.getElementById('editEmail').value = warehouse.email;
            document.getElementById('editLocation').value = warehouse.locationId;
            document.getElementById('editType').value = warehouse.typeId;
            document.getElementById('editForm').dataset.id = warehouse.id;
            
            // Show current type name
            const typeSelect = document.getElementById('editType');
            const selectedOption = typeSelect.options[typeSelect.selectedIndex];
            if (selectedOption && selectedOption.text) {
                // Type is shown in the select, just show the dropdown
            }
            
            editModal.show();
        }

        // ── Save edit ──
        document.getElementById('editForm').addEventListener('submit', function(e) {
            e.preventDefault();
            if (!this.checkValidity()) {
                this.classList.add('was-validated');
                return;
            }

            const id = parseInt(this.dataset.id, 10);
            
            const w = warehouses.find(x => x.id === id);
            if (!w){
                return;
            } 

            const typeSelect = document.getElementById('editType');
            const selectedTypeName = typeSelect.options[typeSelect.selectedIndex].text;
            
            w.name = document.getElementById('editName').value.trim();
            w.responsable = document.getElementById('editResponsable').value.trim();
            w.phone = document.getElementById('editPhone').value.trim();
            w.email = document.getElementById('editEmail').value.trim();
            w.locationId = parseInt(document.getElementById('editLocation').value);
            w.location = document.getElementById('editLocation').options[document.getElementById('editLocation').selectedIndex].text;
            w.typeId = parseInt(typeSelect.value);
            w.type = selectedTypeName;

            // Payload for API
            const payload = {
                key: w.key,
                name: w.name,
                responsable: w.responsable,
                phone: w.phone,
                email: w.email,
                location: w.locationId,
                type: w.typeId
            };
            
            
            // Enviar al servidor
            fetch(`/warehouse-managment/${id}`, {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify(payload)
            })
            .then(response => response.json())
            .then(data => {
                alert(JSON.stringify(data));
                console.log('Server response:', data);
                if (data.success) {
                    editModal.hide();
                    renderTable();
                    showToast('Almacén "' + w.name + '" actualizado correctamente.', 'success');
                } else {
                    showToast('Error: ' + (data.message || 'Error desconocido'), 'error');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showToast('Error al actualizar el almacén.', 'error');
            });
        });

        // ── Open delete modal ──
        function openDelete(id) {
            alert("The id is: " + id);
            deleteModal.show();
        }

        // ── Confirm delete ──
        document.getElementById('btnConfirmDelete').addEventListener('click', function() {
            if (deleteTargetId === null) return;
            const w = warehouses.find(x => x.id === deleteTargetId);
            const name = w ? w.name : '';

            // Payload for API (DELETE)
            console.log('DELETE id:', deleteTargetId);

            warehouses = warehouses.filter(x => x.id !== deleteTargetId);
            deleteTargetId = null;
            deleteModal.hide();
            renderTable();
            showToast('Almacen "' + name + '" eliminado correctamente.', 'success');
        });

        // ── Initial render ──
        renderTable();
    </script>

</body>

</html>
