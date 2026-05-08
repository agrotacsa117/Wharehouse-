@extends('layouts.main')



<style>
    :root {
        /* TACSA Brand Colors */
        --tacsa-red: #DC2626;
        --tacsa-red-dark: #B91C1C;
        --tacsa-red-light: rgba(220, 38, 38, 0.08);
        --tacsa-red-light2: rgba(220, 38, 38, 0.12);

        /* Semantic Colors */
        --tacsa-green: #16a34a;
        --tacsa-green-light: rgba(22, 163, 74, 0.1);
        --tacsa-amber: #d97706;
        --tacsa-amber-light: rgba(217, 119, 6, 0.1);
        --tacsa-blue: #3b82f6;
        --tacsa-blue-light: rgba(59, 130, 246, 0.1);

        /* Neutral Palette */
        --text-primary: #111827;
        --text-secondary: #6b7280;
        --text-muted: #9ca3af;
        --border-color: #e5e7eb;
        --border-light: #f3f4f6;
        --bg-body: #f8fafc;
        --bg-card: #ffffff;
        --bg-hover: #fafafa;

        /* Shadows */
        --shadow-sm: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
        --shadow-md: 0 4px 6px -1px rgba(0, 0, 0, 0.07), 0 2px 4px -1px rgba(0, 0, 0, 0.04);
        --shadow-lg: 0 10px 15px -3px rgba(0, 0, 0, 0.08), 0 4px 6px -2px rgba(0, 0, 0, 0.04);
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
        -webkit-font-smoothing: antialiased;
    }

    /* ══════════════════════════════════
           TOP BAR - TACSA Identity
        ══════════════════════════════════ */
    .topbar {
        background: white;
        padding: 0 2.5rem;
        height: 56px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        position: sticky;
        top: 0;
        z-index: 100;
        box-shadow: 0 2px 8px rgba(220, 38, 38, 0.15);
    }

    .topbar-brand {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        color: #ffffff;
        font-weight: 700;
        font-size: 1.125rem;
        text-decoration: none;
        letter-spacing: -0.01em;
    }

    .topbar-brand .logo-circle {
        width: 34px;
        height: 34px;
        border-radius: 50%;
        border: 2px solid rgba(255, 255, 255, 0.9);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.75rem;
        font-weight: 700;
        color: #ffffff;
        background: rgba(255, 255, 255, 0.1);
    }

    .topbar-nav {
        display: flex;
        align-items: center;
        gap: 0.25rem;
    }

    .topbar-nav a {
        color: rgba(242, 6, 6, 0.85);
        text-decoration: none;
        font-size: 0.8125rem;
        font-weight: 500;
        padding: 0.5rem 1rem;
        border-radius: 8px;
        transition: all 0.2s ease;
    }

    .topbar-nav a:hover {
        color: #f22828;
        background: rgba(255, 255, 255, 0.12);
    }

    .topbar-nav a.active {
        color: #ffffff;
        background: var(--tacsa-red)
    }

    /* ══════════════════════════════════
           PAGE WRAPPER - Modern Spacing
        ══════════════════════════════════ */
    .page-wrapper {
        max-width: 1200px;
        margin: 0 auto;
        padding: 2.5rem 2rem 4rem;
    }

    /* ══════════════════════════════════
           PAGE HEADER - Clean & Bold
        ══════════════════════════════════ */
    .page-header {
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 1.5rem;
        margin-bottom: 2rem;
    }

    .page-header-text h1 {
        font-size: 1.75rem;
        font-weight: 700;
        color: var(--text-primary);
        margin: 0;
        letter-spacing: -0.02em;
    }

    .page-header-text p {
        font-size: 0.9375rem;
        color: var(--text-secondary);
        margin: 0.5rem 0 0;
        line-height: 1.5;
    }

    .btn-new-warehouse {
        height: 44px;
        padding: 0 1.5rem;
        font-size: 0.875rem;
        font-weight: 600;
        border-radius: 10px;
        border: none;
        background: var(--tacsa-red);
        color: #ffffff;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        transition: all 0.2s ease;
        cursor: pointer;
        text-decoration: none;
        box-shadow: 0 2px 4px rgba(220, 38, 38, 0.2);
    }

    .btn-new-warehouse:hover {
        background: var(--tacsa-red-dark);
        color: #ffffff;
        transform: translateY(-1px);
        box-shadow: 0 4px 8px rgba(220, 38, 38, 0.25);
    }

    .btn-new-warehouse i {
        font-size: 1rem;
    }

    /* ══════════════════════════════════
           STAT CARDS - Modern Style (Inspiracion)
        ══════════════════════════════════ */
    .stat-cards {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 1.25rem;
        margin-bottom: 2rem;
    }

    .stat-card {
        background: var(--bg-card);
        border: 1px solid var(--border-color);
        border-radius: 14px;
        padding: 1.5rem;
        display: flex;
        align-items: center;
        gap: 1rem;
        transition: all 0.2s ease;
        box-shadow: var(--shadow-sm);
    }

    .stat-card:hover {
        box-shadow: var(--shadow-md);
        transform: translateY(-2px);
    }

    .stat-icon {
        width: 52px;
        height: 52px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.375rem;
        flex-shrink: 0;
    }

    .stat-icon.red {
        background: var(--tacsa-red-light);
        color: var(--tacsa-red);
    }

    .stat-icon.blue {
        background: var(--tacsa-blue-light);
        color: var(--tacsa-blue);
    }

    .stat-icon.green {
        background: var(--tacsa-green-light);
        color: var(--tacsa-green);
    }

    .stat-icon.amber {
        background: var(--tacsa-amber-light);
        color: var(--tacsa-amber);
    }

    .stat-info h3 {
        font-size: 1.625rem;
        font-weight: 700;
        margin: 0;
        line-height: 1;
        color: var(--text-primary);
        letter-spacing: -0.02em;
    }

    .stat-info span {
        font-size: 0.8125rem;
        color: var(--text-secondary);
        margin-top: 0.25rem;
        display: block;
    }

    /* ══════════════════════════════════
           TABLE CARD - Clean Container
        ══════════════════════════════════ */
    .table-card {
        background: var(--bg-card);
        border: 1px solid var(--border-color);
        border-radius: 16px;
        overflow: hidden;
        box-shadow: var(--shadow-sm);
    }

    /* ── Toolbar ── */
    .table-toolbar {
        padding: 1.25rem 1.75rem;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 1rem;
        flex-wrap: wrap;
        border-bottom: 1px solid var(--border-light);
    }

    .section-title {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        margin: 0;
    }

    .section-title .indicator {
        width: 4px;
        height: 24px;
        background: var(--tacsa-red);
        border-radius: 4px;
        flex-shrink: 0;
    }

    .section-title h2 {
        font-size: 1.0625rem;
        font-weight: 600;
        color: var(--tacsa-red);
        margin: 0;
    }

    .toolbar-actions {
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .filter-select {
        height: 40px;
        min-width: 160px;
        border-radius: 10px;
        border: 1px solid var(--border-color);
        font-size: 0.8125rem;
        padding: 0 2.25rem 0 1rem;
        color: var(--text-primary);
        background: var(--bg-card);
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 12 12'%3E%3Cpath fill='%236b7280' d='M2.5 4.5L6 8l3.5-3.5'/%3E%3C/svg%3E");
        background-repeat: no-repeat;
        background-position: right 0.875rem center;
        appearance: none;
        cursor: pointer;
        transition: all 0.2s ease;
    }

    .filter-select:hover {
        border-color: #d1d5db;
    }

    .filter-select:focus {
        border-color: var(--tacsa-red);
        box-shadow: 0 0 0 3px rgba(220, 38, 38, 0.1);
        outline: none;
    }

    .search-box {
        position: relative;
    }

    .search-box i {
        position: absolute;
        left: 1rem;
        top: 50%;
        transform: translateY(-50%);
        color: var(--text-muted);
        font-size: 0.9375rem;
        pointer-events: none;
    }

    .search-box input {
        height: 40px;
        width: 280px;
        padding-left: 2.75rem;
        padding-right: 1rem;
        border-radius: 10px;
        border: 1px solid var(--border-color);
        font-size: 0.8125rem;
        color: var(--text-primary);
        background: var(--bg-card);
        transition: all 0.2s ease;
    }

    .search-box input::placeholder {
        color: var(--text-muted);
    }

    .search-box input:hover {
        border-color: #d1d5db;
    }

    .search-box input:focus {
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
        background: var(--bg-hover);
        padding: 0.875rem 1rem;
        font-weight: 600;
        font-size: 0.6875rem;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        color: var(--text-secondary);
        border-bottom: 1px solid var(--border-color);
        white-space: nowrap;
        text-align: left;
    }

    .tacsa-table tbody td {
        padding: 1rem;
        border-bottom: 1px solid var(--border-light);
        vertical-align: middle;
        color: var(--text-primary);
    }

    .tacsa-table tbody tr {
        transition: background 0.15s ease;
    }

    .tacsa-table tbody tr:hover {
        background: var(--bg-hover);
    }

    .tacsa-table tbody tr:last-child td {
        border-bottom: none;
    }

    /* Cell helpers */
    .cell-key {
        font-weight: 600;
        color: var(--tacsa-red);
        font-size: 0.8125rem;
    }

    .cell-name {
        font-weight: 500;
        color: var(--text-primary);
    }

    .cell-secondary {
        color: var(--text-secondary);
        font-size: 0.8125rem;
    }

    /* Badges */
    .badge-type {
        display: inline-block;
        padding: 0.3125rem 0.75rem;
        font-size: 0.6875rem;
        font-weight: 600;
        border-radius: 6px;
        background: var(--tacsa-red-light);
        color: var(--tacsa-red);
        letter-spacing: 0.01em;
    }

    .badge-active {
        display: inline-flex;
        align-items: center;
        gap: 0.375rem;
        padding: 0.3125rem 0.75rem;
        font-size: 0.6875rem;
        font-weight: 600;
        border-radius: 20px;
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
        padding: 0.3125rem 0.75rem;
        font-size: 0.6875rem;
        font-weight: 600;
        border-radius: 20px;
        background: #f3f4f6;
        color: var(--text-secondary);
    }

    .badge-inactive .dot {
        width: 6px;
        height: 6px;
        border-radius: 50%;
        background: var(--text-secondary);
    }

    /* Action buttons */
    .actions-cell {
        display: flex;
        gap: 0.375rem;
        justify-content: center;
    }

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
        transition: all 0.2s ease;
        font-size: 0.875rem;
    }

    .action-btn:hover {
        border-color: #d1d5db;
        background: var(--bg-hover);
    }

    .action-btn.view:hover {
        color: var(--tacsa-green);
        border-color: var(--tacsa-green);
        background: var(--tacsa-green-light);
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

    /* ── Empty State ── */
    .empty-state {
        text-align: center;
        padding: 4rem 2rem;
    }

    .empty-state i {
        font-size: 3rem;
        color: #e5e7eb;
        margin-bottom: 1rem;
    }

    .empty-state p {
        color: var(--text-secondary);
        font-size: 0.9375rem;
        margin: 0;
    }

    /* ── Table Footer ── */
    .table-footer {
        padding: 1rem 1.75rem;
        display: flex;
        align-items: center;
        justify-content: space-between;
        border-top: 1px solid var(--border-light);
        font-size: 0.8125rem;
        color: var(--text-secondary);
        background: var(--bg-hover);
    }

    .pagination-btns {
        display: flex;
        gap: 0.375rem;
    }

    .page-btn {
        width: 36px;
        height: 36px;
        border-radius: 8px;
        border: 1px solid var(--border-color);
        background: var(--bg-card);
        color: var(--text-secondary);
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 0.8125rem;
        font-weight: 500;
        cursor: pointer;
        transition: all 0.2s ease;
    }

    .page-btn:hover {
        border-color: #d1d5db;
        background: var(--bg-hover);
    }

    .page-btn.active {
        background: var(--tacsa-red);
        color: #ffffff;
        border-color: var(--tacsa-red);
    }

    /* ══════════════════════════════════
           MODALS - Modern & Clean
        ══════════════════════════════════ */
    .modal-content {
        border: none;
        border-radius: 16px;
        box-shadow: var(--shadow-lg);
    }

    .modal-header {
        border-bottom: 1px solid var(--border-light);
        padding: 1.25rem 1.5rem;
    }

    .modal-header .section-title {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        margin: 0;
    }

    .modal-header .section-title .indicator {
        width: 4px;
        height: 22px;
        background: var(--tacsa-red);
        border-radius: 4px;
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
        border-top: 1px solid var(--border-light);
        padding: 1rem 1.5rem;
        background: var(--bg-hover);
        border-radius: 0 0 16px 16px;
    }

    /* Form fields */
    .field-label {
        font-size: 0.8125rem;
        font-weight: 500;
        color: var(--text-primary);
        margin-bottom: 0.5rem;
        display: block;
    }

    .field-label .required {
        color: var(--tacsa-red);
        margin-left: 2px;
    }

    .tacsa-input,
    .tacsa-select {
        height: 44px;
        border-radius: 10px;
        border: 1px solid var(--border-color);
        padding: 0 1rem;
        font-size: 0.875rem;
        color: var(--text-primary);
        background-color: var(--bg-card);
        transition: all 0.2s ease;
        width: 100%;
    }

    .tacsa-input::placeholder {
        color: var(--text-muted);
    }

    .tacsa-input:hover,
    .tacsa-select:hover {
        border-color: #d1d5db;
    }

    .tacsa-input:focus,
    .tacsa-select:focus {
        border-color: var(--tacsa-red);
        box-shadow: 0 0 0 3px rgba(220, 38, 38, 0.1);
        outline: none;
    }

    .section-separator {
        border: none;
        height: 1px;
        background: var(--border-light);
        margin: 1.5rem 0;
    }

    .modal-section-title {
        display: flex;
        align-items: center;
        gap: 0.625rem;
        margin-bottom: 1rem;
    }

    .modal-section-title .indicator {
        width: 4px;
        height: 20px;
        background: var(--tacsa-red);
        border-radius: 4px;
    }

    .modal-section-title span {
        font-size: 0.9375rem;
        font-weight: 600;
        color: var(--tacsa-red);
    }

    /* Buttons */
    .btn-tacsa-cancel {
        height: 42px;
        padding: 0 1.25rem;
        font-size: 0.8125rem;
        font-weight: 500;
        border-radius: 10px;
        border: 1px solid var(--border-color);
        background: var(--bg-card);
        color: var(--text-primary);
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        cursor: pointer;
        transition: all 0.2s ease;
    }

    .btn-tacsa-cancel:hover {
        background: var(--bg-hover);
        border-color: #d1d5db;
    }

    .btn-tacsa-save {
        height: 42px;
        padding: 0 1.25rem;
        font-size: 0.8125rem;
        font-weight: 600;
        border-radius: 10px;
        border: none;
        background: var(--tacsa-red);
        color: #ffffff;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        cursor: pointer;
        transition: all 0.2s ease;
    }

    .btn-tacsa-save:hover {
        background: var(--tacsa-red-dark);
    }

    .btn-tacsa-delete {
        height: 42px;
        padding: 0 1.25rem;
        font-size: 0.8125rem;
        font-weight: 600;
        border-radius: 10px;
        border: none;
        background: var(--tacsa-red);
        color: #ffffff;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        cursor: pointer;
        transition: all 0.2s ease;
    }

    .btn-tacsa-delete:hover {
        background: var(--tacsa-red-dark);
    }

    /* Delete modal */
    .delete-icon-wrapper {
        width: 72px;
        height: 72px;
        border-radius: 50%;
        background: var(--tacsa-red-light);
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 1.25rem;
    }

    .delete-icon-wrapper i {
        font-size: 2rem;
        color: var(--tacsa-red);
    }

    .delete-text {
        text-align: center;
    }

    .delete-text h5 {
        font-size: 1.125rem;
        font-weight: 600;
        margin-bottom: 0.5rem;
        color: var(--text-primary);
    }

    .delete-text p {
        font-size: 0.875rem;
        color: var(--text-secondary);
        margin: 0;
        line-height: 1.5;
    }

    .delete-text .item-name {
        font-weight: 600;
        color: var(--tacsa-red);
    }

    /* Detail modal */
    .detail-row {
        display: flex;
        padding: 0.875rem 0;
        border-bottom: 1px solid var(--border-light);
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
        font-size: 0.875rem;
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

        .page-wrapper {
            padding: 2rem 1.5rem;
        }
    }

    @media (max-width: 768px) {
        .toolbar-actions {
            flex-direction: column;
            width: 100%;
            align-items: stretch;
        }

        .search-box input {
            width: 100%;
        }

        .filter-select {
            width: 100%;
        }
    }

    @media (max-width: 576px) {
        .page-wrapper {
            padding: 1.5rem 1rem;
        }

        .stat-cards {
            grid-template-columns: 1fr;
        }

        .topbar {
            padding: 0 1rem;
        }

        .topbar-nav {
            display: none;
        }

        .page-header {
            flex-direction: column;
            gap: 1rem;
        }

        .page-header-text h1 {
            font-size: 1.5rem;
        }

        .btn-new-warehouse {
            width: 100%;
            justify-content: center;
        }

        .table-footer {
            flex-direction: column;
            gap: 1rem;
            text-align: center;
        }

        .table-toolbar {
            padding: 1rem;
        }
    }
</style>

@section('contenido')
    <!-- ══════════ TOP BAR - TACSA Identity ══════════ -->

    <main id="main" class="main bg-light py-4">
        <div class="container-fluid">
            <nav class="topbar">
                @if (auth()->user()->rol === 'admin')
                    <div class="topbar-nav">
                        <a href="{{ route('warehouse-type.get') }}" class="active">Categorías</a>
                        <a href="{{ route('inventory.management') }}">Inventario</a>
                        <a href="{{ route('location.store') }}">Ubicaciones</a>
                    </div>
                @endif
            </nav>

            <!-- ══════════ PAGE CONTENT ══════════ -->
            <div class="page-wrapper">

                <!-- Page Header -->
                <div class="page-header">
                    <meta name="csrf-token" content="{{ csrf_token() }}">

                    <div class="page-header-text">
                        <h1>Gestión de Bodegas</h1>
                        <p>Gestión de bodegas,racks,ubicaciónes y categorías.</p>
                    </div>
                    @if (auth()->user()->rol === 'admin')
                        <a href="{{ route('warehouses.create') }}" class="btn-new-warehouse"
                            title="Ir al formulario de registro">
                            <i class="bi bi-plus-lg"></i>
                            Nueva Bodega
                        </a>
                    @endif
                </div>

                <!-- Stat Cards -->
                <div class="stat-cards">
                    <div class="stat-card">
                        <div class="stat-icon red">
                            <i class="bi bi-building"></i>
                        </div>
                        <div class="stat-info">
                            <h3>{{ $totalWarehouses }}</h3>
                            <span>Total Almacenes</span>
                        </div>
                    </div>

                    <div class="stat-card">
                        <div class="stat-icon blue">
                            <i class="bi bi-geo-alt"></i>
                        </div>
                        <div class="stat-info">
                            <h3>{{ $totalLocation }}</h3>
                            <span>Ubicaciones</span>
                        </div>
                    </div>
                </div>

                <!-- Table Card -->
                <div class="table-card">

                    <!-- Toolbar -->
                    <div class="table-toolbar">
                        <div class="section-title">
                            <div class="indicator"></div>
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
                                @foreach ($warehouses as $warehouse)
                                    <tr>
                                        <td><span class="cell-key">{{ $warehouse->getWarehouseKey() }}</span></td>
                                        <td><span class="cell-name">{{ $warehouse->getWarehouseName() }}</span></td>
                                        <td class="cell-secondary">{{ $warehouse->getCreatedAt()->format('d-m-Y') }}</td>
                                        <td class="cell-secondary">{{ $warehouse->getUpdatedAt()->format('d-m-Y') }}</td>
                                        <td>{{ $warehouse->getWarehouseManager() }}</td>
                                        <td class="cell-secondary">{{ $warehouse->getUserName() }}</td>
                                        <td>{{ $warehouse->getPhoneNumber() }}</td>
                                        <td>{{ $warehouse->getHeadquartersName() }}</td>
                                        <td class="cell-secondary">{{ $warehouse->getEmail() }}</td>
                                        <td><span class="badge-type">{{ $warehouse->getCategoryWarehouse() }}</span></td>
                                        <td><span class="badge-active"><span class="dot"></span>Activo</span></td>
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
                                                    onclick='openDelete({{ $warehouse->getId() }})'>
                                                    <i class="bi bi-trash3"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- Empty State -->
                    <div id="emptyState" class="empty-state d-none">
                        <i class="bi bi-inbox"></i>
                        <p>No se encontraron almacenes con ese criterio.</p>
                    </div>

                    <!-- Footer -->
                    <div class="table-footer">
                        <span>Mostrando <strong id="showCount">0</strong> de <strong id="totalCount">0</strong>
                            almacenes</span>
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
                                <div class="indicator"></div>
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
                                <div class="indicator"></div>
                                <h5>Editar Almacen</h5>
                            </div>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                aria-label="Cerrar"></button>
                        </div>
                        <form id="editForm" novalidate>
                            <div class="modal-body" style="max-height: 70vh; overflow-y: auto;">

                                <!-- Seccion: Info General -->
                                <div class="modal-section-title">
                                    <div class="indicator"></div>
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
                                    <div class="indicator"></div>
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
                                        <input type="tel" class="tacsa-input" id="editPhone"
                                            placeholder="10 digitos" required>
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
                                    <div class="indicator"></div>
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
                                    <div class="indicator"></div>
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
                                <p>Esta a punto de eliminar el almacen <span class="item-name" id="deleteName"></span>.
                                    Esta
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
                <div id="toastMsg" class="toast align-items-center border-0" role="alert"
                    style="border-radius:10px;">
                    <div class="d-flex">
                        <div class="toast-body" id="toastText" style="font-size:0.8125rem; font-weight:500;"></div>
                        <button type="button" class="btn-close btn-close-white me-2 m-auto"
                            data-bs-dismiss="toast"></button>
                    </div>
                </div>
            </div>
        </div>
    </main>
@endsection

@push('scripts')
    
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
                <td class="cell-secondary">${w.createdAt}</td>
                <td class="cell-secondary">${w.updatedAt}</td>
                <td>${w.responsable}</td>
                <td class="cell-secondary">${w.userName}</td>
                <td>${w.phone}</td>
                <td>${w.location}</td>
                <td class="cell-secondary">${w.email}</td>
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
            <div class="detail-row"><div class="detail-label">Fecha de creacion</div><div class="detail-value">${warehouse.createdAt}</div></div>
            <div class="detail-row"><div class="detail-label">Ultima actualizacion</div><div class="detail-value">${warehouse.updatedAt}</div></div>
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
            if (!w) {
                return;
            }

            const typeSelect = document.getElementById('editType');
            const selectedTypeName = typeSelect.options[typeSelect.selectedIndex].text;

            w.name = document.getElementById('editName').value.trim();
            w.responsable = document.getElementById('editResponsable').value.trim();
            w.phone = document.getElementById('editPhone').value.trim();
            w.email = document.getElementById('editEmail').value.trim();
            w.locationId = parseInt(document.getElementById('editLocation').value);
            w.location = document.getElementById('editLocation').options[document.getElementById('editLocation')
                .selectedIndex].text;
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
                    console.log('Server response:', data);
                    if (data.success) {
                        editModal.hide();
                        renderTable();
                        showToast('Almacen "' + w.name + '" actualizado correctamente.', 'success');
                    } else {
                        showToast('Error: ' + (data.message || 'Error desconocido'), 'error');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    showToast('Error al actualizar el almacen.', 'error');
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
@endpush
