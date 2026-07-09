@extends('layouts.main')

<style>
    :root {
        --tacsa-red: #DC2626;
        --tacsa-red-dark: #B91C1C;
        --tacsa-red-light: rgba(220, 38, 38, 0.08);
        --tacsa-red-light2: rgba(220, 38, 38, 0.15);
        --tacsa-green: #16a34a;
        --tacsa-green-dark: #15803d;
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

    * { box-sizing: border-box; }

    body {
        font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
        background-color: var(--bg-body);
        color: var(--text-primary);
        min-height: 100vh;
        margin: 0;
    }

    .inventory-item { cursor: pointer; }

    .page-wrapper {
        max-width: 1100px;
        margin: 0 auto;
        padding: 2rem 1.5rem 3rem;
    }

    .page-header {
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 1rem;
        margin-bottom: 1.75rem;
    }

    .page-header h1 { font-size: 1.5rem; font-weight: 700; color: var(--text-primary); margin: 0; }
    .page-header p { font-size: 0.875rem; color: var(--text-secondary); margin: 0.25rem 0 0; }

    .header-actions { display: flex; gap: 0.75rem; }

    .btn-entrada {
        height: 42px; padding: 0 1.25rem; font-size: 0.8125rem; font-weight: 600;
        border-radius: 8px; border: none; background: var(--tacsa-green); color: #ffffff;
        display: inline-flex; align-items: center; gap: 0.5rem;
        transition: background-color 0.15s; cursor: pointer;
    }
    .btn-entrada:hover { background: var(--tacsa-green-dark); color: #ffffff; }

    .btn-salida {
        height: 42px; padding: 0 1.25rem; font-size: 0.8125rem; font-weight: 600;
        border-radius: 8px; border: none; background: var(--tacsa-red); color: #ffffff;
        display: inline-flex; align-items: center; gap: 0.5rem;
        transition: background-color 0.15s; cursor: pointer;
    }
    .btn-salida:hover { background: var(--tacsa-red-dark); color: #ffffff; }

    /* ── Stat Cards ── */
    .stat-cards {
        display: grid; grid-template-columns: repeat(4, 1fr);
        gap: 1rem; margin-bottom: 1.75rem;
    }
    .stat-card {
        background: var(--bg-card); border: 1px solid var(--border-color);
        border-radius: 10px; padding: 1.25rem;
        display: flex; align-items: flex-start; gap: 1rem;
    }
    .stat-icon {
        width: 44px; height: 44px; border-radius: 10px;
        display: flex; align-items: center; justify-content: center;
        font-size: 1.25rem; flex-shrink: 0;
    }
    .stat-icon.red    { background: var(--tacsa-red-light); color: var(--tacsa-red); }
    .stat-icon.green  { background: var(--tacsa-green-light); color: var(--tacsa-green); }
    .stat-icon.amber  { background: var(--tacsa-amber-light); color: var(--tacsa-amber); }
    .stat-icon.blue   { background: var(--tacsa-blue-light); color: var(--tacsa-blue); }
    .stat-icon.purple { background: rgba(139,92,246,0.15); color: #8B5CF6; }
    .stat-icon.orange { background: rgba(249,115,22,0.15); color: #F97316; }
    .stat-info h3 { font-size: 1.375rem; font-weight: 700; margin: 0; line-height: 1; }
    .stat-info span { font-size: 0.75rem; color: var(--text-secondary); }

    /* ── Tabs ── */
    .tacsa-tabs {
        display: flex; gap: 0; margin-bottom: 1.5rem;
        border-bottom: 2px solid var(--border-color);
    }
    .tacsa-tab {
        padding: 0.75rem 1.5rem; font-size: 0.875rem; font-weight: 500;
        color: var(--text-secondary); background: none; border: none;
        cursor: pointer; position: relative; transition: color 0.15s;
    }
    .tacsa-tab:hover { color: var(--text-primary); }
    .tacsa-tab.active { color: var(--tacsa-red); font-weight: 600; }
    .tacsa-tab.active::after {
        content: ''; position: absolute; bottom: -2px; left: 0; right: 0;
        height: 2px; background: var(--tacsa-red); border-radius: 1px;
    }

    .tab-content-panel { display: none; }
    .tab-content-panel.active { display: block; }

    /* ── Table Card ── */
    .table-card {
        background: var(--bg-card); border: 1px solid var(--border-color);
        border-radius: 12px; overflow: hidden;
    }
    .table-toolbar {
        padding: 1.25rem 1.5rem; display: flex; align-items: center;
        justify-content: space-between; gap: 1rem; flex-wrap: wrap;
        border-bottom: 1px solid var(--border-color);
    }
    .table-toolbar .section-title { display: flex; align-items: center; gap: 0.625rem; margin: 0; }
    .table-toolbar .section-title .bar {
        width: 4px; height: 22px; background: var(--tacsa-red);
        border-radius: 9999px; flex-shrink: 0;
    }
    .table-toolbar .section-title h2 { font-size: 1rem; font-weight: 600; color: var(--tacsa-red); margin: 0; }
    .toolbar-actions { display: flex; align-items: center; gap: 0.75rem; }

    .search-box { position: relative; }
    .search-box i {
        position: absolute; left: 0.875rem; top: 50%;
        transform: translateY(-50%); color: var(--text-secondary); font-size: 0.875rem;
    }
    .search-box input {
        height: 38px; width: 260px; padding-left: 2.5rem; padding-right: 1rem;
        border-radius: 8px; border: 1px solid var(--border-color);
        font-size: 0.8125rem; color: var(--text-primary); background: var(--bg-card);
        transition: border-color 0.2s, box-shadow 0.2s;
    }
    .search-box input:focus {
        border-color: var(--tacsa-red);
        box-shadow: 0 0 0 3px rgba(220,38,38,0.1); outline: none;
    }
    .filter-select {
        height: 38px; border-radius: 8px; border: 1px solid var(--border-color);
        font-size: 0.8125rem; padding: 0 2rem 0 0.75rem;
        color: var(--text-primary); background: var(--bg-card);
        transition: border-color 0.2s; cursor: pointer;
    }
    .filter-label {
        display: block; font-size: 0.75rem; font-weight: 600;
        color: var(--text-secondary); text-transform: uppercase;
        letter-spacing: 0.03em; margin-bottom: 0.375rem;
    }
    .filter-result-text { font-size: 0.8125rem; color: var(--text-secondary); font-weight: 500; }
    .inventory-item.hidden-filter { display: none !important; }
    .no-results-filter { grid-column: 1/-1; text-align: center; padding: 3rem 1rem; color: var(--text-secondary); }

    .table-wrapper { overflow-x: auto; }
    .tacsa-table { width: 100%; border-collapse: collapse; font-size: 0.8125rem; }
    .tacsa-table thead th {
        background: #fafafa; padding: 0.875rem 1rem; font-weight: 600;
        font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.04em;
        color: var(--text-secondary); border-bottom: 1px solid var(--border-color);
        white-space: nowrap;
    }
    .tacsa-table tbody td {
        padding: 1rem; border-bottom: 1px solid #f3f4f6; vertical-align: middle;
    }
    .tacsa-table tbody tr { transition: background 0.1s; }
    .tacsa-table tbody tr:hover { background: #fafafa; }
    .tacsa-table tbody tr:last-child td { border-bottom: none; }

    /* ── Badges ── */
    .badge-entrada {
        display: inline-flex; align-items: center; gap: 0.375rem;
        padding: 0.25rem 0.75rem; font-size: 0.6875rem; font-weight: 600;
        border-radius: 9999px; background: var(--tacsa-green-light); color: var(--tacsa-green);
    }
    .badge-salida {
        display: inline-flex; align-items: center; gap: 0.375rem;
        padding: 0.25rem 0.75rem; font-size: 0.6875rem; font-weight: 600;
        border-radius: 9999px; background: var(--tacsa-red-light); color: var(--tacsa-red);
    }
    .badge-ajuste {
        display: inline-flex; align-items: center; gap: 0.375rem;
        padding: 0.25rem 0.75rem; font-size: 0.6875rem; font-weight: 600;
        border-radius: 9999px; background: var(--tacsa-amber-light); color: var(--tacsa-amber);
    }
    .badge-transferencia {
        display: inline-flex; align-items: center; gap: 0.375rem;
        padding: 0.25rem 0.75rem; font-size: 0.6875rem; font-weight: 600;
        border-radius: 9999px; background: rgba(124,58,237,0.15); color: #6d28d9;
    }
    .transfer-warehouse { color: var(--tacsa-red); font-weight: 500; }
    .transfer-warehouse-dest { color: #6d28d9; font-weight: 600; }
    .badge-product {
        display: inline-block; padding: 0.25rem 0.625rem; font-size: 0.6875rem;
        font-weight: 600; border-radius: 9999px;
        background: var(--tacsa-blue-light); color: var(--tacsa-blue);
    }
    .cell-qty { font-weight: 700; font-size: 0.875rem; }
    .cell-qty.positive { color: var(--tacsa-green); }
    .cell-qty.negative { color: var(--tacsa-red); }

    /* ── Table Footer / Pagination ── */
    .table-footer {
        padding: 1rem 1.5rem; display: flex; align-items: center;
        justify-content: space-between; border-top: 1px solid var(--border-color);
        font-size: 0.8125rem; color: var(--text-secondary);
    }
    .pagination-btns { display: flex; gap: 0.375rem; }
    .page-btn {
        width: 34px; height: 34px; border-radius: 6px;
        border: 1px solid var(--border-color); background: var(--bg-card);
        color: var(--text-secondary); display: inline-flex; align-items: center;
        justify-content: center; font-size: 0.8125rem; font-weight: 500;
        cursor: pointer; transition: all 0.15s;
    }
    .page-btn.active { background: var(--tacsa-red); color: #ffffff; border-color: var(--tacsa-red); }
    .page-btn:disabled { opacity: 0.5; cursor: not-allowed; }

    /* ── Empty State ── */
    .empty-state { text-align: center; padding: 4rem 2rem; }
    .empty-icon {
        width: 72px; height: 72px; border-radius: 50%; background: #f3f4f6;
        display: flex; align-items: center; justify-content: center; margin: 0 auto 1.25rem;
    }
    .empty-icon i { font-size: 2rem; color: #9ca3af; }
    .empty-state h4 { font-size: 1rem; font-weight: 600; margin-bottom: 0.375rem; }
    .empty-state p { font-size: 0.875rem; color: var(--text-secondary); margin: 0; }

    /* ── Inventory Cards ── */
    .inventory-grid { display: grid; grid-template-columns: repeat(3,1fr); gap: 1rem; }
    .inventory-item {
        background: var(--bg-card); border: 1px solid var(--border-color);
        border-radius: 10px; padding: 1.25rem;
        transition: box-shadow 0.15s, border-color 0.15s;
    }
    .inventory-item:hover { box-shadow: 0 4px 12px rgba(0,0,0,0.06); border-color: #d1d5db; }
    .inventory-item-header { display: flex; align-items: center; justify-content: space-between; margin-bottom: 1rem; }
    .inventory-item-header h5 { font-size: 0.875rem; font-weight: 600; margin: 0; }
    .inventory-item-row {
        display: flex; align-items: center; justify-content: space-between;
        padding: 0.5rem 0; border-top: 1px solid #f3f4f6;
    }
    .inventory-item-row .label { font-size: 0.75rem; color: var(--text-secondary); }
    .inventory-item-row .value { font-size: 0.8125rem; font-weight: 600; }

    /* ── Modal overrides (solo estética, no estructura) ── */
    .modal-content { border: none; border-radius: 12px; }
    .modal-header { border-bottom: 1px solid var(--border-color); padding: 1.25rem 1.5rem; }
    .modal-body { padding: 1.5rem; }
    .modal-footer { border-top: 1px solid var(--border-color); padding: 1rem 1.5rem; }

    .modal-header .section-title { display: flex; align-items: center; gap: 0.625rem; margin: 0; }
    .modal-header .section-title .bar { width: 4px; height: 22px; background: var(--tacsa-red); border-radius: 9999px; }
    .modal-header .section-title h5 { font-size: 1.0625rem; font-weight: 600; color: var(--tacsa-red); margin: 0; }

    .field-label { font-size: 0.8125rem; font-weight: 500; color: var(--text-primary); margin-bottom: 0.375rem; }
    .field-label .required { color: var(--tacsa-red); margin-left: 2px; }

    .tacsa-input, .tacsa-select, .tacsa-textarea {
        height: 44px; border-radius: 8px; border: 1px solid var(--border-color);
        padding: 0 1rem; font-size: 0.8125rem; color: var(--text-primary);
        background-color: var(--bg-card); transition: border-color 0.2s, box-shadow 0.2s; width: 100%;
    }
    .tacsa-textarea { height: auto; padding: 0.75rem 1rem; resize: vertical; min-height: 80px; }
    .tacsa-input:focus, .tacsa-select:focus, .tacsa-textarea:focus {
        border-color: var(--tacsa-red); box-shadow: 0 0 0 3px rgba(220,38,38,0.12); outline: none;
    }
    .section-separator { border: none; height: 1px; background: var(--tacsa-red-light2); margin: 1.5rem 0; }
    .modal-section-title { display: flex; align-items: center; gap: 0.625rem; margin-bottom: 1rem; }
    .modal-section-title .bar { width: 4px; height: 20px; background: var(--tacsa-red); border-radius: 9999px; }
    .modal-section-title span { font-size: 0.9375rem; font-weight: 600; color: var(--tacsa-red); }

    .btn-tacsa-cancel {
        height: 42px; padding: 0 1.25rem; font-size: 0.8125rem; font-weight: 500;
        border-radius: 8px; border: 1px solid var(--border-color); background: var(--bg-card);
        color: var(--text-primary); display: inline-flex; align-items: center; gap: 0.5rem;
        cursor: pointer; transition: all 0.15s;
    }
    .btn-tacsa-save {
        height: 42px; padding: 0 1.25rem; font-size: 0.8125rem; font-weight: 600;
        border-radius: 8px; border: none; background: var(--tacsa-green); color: #ffffff;
        display: inline-flex; align-items: center; gap: 0.5rem; cursor: pointer; transition: background 0.15s;
    }
    .btn-tacsa-save:hover { background: var(--tacsa-green-dark); }
    .btn-tacsa-save.red { background: var(--tacsa-red); }
    .btn-tacsa-save.red:hover { background: var(--tacsa-red-dark); }

    .detail-row { display: flex; padding: 0.75rem 0; border-bottom: 1px solid #f3f4f6; }
    .detail-row:last-child { border-bottom: none; }
    .detail-label { width: 160px; flex-shrink: 0; font-size: 0.8125rem; font-weight: 500; color: var(--text-secondary); }
    .detail-value { font-size: 0.8125rem; font-weight: 500; color: var(--text-primary); }

    /* ── Toast ── */
    .tacsa-toast {
        position: fixed; bottom: 2rem; right: 2rem; background: var(--bg-card);
        border: 1px solid var(--border-color); border-radius: 10px; padding: 1rem 1.25rem;
        box-shadow: 0 8px 24px rgba(0,0,0,0.12); display: flex; align-items: center; gap: 0.75rem;
        font-size: 0.8125rem; font-weight: 500; z-index: 9999;
        transform: translateY(120%); opacity: 0; transition: all 0.3s ease;
    }
    .tacsa-toast.show { transform: translateY(0); opacity: 1; }
    .tacsa-toast .toast-icon {
        width: 32px; height: 32px; border-radius: 8px;
        display: flex; align-items: center; justify-content: center; font-size: 1rem; flex-shrink: 0;
    }
    .tacsa-toast .toast-icon.success { background: var(--tacsa-green-light); color: var(--tacsa-green); }
    .tacsa-toast .toast-icon.error { background: var(--tacsa-red-light); color: var(--tacsa-red); }

    /* ── Reports ── */
    .report-type-selector { background: var(--bg-card); border: 1px solid var(--border-color); border-radius: 12px; padding: 1.5rem; margin-bottom: 1.5rem; }
    .report-intro { font-size: 0.875rem; color: var(--text-secondary); margin-bottom: 1rem; }
    .report-types-grid { display: grid; grid-template-columns: repeat(2,1fr); gap: 1rem; }
    .report-type-card {
        display: flex; align-items: flex-start; gap: 1rem; padding: 1.25rem;
        background: #fafafa; border: 1.5px solid var(--border-color); border-radius: 10px;
        cursor: pointer; transition: all 0.2s ease;
    }
    .report-type-card:hover { border-color: var(--tacsa-red); background: #fff; box-shadow: 0 4px 12px rgba(220,38,38,0.08); }
    .report-type-card.selected { border-color: var(--tacsa-red); background: var(--tacsa-red-light); }
    .report-type-icon { width: 48px; height: 48px; border-radius: 10px; display: flex; align-items: center; justify-content: center; font-size: 1.25rem; flex-shrink: 0; }
    .report-type-icon.blue { background: var(--tacsa-blue-light); color: var(--tacsa-blue); }
    .report-type-icon.green { background: var(--tacsa-green-light); color: var(--tacsa-green); }
    .report-type-icon.amber { background: var(--tacsa-amber-light); color: var(--tacsa-amber); }
    .report-type-icon.red { background: var(--tacsa-red-light); color: var(--tacsa-red); }
    .report-type-info h6 { font-size: 0.9375rem; font-weight: 700; margin: 0 0 0.25rem; }
    .report-type-info p { font-size: 0.8125rem; color: var(--text-secondary); margin: 0; line-height: 1.4; }
    .report-filters-panel { background: var(--bg-card); border: 1px solid var(--border-color); border-radius: 12px; padding: 1.25rem 1.5rem; margin-bottom: 1.5rem; }
    .report-filters-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 1rem; padding-bottom: 0.75rem; border-bottom: 1px solid var(--border-color); }
    .btn-clear-report { padding: 0.4rem 0.75rem; font-size: 0.8125rem; font-weight: 500; color: var(--text-secondary); background: transparent; border: 1px solid var(--border-color); border-radius: 6px; cursor: pointer; display: flex; align-items: center; gap: 0.35rem; }
    .btn-clear-report:hover { border-color: var(--tacsa-red); color: var(--tacsa-red); }
    .btn-generate-report { width: 100%; padding: 0.625rem 1rem; font-size: 0.8125rem; font-weight: 600; color: #fff; background: var(--tacsa-red); border: none; border-radius: 8px; cursor: pointer; display: flex; align-items: center; justify-content: center; gap: 0.4rem; }
    .btn-generate-report:hover:not(:disabled) { background: var(--tacsa-red-dark); }
    .btn-generate-report:disabled { opacity: 0.5; cursor: not-allowed; }
    .report-results { background: var(--bg-card); border: 1px solid var(--border-color); border-radius: 12px; overflow: hidden; }
    .report-results-header { display: flex; justify-content: space-between; align-items: center; padding: 1rem 1.5rem; border-bottom: 1px solid var(--border-color); background: #fafafa; }
    .results-count { font-size: 0.8125rem; color: var(--text-secondary); font-weight: 500; }
    .report-empty { text-align: center; padding: 3rem 1rem; color: var(--text-secondary); }

    /* ── Footer ── */
    .site-footer { border-top: 2px solid var(--tacsa-red); text-align: center; padding: 1.25rem; font-size: 0.75rem; color: var(--text-secondary); margin-top: 2rem; }

    /* ── Responsive ── */
    @media (max-width: 992px) {
        .stat-cards { grid-template-columns: repeat(2,1fr); }
        .inventory-grid { grid-template-columns: repeat(2,1fr); }
    }
    @media (max-width: 576px) {
        .page-wrapper { padding: 1.25rem 1rem; }
        .stat-cards { grid-template-columns: 1fr; }
        .inventory-grid { grid-template-columns: 1fr; }
        .page-header { flex-direction: column; }
        .header-actions { width: 100%; }
        .toolbar-actions { width: 100%; flex-wrap: wrap; }
        .search-box input { width: 100%; }
        .table-footer { flex-direction: column; gap: 0.75rem; }
        .report-types-grid { grid-template-columns: 1fr; }
    }
</style>

@section('title', 'TACSA - Movimientos de Inventario')
@section('contenido')
<main id="main" class="main bg-light py-4">
    <div class="container-fluid">

        <div class="page-wrapper">

            <!-- Page Header -->
            <div class="page-header">
                <div>
                    <h1>Movimientos</h1>
                    <p>Entradas y salidas de inventario</p>
                </div>
                <div class="header-actions">
                    <button onclick="window.location='{{ route('operation.get') }}'" class="btn-entrada">
                        <i class="bi bi-box-arrow-in-down"></i> Entrada
                    </button>
                    <button onclick="window.location='{{ route('output.get') }}'" class="btn-salida">
                        <i class="bi bi-box-arrow-up"></i> Salida
                    </button>
                </div>
            </div>

            <!-- Stat Cards -->
            <div class="stat-cards">
                <div class="stat-card">
                    <div class="stat-icon blue"><i class="bi bi-arrow-left-right"></i></div>
                    <div class="stat-info"><h3>{{ $movementsTotal }}</h3><span>Total Movimientos</span></div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon green"><i class="bi bi-box-arrow-in-down"></i></div>
                    <div class="stat-info"><h3>{{ $movementsTotalIN }}</h3><span>Entradas</span></div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon red"><i class="bi bi-box-arrow-up"></i></div>
                    <div class="stat-info"><h3>{{ $movementsTotalOUT }}</h3><span>Salidas</span></div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon purple"><i class="bi bi-arrows-move"></i></div>
                    <div class="stat-info"><h3>{{ $movementsTotalTRANSFER }}</h3><span>Traslados</span></div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon purple"><i class="bi bi-box-arrow-in-right"></i></div>
                    <div class="stat-info"><h3>{{ $movementsTotalRELOCATION }}</h3><span>Reubicaciones</span></div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon orange"><i class="bi bi-pencil-square"></i></div>
                    <div class="stat-info"><h3>{{ $movementsTotalADJUSTMENT }}</h3><span>Ajustes</span></div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon orange"><i class="bi bi-cash-stack"></i></div>
                    <div class="stat-info"><h3>{{ $movementsTotalSALE }}</h3><span>Ventas</span></div>
                </div>
            </div>

            <!-- Tabs -->
            <div class="tacsa-tabs">
                <button class="tacsa-tab active" onclick="switchTab('historial', this)">
                    <i class="bi bi-clock-history"></i>&nbsp; Historial de Movimientos
                </button>
                <button class="tacsa-tab" onclick="switchTab('inventario', this)">
                    <i class="bi bi-boxes"></i>&nbsp; Inventario Actual
                </button>
            </div>

            <!-- TAB 1: HISTORIAL -->
            <div class="tab-content-panel active" id="tab-historial">
                <div class="table-card">
                    <div class="table-toolbar">
                        <div class="section-title">
                            <span class="bar"></span>
                            <h2>Registro de Movimientos</h2>
                        </div>
                        <div class="toolbar-actions">
                            <div class="search-box">
                                <i class="bi bi-search"></i>
                                <input type="text" id="searchMovements" placeholder="Buscar por producto, lote..." oninput="filterMovements()">
                            </div>
                            <select class="filter-select" id="filterType" onchange="filterMovements()">
                                <option value="">Todos los tipos</option>
                                <option value="entrada">Entrada</option>
                                <option value="salida">Salida</option>
                                <option value="traslado">Traslado</option>
                                <option value="ajuste">Ajuste</option>
                                <option value="relocation">Reubicación</option>
                                <option value="sales">Ventas</option>
                            </select>
                        </div>
                    </div>
                    <div class="table-wrapper">
                        <table class="tacsa-table">
                            <thead>
                                <tr>
                                    <th>Folio</th>
                                    <th>Fecha</th>
                                    <th>Tipo</th>
                                    <th>Producto</th>
                                    <th>Bodega</th>
                                    <th>Ubicación</th>
                                    <th>Cantidad</th>
                                    <th>Lote</th>
                                    <th>Folio SAP</th>
                                    <th class="text-center">Acciones</th>
                                </tr>
                            </thead>
                            <tbody id="movementsBody"></tbody>
                        </table>
                    </div>
                    <div class="empty-state" id="emptyHistorial" style="display:none;">
                        <div class="empty-icon"><i class="bi bi-arrow-left-right"></i></div>
                        <h4>No hay movimientos registrados</h4>
                        <p>Los movimientos de entrada y salida se mostrarán aquí</p>
                    </div>
                    <div class="table-footer" id="historialFooter">
                        <span>Mostrando <strong id="showingFrom">1</strong>-<strong id="showingTo">15</strong> de <strong id="showingTotal">0</strong> movimientos</span>
                        <div class="pagination-btns" id="paginationBtns">
                            <button class="page-btn" id="prevPageBtn" onclick="changePage(-1)" disabled>
                                <i class="bi bi-chevron-left"></i>
                            </button>
                            <span id="pageNumbers"></span>
                            <button class="page-btn" id="nextPageBtn" onclick="changePage(1)">
                                <i class="bi bi-chevron-right"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- TAB 2: INVENTARIO ACTUAL -->
            <div class="tab-content-panel" id="tab-inventario">
                <div class="table-card" style="border:none; background:transparent; overflow:visible;">
                    <div class="table-toolbar" style="border-radius:12px 12px 0 0; background:var(--bg-card); border:1px solid var(--border-color);">
                        <div class="section-title"><span class="bar"></span><h2>Stock por Producto</h2></div>
                    </div>
                    <div class="inventory-filters" style="padding:1rem 1.5rem; border:1px solid var(--border-color); border-top:none; background:var(--bg-card);">
                        <div class="row g-3">
                            <div class="col-md-3 col-sm-6">
                                <label class="filter-label">Producto</label>
                                <select class="form-select filter-select" id="filterProducto" onchange="filterInventory()">
                                    <option value="">Todos los productos</option>
                                </select>
                            </div>
                            <div class="col-md-3 col-sm-6">
                                <label class="filter-label">Bodega</label>
                                <select class="form-select filter-select" id="filterBodega" onchange="filterInventory()">
                                    <option value="">Todas las bodegas</option>
                                </select>
                            </div>
                            <div class="col-md-3 col-sm-6">
                                <label class="filter-label">Rack</label>
                                <select class="form-select filter-select" id="filterRack" onchange="filterInventory()">
                                    <option value="">Todos los racks</option>
                                </select>
                            </div>
                            <div class="col-md-3 col-sm-6">
                                <label class="filter-label">Nivel</label>
                                <select class="form-select filter-select" id="filterNivel" onchange="filterInventory()">
                                    <option value="">Todos los niveles</option>
                                </select>
                            </div>
                        </div>
                        <div class="d-flex align-items-center justify-content-between mt-3" id="filterStatus" style="display:none!important;">
                            <span class="filter-result-text"><i class="bi bi-funnel-fill" style="color:var(--tacsa-red);"></i> <span id="filterCount">0</span> productos encontrados</span>
                            <button class="btn btn-sm btn-outline-secondary" onclick="clearFilters()" style="font-size:0.8rem; border-radius:8px;">
                                <i class="bi bi-x-lg"></i> Limpiar filtros
                            </button>
                        </div>
                    </div>
                </div>
                <div class="inventory-grid" style="margin-top:1rem;">
                    @foreach ($inventories as $item)
                    <div class="inventory-item"
                        data-id="{{ $item->getInventoryId() }}"
                        data-product="{{ $item->getProductName() }}"
                        data-warehouse="{{ $item->getWarehouseName() }}"
                        data-rack="{{ $item->getRack() }}"
                        data-level="{{ $item->getLevel() }}"
                        onclick="goToInventoryMovements(this.dataset.id)">
                        <div class="inventory-item-header">
                            <h5>{{ $item->getProductName() }}</h5>
                            <span class="badge-product">{{ $item->getProductCode() }}</span>
                        </div>
                        <div class="inventory-item-row"><span class="label">Bodega</span><span class="value">{{ $item->getWarehouseName() }}</span></div>
                        <div class="inventory-item-row"><span class="label">Rack / Nivel</span><span class="value">{{ $item->getRack() }} / Nivel {{ $item->getLevel() }}</span></div>
                        <div class="inventory-item-row"><span class="label">Bahía / Modulo</span><span class="value">{{ $item->getBay() }} / Modulo {{ $item->getModule() }}</span></div>
                        <div class="inventory-item-row"><span class="label">Tarima</span><span class="value" style="color:var(--tacsa-green);">{{ $item->getPlatform() }}</span></div>
                        <div class="inventory-item-row"><span class="label">Stock Actual</span><span class="value" style="color:var(--tacsa-green);">{{ $item->getStock() }} pzs</span></div>
                        <div class="inventory-item-row"><span class="label">Caducidad</span><span class="value" style="color:red;">{{ substr($item->getExpirationDate(), 0, 10) }}</span></div>
                        <div class="inventory-item-row"><span class="label">No. de lote</span><span class="value">{{ $item->getLotNumber() }}</span></div>
                        <div class="inventory-item-row"><span class="label">Fecha fabricación</span><span class="value">{{ $item->getManufacturingDate() }}</span></div>
                    </div>
                    @endforeach
                </div>
            </div>

        </div>

        <!-- TAB REPORTES (fuera del page-wrapper) -->
        <div class="tab-content-panel" id="tab-reportes">
            <div class="report-type-selector">
                <p class="report-intro">Seleccione el tipo de reporte que desea generar:</p>
                <div class="report-types-grid">
                    <div class="report-type-card" onclick="selectReportType('bodega')" id="rptType-bodega">
                        <div class="report-type-icon blue"><i class="bi bi-building"></i></div>
                        <div class="report-type-info"><h6>Por Bodega</h6><p>Inventario completo de una bodega con detalles de productos y ubicaciones</p></div>
                    </div>
                    <div class="report-type-card" onclick="selectReportType('articulo')" id="rptType-articulo">
                        <div class="report-type-icon green"><i class="bi bi-box-seam"></i></div>
                        <div class="report-type-info"><h6>Por Artículo</h6><p>Movimientos y existencias de un producto en todos los almacenes</p></div>
                    </div>
                    <div class="report-type-card" onclick="selectReportType('caducidad')" id="rptType-caducidad">
                        <div class="report-type-icon amber"><i class="bi bi-exclamation-triangle"></i></div>
                        <div class="report-type-info"><h6>Por Caducidad</h6><p>Productos próximos a caducar o ya caducados para tomar acción</p></div>
                    </div>
                    <div class="report-type-card" onclick="selectReportType('periodo')" id="rptType-periodo">
                        <div class="report-type-icon red"><i class="bi bi-calendar-range"></i></div>
                        <div class="report-type-info"><h6>Movimientos por Periodo</h6><p>Entradas, salidas y ajustes dentro de un rango de fechas</p></div>
                    </div>
                </div>
            </div>

            <div class="report-filters-panel" id="reportFiltersPanel" style="display:none;">
                <div class="report-filters-header">
                    <div class="section-title"><span class="bar"></span><h5 id="reportFiltersPanelTitle">Filtros del Reporte</h5></div>
                    <button type="button" class="btn-clear-report" onclick="clearReportSelection()"><i class="bi bi-arrow-left"></i> Cambiar tipo</button>
                </div>
                <div class="report-filter-group" id="filtersForBodega" style="display:none;">
                    <div class="row g-3 align-items-end">
                        <div class="col-md-4">
                            <label class="field-label">Bodega <span class="required">*</span></label>
                            <select class="tacsa-select" id="rptBodegaSelect" onchange="checkReportFilters()">
                                <option value="">Seleccione una bodega</option>
                                @foreach ($warehouses as $warehouse)
                                <option value="{{ $warehouse->getId() }}">{{ $warehouse->getWarehouseName() }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2">
                            <button type="button" class="btn-generate-report" id="btnGenBodega" onclick="generateReportInline('bodega')" disabled><i class="bi bi-search"></i> Consultar</button>
                        </div>
                    </div>
                </div>
                <div class="report-filter-group" id="filtersForCaducidad" style="display:none;">
                    <div class="row g-3 align-items-end">
                        <div class="col-md-4"><p class="text-muted small mb-0">Se mostrarán todos los productos caducados</p></div>
                        <div class="col-md-4">
                            <button type="button" class="btn-generate-report" id="btnGenCaducidad" onclick="generateReportInline('caducidad')"><i class="bi bi-search"></i> Consultar</button>
                        </div>
                    </div>
                </div>
                <div class="report-filter-group" id="filtersForPeriodo" style="display:none;">
                    <div class="row g-3 align-items-end">
                        <div class="col-md-2">
                            <label class="field-label">Fecha inicio <span class="required">*</span></label>
                            <input type="date" class="tacsa-input" id="rptPeriodoInicio" onchange="checkReportFilters()">
                        </div>
                        <div class="col-md-2">
                            <label class="field-label">Fecha fin <span class="required">*</span></label>
                            <input type="date" class="tacsa-input" id="rptPeriodoFin" onchange="checkReportFilters()">
                        </div>
                        <div class="col-md-3">
                            <label class="field-label">Tipo de movimiento</label>
                            <select class="tacsa-select" id="rptPeriodoTipoInline">
                                <option value="">Todos</option>
                                <option value="IN">Entradas</option>
                                <option value="OUT">Salidas</option>
                                <option value="TRANSFER">Traslados</option>
                                <option value="ADJUSTMENT">Ajustes</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="field-label">Bodega (opcional)</label>
                            <select class="tacsa-select" id="rptPeriodoBodega">
                                <option value="">Todas</option>
                                @foreach ($warehouses as $warehouse)
                                <option value="{{ $warehouse->getId() }}">{{ $warehouse->getWarehouseName() }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2">
                            <button type="button" class="btn-generate-report" id="btnGenPeriodo" onclick="generateReportInline('periodo')" disabled><i class="bi bi-search"></i> Consultar</button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="report-results" id="reportResults" style="display:none;">
                <div class="report-results-header">
                    <div class="section-title"><span class="bar"></span><h5 id="reportResultsTitle">Resultados</h5></div>
                    <span class="results-count" id="resultsCount">0 registros encontrados</span>
                </div>
                <div class="table-responsive">
                    <table class="tacsa-table" id="reportTable">
                        <thead id="reportTableHead"></thead>
                        <tbody id="reportTableBody"></tbody>
                    </table>
                </div>
                <div class="report-empty" id="reportEmpty" style="display:none;">
                    <i class="bi bi-inbox"></i>
                    <p>No se encontraron registros con los filtros seleccionados</p>
                </div>
            </div>
        </div>

        <!-- Footer -->
        <div class="site-footer">&copy; 2026 Sistemas TACSA — Todos los derechos reservados.</div>

        <!-- ═══════════════════ MODALES ═══════════════════ -->

        <!-- Modal: Nueva Entrada -->
        <div class="modal fade" id="entradaModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-lg modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <div class="section-title"><span class="bar"></span><h5>Registrar Entrada</h5></div>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <form id="entradaForm" onsubmit="return handleEntrada(event)">
                        <div class="modal-body" id="modalContent">
                            <div class="text-center p-4">
                                <div class="spinner-border"></div>
                                <p class="mt-2">Cargando información...</p>
                            </div>
                        </div>
                        <div class="modal-footer justify-content-end gap-2">
                            <button type="button" class="btn-tacsa-cancel" data-bs-dismiss="modal"><i class="bi bi-x-lg"></i> Cancelar</button>
                            <button type="submit" class="btn-tacsa-save"><i class="bi bi-box-arrow-in-down"></i> Registrar Entrada</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Modal: Ver Detalle -->
        <div class="modal fade" id="detailModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-lg modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <div class="section-title"><span class="bar"></span><h5>Detalle del Movimiento</h5></div>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body" id="detailBody"></div>
                    <div class="modal-footer justify-content-end">
                        <button type="button" class="btn-tacsa-cancel" data-bs-dismiss="modal"><i class="bi bi-x-lg"></i> Cerrar</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal: Eliminar -->
        <div class="modal fade" id="deleteModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-sm">
                <div class="modal-content">
                    <div class="modal-body" style="padding:2rem 1.5rem; text-align:center;">
                        <div style="width:64px;height:64px;border-radius:50%;background:var(--tacsa-red-light);display:flex;align-items:center;justify-content:center;margin:0 auto 1.25rem;">
                            <i class="bi bi-trash3" style="font-size:1.75rem;color:var(--tacsa-red);"></i>
                        </div>
                        <h5 style="font-weight:600;">Eliminar Movimiento</h5>
                        <p style="font-size:0.875rem;color:var(--text-secondary);margin:0;">Se eliminará el movimiento <strong id="deleteItemName" style="color:var(--tacsa-red);">MOV-001</strong>. Esta acción no se puede deshacer.</p>
                    </div>
                    <div class="modal-footer justify-content-center gap-2" style="border:none;padding-top:0;">
                        <button type="button" class="btn-tacsa-cancel" data-bs-dismiss="modal"><i class="bi bi-x-lg"></i> Cancelar</button>
                        <button type="button" class="btn-tacsa-save red" onclick="confirmDelete()"><i class="bi bi-trash3"></i> Eliminar</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal: Confirmación Contramovimiento -->
        <div class="modal fade" id="modalReversalConfirm" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header" style="background:linear-gradient(135deg,#fef2f2,#fee2e2);border-left:5px solid #dc2626;">
                        <h5 class="modal-title fw-bold" style="color:#dc2626;">
                            <i class="bi bi-arrow-counterclockwise me-2"></i>Confirmar cancelación de movimiento
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="alert alert-warning">
                            <i class="bi bi-exclamation-triangle-fill me-2"></i>
                            <strong>Esta acción no puede deshacerse.</strong> Se generará un movimiento inverso que cancela el original. El historial queda íntegro.
                        </div>
                        <table class="table table-sm table-bordered mb-0">
                            <tr><td class="text-muted fw-medium" style="width:40%;">Folio original</td><td><strong id="reversalFolioOriginal">-</strong></td></tr>
                            <tr><td class="text-muted fw-medium">Producto</td><td id="reversalProducto">-</td></tr>
                            <tr><td class="text-muted fw-medium">Tipo</td><td id="reversalTipo">-</td></tr>
                            <tr><td class="text-muted fw-medium">Cantidad</td><td><strong id="reversalCantidad">-</strong></td></tr>
                            <tr class="table-warning"><td class="text-muted fw-medium">Folio cancelación</td><td><strong id="reversalFolioNuevo" style="color:#dc2626;">-</strong></td></tr>
                            <tr>
                                <td class="text-muted fw-medium">Motivo</td>
                                <td><input type="text" id="reason-reverse" class="form-control form-control-sm" placeholder="Ingrese el motivo de cancelación" autocomplete="off"></td>
                            </tr>
                        </table>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal"><i class="bi bi-x-circle me-1"></i> Cancelar</button>
                        <button type="button" class="btn btn-danger" id="btnConfirmarReversal" onclick="confirmarReversal()">
                            <i class="bi bi-arrow-counterclockwise me-1"></i> Confirmar cancelación
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal: Advertencia Movimientos Posteriores -->
        <div class="modal fade" id="modalReversalWarning" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-lg modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header" style="background:linear-gradient(135deg,#fefce8,#fef08a);border-left:5px solid #ca8a04;">
                        <h5 class="modal-title fw-bold" style="color:#a16207;">
                            <i class="bi bi-exclamation-triangle-fill me-2"></i>Advertencia — Movimientos Posteriores Detectados
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="alert alert-warning d-flex gap-3 align-items-start">
                            <i class="bi bi-exclamation-triangle-fill fs-4 flex-shrink-0 mt-1"></i>
                            <div>
                                <strong>Este movimiento tiene transacciones posteriores.</strong>
                                <p class="mb-0 mt-1 small">Existen movimientos realizados después que consumieron parte o todas sus unidades. Revertirlo puede generar <strong>stock negativo</strong>.</p>
                            </div>
                        </div>
                        <div class="row g-3 mb-4">
                            <div class="col-md-4">
                                <div style="background:#f8fafc;border:1px solid #e2e8f0;border-radius:8px;padding:14px;text-align:center;">
                                    <div class="text-muted" style="font-size:11px;margin-bottom:4px;">Cantidad a revertir</div>
                                    <div style="font-size:24px;font-weight:700;color:#dc2626;" id="warnCantidadRevertir">-</div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div style="background:#f8fafc;border:1px solid #e2e8f0;border-radius:8px;padding:14px;text-align:center;">
                                    <div class="text-muted" style="font-size:11px;margin-bottom:4px;">Stock actual del lote</div>
                                    <div style="font-size:24px;font-weight:700;color:#1e293b;" id="warnStockActual">-</div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div id="warnStockResultanteCard" style="border-radius:8px;padding:14px;text-align:center;">
                                    <div style="font-size:11px;margin-bottom:4px;">Stock resultante</div>
                                    <div style="font-size:24px;font-weight:700;" id="warnStockResultante">-</div>
                                </div>
                            </div>
                        </div>
                        <div style="font-size:13px;font-weight:600;color:#475569;margin-bottom:8px;"><i class="bi bi-list-ul me-1"></i>Movimientos posteriores afectados:</div>
                        <div class="table-responsive">
                            <table class="table table-sm table-bordered mb-0" style="font-size:13px;">
                                <thead style="background:#f1f5f9;">
                                    <tr><th>Folio</th><th>Tipo</th><th class="text-center">Cantidad</th><th>Fecha</th></tr>
                                </thead>
                                <tbody id="warnMovimientosPosteriores"></tbody>
                            </table>
                        </div>
                        <div class="form-check mt-4 p-3" style="background:#fef2f2;border-radius:8px;border:1px solid #fecaca;">
                            <input class="form-check-input" type="checkbox" id="checkConfirmarRiesgo"
                                onchange="document.getElementById('btnConfirmarConRiesgo').disabled = !this.checked">
                            <label class="form-check-label fw-medium" for="checkConfirmarRiesgo" style="color:#991b1b;">
                                Entiendo que esta acción puede generar stock negativo y acepto la responsabilidad
                            </label>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal"><i class="bi bi-x-circle me-1"></i> Cancelar</button>
                        <button type="button" class="btn btn-warning fw-bold" id="btnConfirmarConRiesgo" disabled onclick="confirmarReversalForzado()">
                            <i class="bi bi-exclamation-triangle me-1"></i> Confirmar de todas formas
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Toast -->
        <div class="tacsa-toast" id="toast">
            <div class="toast-icon success" id="toastIcon"><i class="bi bi-check-lg"></i></div>
            <span id="toastMsg">Operación exitosa</span>
        </div>

    </div>
</main>
@endsection

@push('scripts')
<script>
// ══════════════════════════════════
//  VARIABLES GLOBALES
// ══════════════════════════════════
let reversalMovementId = null;
let reversalCantidad   = null;
let deleteTarget       = null;
let currentReportType  = null;

// ══════════════════════════════════
//  DATA
// ══════════════════════════════════
const allMovements  = @json($movements);
const inventoryData = @json($inventories);
const paginationInfo = @json($paginationInfo);

let filteredMovements = [...allMovements];
let pagination = {
    total:        paginationInfo ? paginationInfo.total    : allMovements.length,
    per_page:     paginationInfo ? paginationInfo.per_page : 15,
    current_page: 1,
    last_page:    Math.ceil(allMovements.length / (paginationInfo ? paginationInfo.per_page : 15))
};

// ── Init ──
renderInventorySelects();
updatePagination();
renderMovements();

// ══════════════════════════════════
//  RENDER TABLA DE MOVIMIENTOS
// ══════════════════════════════════
function renderMovements() {
    const tbody    = document.getElementById('movementsBody');
    const fragment = document.createDocumentFragment();
    tbody.innerHTML = '';

    const start    = (pagination.current_page - 1) * pagination.per_page;
    const pageData = filteredMovements.slice(start, start + pagination.per_page);

    pageData.forEach(m => {
        let badgeClass, badgeIcon, badgeLabel;

        if      (m.movementType === 'IN')              { badgeClass = 'badge-entrada';     badgeIcon = 'bi-box-arrow-in-down'; badgeLabel = 'Entrada'; }
        else if (m.movementType === 'OUT')             { badgeClass = 'badge-salida';      badgeIcon = 'bi-box-arrow-up';      badgeLabel = 'Salida'; }
        else if (m.movementType === 'TRANSFER')        { badgeClass = 'badge-transferencia'; badgeIcon = 'bi-arrow-left-right'; badgeLabel = 'Traslado'; }
        else if (m.movementType === 'RELOCATION')      { badgeClass = 'badge-transferencia'; badgeIcon = 'bi-arrow-left-right'; badgeLabel = 'Reubicación Bodega'; }
        else if (m.movementType === 'SALE')            { badgeClass = 'badge-ajuste';      badgeIcon = 'bi-cash-stack';        badgeLabel = 'Venta'; }
        else if (m.movementType === 'LOCATION_UPDATE') { badgeClass = 'badge-transferencia'; badgeIcon = 'bi-pin-map';          badgeLabel = 'Reubicación Interna'; }
        else                                           { badgeClass = 'badge-ajuste';      badgeIcon = 'bi-arrow-repeat';      badgeLabel = 'Ajuste'; }

        const qtyClass  = m.quantity > 0 ? 'positive' : 'negative';
        const qtyPrefix = m.movementType === 'IN' ? '+' : '-';

        let warehouseDisplay = m.warehousesName;
        if (m.movementType === 'TRANSFER' && m.reason) {
            const match = m.reason.match(/Traslado de\s+(.+?)\s+a\s+(.+?):/);
            if (match) {
                warehouseDisplay = `<span class="transfer-warehouse">${match[1]}</span> <i class="bi bi-arrow-right" style="font-size:0.65rem;margin:0 3px;color:#6d28d9;"></i> <span class="transfer-warehouse-dest">${match[2]}</span>`;
            }
        }
        if (m.movementType === 'RELOCATION'      && m.reason) warehouseDisplay = m.reason;
        if (m.movementType === 'LOCATION_UPDATE' && m.reason) warehouseDisplay = m.reason + ' | Bodega: ' + m.warehousesName;

        const tr = document.createElement('tr');
        tr.innerHTML = `
            <td style="font-weight:600;color:var(--tacsa-red);">${m.folio}</td>
            <td>${m.createdAt}</td>
            <td><span class="${badgeClass}"><i class="bi ${badgeIcon}"></i> ${badgeLabel}</span></td>
            <td>
                <div style="font-weight:500;">${m.productName}</div>
                <div style="font-size:0.6875rem;color:var(--text-secondary);">${m.productId}</div>
            </td>
            <td>${warehouseDisplay}</td>
            <td>R:${m.rack??'SR'} / N:${m.level??'SN'} / M:${m.module??'SM'} / B:${m.bay??'SB'} / T:${m.platform??'ST'}</td>
            <td><span class="cell-qty ${qtyClass}">${qtyPrefix}${m.quantity}</span></td>
            <td><span class="badge-product">${m.lotNumber}</span></td>
            <td><span class="badge-product">${m.movementType === 'SALE' ? (m.invoiceSap||'-') : (m.movementType === 'TRANSFER' ? (m.transferFolio||'-') : '-')}</span></td>
            <td class="text-center">${generarBotonReversal(m)}</td>
        `;
        fragment.appendChild(tr);
    });

    tbody.appendChild(fragment);
}

// ══════════════════════════════════
//  BOTÓN REVERSAL
// ══════════════════════════════════
const tipoLabels = { 'IN':'Entrada','OUT':'Salida','SALE':'Venta','TRANSFER':'Transferencia','RELOCATION':'Reubicación','LOCATION_UPDATE':'Reubicación Interna' };
const tipoInverso = { 'IN':'→ generará OUT (Salida)','OUT':'→ generará IN (Entrada)','SALE':'→ generará IN (Entrada)','TRANSFER':'→ revierte origen y destino' };
const tipoBadge  = { 'IN':'bg-success','OUT':'bg-danger','SALE':'bg-danger','TRANSFER':'bg-primary','RELOCATION':'bg-info text-dark','LOCATION_UPDATE':'bg-info text-dark','ADJUSTMENT':'bg-warning text-dark' };

function generarBotonReversal(mov) {
    const esAdmin = {{ auth()->user()->rol === 'admin' ? 'true' : 'false' }};
    const tiposRevertibles = ['IN','OUT','SALE','TRANSFER','RELOCATION','LOCATION_UPDATE'];
    const tipo             = (mov.movementType || '').toUpperCase();
    const esRevertible     = tiposRevertibles.includes(tipo);
    const estaRevertido    = mov.isReversed;
    const esContramovimiento = (mov.folio || '').startsWith('REV-');

    if (!esAdmin) return '<span class="text-muted small">-</span>';
    if (esContramovimiento) return `<span class="badge bg-secondary"><i class="bi bi-arrow-counterclockwise me-1"></i>Contramovimiento</span>`;
    if (estaRevertido)      return `<span class="badge bg-secondary text-white"><i class="bi bi-check-circle me-1"></i>Revertido</span>`;
    if (!esRevertible)      return '<span class="text-muted small">-</span>';

    return `<button class="btn btn-sm btn-outline-danger reversal-btn"
        data-id="${mov.id}"
        data-folio="${mov.folio}"
        data-tipo="${mov.movementType}"
        data-producto="${mov.productName.replace(/"/g,'&quot;')}"
        data-cantidad="${mov.quantity}">
        <i class="bi bi-arrow-counterclockwise me-1"></i> Revertir
    </button>`;
}

// Delegación de click en la tabla
document.getElementById('movementsBody').addEventListener('click', function(e) {
    const btn = e.target.closest('.reversal-btn');
    if (!btn) return;
    abrirModalReversal(btn.dataset.id, btn.dataset.folio, btn.dataset.tipo, btn.dataset.producto, btn.dataset.cantidad);
});

// ══════════════════════════════════
//  CONTRAMOVIMIENTOS
// ══════════════════════════════════
function abrirModalReversal(movId, folio, tipo, producto, cantidad) {
    reversalMovementId = folio;
    reversalCantidad   = cantidad;

    document.getElementById('reversalFolioOriginal').textContent = folio;
    document.getElementById('reversalProducto').textContent      = producto;
    document.getElementById('reversalTipo').textContent          = `${tipoLabels[tipo]||tipo} ${tipoInverso[tipo]||''}`;
    document.getElementById('reversalCantidad').textContent      = cantidad;
    document.getElementById('reversalFolioNuevo').textContent    = `REV-${folio}`;
    document.getElementById('reason-reverse').value              = '';

    new bootstrap.Modal(document.getElementById('modalReversalConfirm')).show();
}

async function confirmarReversal()        { await ejecutarReversal(false); }
async function confirmarReversalForzado() { await ejecutarReversal(true);  }

async function ejecutarReversal(forceConfirm) {
    const btnNormal  = document.getElementById('btnConfirmarReversal');
    const btnForzado = document.getElementById('btnConfirmarConRiesgo');
    const btn        = forceConfirm ? btnForzado : btnNormal;

    btn.disabled  = true;
    btn.innerHTML = '<div class="spinner-border spinner-border-sm me-2"></div>Procesando...';

    const reason = document.getElementById('reason-reverse').value;

    try {
        const response = await fetch(`/warehouse-movements/movements/${reversalMovementId}/reason/${reason}`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept':       'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({ force_confirm: forceConfirm })
        });

        const data = await response.json();

        if (data.requires_confirm) {
            document.querySelectorAll('.modal.show').forEach(m => bootstrap.Modal.getInstance(m)?.hide());
            setTimeout(() => {
                document.querySelectorAll('.modal-backdrop').forEach(el => el.remove());
                document.body.classList.remove('modal-open');
                document.body.style.removeProperty('overflow');
                document.body.style.removeProperty('padding-right');
                abrirModalWarning(data);
            }, 350);
            return;
        }

        if (data.success) {
            document.querySelectorAll('.modal.show').forEach(m => bootstrap.Modal.getInstance(m)?.hide());
            document.querySelectorAll('.modal-backdrop').forEach(el => el.remove());
            document.body.classList.remove('modal-open');
            document.body.style.removeProperty('overflow');
            document.body.style.removeProperty('padding-right');

            const msg = data.stock_negativo
                ? `${data.reversal_folio} creado. ⚠️ Stock resultante negativo.`
                : `Contramovimiento ${data.reversal_folio} creado correctamente.`;

            mostrarToastReversal(data.stock_negativo ? 'warning' : 'success', msg);
            filterMovements(); // refrescar tabla
        } else {
            mostrarToastReversal('error', data.message);
        }

    } catch (error) {
        mostrarToastReversal('error', 'Error de conexión. Intenta nuevamente.');
        console.error(error);
    } finally {
        btn.disabled  = false;
        btn.innerHTML = forceConfirm
            ? '<i class="bi bi-exclamation-triangle me-1"></i> Confirmar de todas formas'
            : '<i class="bi bi-arrow-counterclockwise me-1"></i> Confirmar cancelación';
    }
}

function abrirModalWarning(data) {
    const resultante     = data.resulting_stock;
    const esNegativo     = resultante < 0;
    const cardResultante = document.getElementById('warnStockResultanteCard');

    cardResultante.style.background = esNegativo ? '#FCEBEB' : '#EAF3DE';
    cardResultante.style.border     = `1px solid ${esNegativo ? '#dc2626' : '#16a34a'}`;

    document.getElementById('warnCantidadRevertir').textContent  = formatNumber(reversalCantidad);
    document.getElementById('warnStockActual').textContent       = formatNumber(data.actual_stock);
    document.getElementById('warnStockResultante').textContent   = formatNumber(resultante);
    document.getElementById('warnStockResultante').style.color   = esNegativo ? '#dc2626' : '#16a34a';

    document.getElementById('warnMovimientosPosteriores').innerHTML = data.posterior_movements.map(m => `
        <tr>
            <td style="font-weight:600;color:var(--tacsa-red);">${m.folio}</td>
            <td><span class="badge ${tipoBadge[m.movementType]||'bg-secondary'}">${tipoLabels[m.movementType]||m.movementType}</span></td>
            <td class="text-center fw-bold">${formatNumber(m.quantity)}</td>
            <td style="font-size:12px;">${m.createdAt}</td>
        </tr>
    `).join('');

    document.getElementById('checkConfirmarRiesgo').checked         = false;
    document.getElementById('btnConfirmarConRiesgo').disabled        = true;

    new bootstrap.Modal(document.getElementById('modalReversalWarning')).show();
}

function formatNumber(num) {
    return new Intl.NumberFormat('es-MX').format(num ?? 0);
}

function mostrarToastReversal(tipo, mensaje) {
    const toastId  = 'toastReversal_' + Date.now();
    const bgClass  = tipo === 'success' ? 'bg-success' : tipo === 'warning' ? 'bg-warning text-dark' : 'bg-danger';
    const icon     = tipo === 'success' ? 'bi-check-circle-fill' : tipo === 'warning' ? 'bi-exclamation-triangle-fill' : 'bi-x-circle-fill';

    document.body.insertAdjacentHTML('beforeend', `
        <div id="${toastId}" class="toast align-items-center text-white ${bgClass} border-0 position-fixed bottom-0 end-0 m-3"
             role="alert" style="z-index:9999;min-width:320px;">
            <div class="d-flex">
                <div class="toast-body"><i class="bi ${icon} me-2"></i>${mensaje}</div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
            </div>
        </div>
    `);
    const toastEl = document.getElementById(toastId);
    new bootstrap.Toast(toastEl, { delay: 5000 }).show();
    toastEl.addEventListener('hidden.bs.toast', () => toastEl.remove());
}

// ══════════════════════════════════
//  PAGINACIÓN
// ══════════════════════════════════
function updatePagination() {
    const from = (pagination.current_page - 1) * pagination.per_page + 1;
    const to   = Math.min(pagination.current_page * pagination.per_page, pagination.total);

    document.getElementById('showingFrom').textContent  = pagination.total > 0 ? from : 0;
    document.getElementById('showingTo').textContent    = to;
    document.getElementById('showingTotal').textContent = pagination.total;
    document.getElementById('prevPageBtn').disabled     = pagination.current_page <= 1;
    document.getElementById('nextPageBtn').disabled     = pagination.current_page >= pagination.last_page;

    const pageNumbers = document.getElementById('pageNumbers');
    pageNumbers.innerHTML = '';

    let startPage = Math.max(1, pagination.current_page - 2);
    let endPage   = Math.min(pagination.last_page, pagination.current_page + 2);
    if (endPage - startPage < 4) {
        if (startPage === 1) endPage = Math.min(5, pagination.last_page);
        else startPage = Math.max(1, pagination.last_page - 4);
    }
    for (let i = startPage; i <= endPage; i++) {
        const btn = document.createElement('button');
        btn.className = `page-btn ${i === pagination.current_page ? 'active' : ''}`;
        btn.textContent = i;
        btn.onclick = () => goToPage(i);
        pageNumbers.appendChild(btn);
    }
}

function changePage(direction) {
    const newPage = pagination.current_page + direction;
    if (newPage >= 1 && newPage <= pagination.last_page) goToPage(newPage);
}

function goToPage(page) {
    pagination.current_page = page;
    updatePagination();
    renderMovements();
    window.scrollTo({ top: 0, behavior: 'smooth' });
}

// ══════════════════════════════════
//  FILTROS
// ══════════════════════════════════
function filterMovements() {
    const search     = document.getElementById('searchMovements').value.toLowerCase();
    const typeFilter = document.getElementById('filterType').value;

    filteredMovements = allMovements.filter(m => {
        const matchesSearch = !search ||
            (m.productName    && m.productName.toLowerCase().includes(search))    ||
            (m.productId      && m.productId.toLowerCase().includes(search))      ||
            (m.lotNumber      && m.lotNumber.toLowerCase().includes(search))      ||
            (m.warehousesName && m.warehousesName.toLowerCase().includes(search)) ||
            (m.folio          && m.folio.toLowerCase().includes(search));

        let typeMatch = !typeFilter;
        if (!typeMatch) {
            if (typeFilter === 'entrada'   && m.movementType === 'IN')         typeMatch = true;
            if (typeFilter === 'salida'    && m.movementType === 'OUT')        typeMatch = true;
            if (typeFilter === 'traslado'  && m.movementType === 'TRANSFER')   typeMatch = true;
            if (typeFilter === 'ajuste'    && m.movementType === 'ADJUSTMENT') typeMatch = true;
            if (typeFilter === 'relocation' && (m.movementType === 'RELOCATION' || m.movementType === 'LOCATION_UPDATE')) typeMatch = true;
            if (typeFilter === 'sales'     && m.movementType === 'SALE')       typeMatch = true;
        }

        return matchesSearch && typeMatch;
    });

    pagination.current_page = 1;
    pagination.total        = filteredMovements.length;
    pagination.last_page    = Math.ceil(filteredMovements.length / pagination.per_page) || 1;
    updatePagination();
    renderMovements();
}

// ══════════════════════════════════
//  TABS
// ══════════════════════════════════
function switchTab(tabId, btn) {
    document.querySelectorAll('.tacsa-tab').forEach(t => t.classList.remove('active'));
    document.querySelectorAll('.tab-content-panel').forEach(p => p.classList.remove('active'));
    btn.classList.add('active');
    document.getElementById('tab-' + tabId).classList.add('active');
}

// ══════════════════════════════════
//  INVENTARIO - SELECTS Y FILTROS
// ══════════════════════════════════
function renderInventorySelects() {
    const productSelect   = document.getElementById('filterProducto');
    const warehouseSelect = document.getElementById('filterBodega');
    const rackSelect      = document.getElementById('filterRack');
    const levelSelect     = document.getElementById('filterNivel');
    const products        = new Set(), warehouses = new Set(), racks = new Set(), levels = new Set();

    inventoryData.forEach(item => {
        if (!products.has(item.productName))   { products.add(item.productName);   productSelect.innerHTML   += `<option value="${item.productName}">${item.productName}</option>`; }
        if (!warehouses.has(item.warehouseName)) { warehouses.add(item.warehouseName); warehouseSelect.innerHTML += `<option value="${item.warehouseName}">${item.warehouseName}</option>`; }
        if (item.rack  && !racks.has(item.rack))   { racks.add(item.rack);   rackSelect.innerHTML  += `<option value="${item.rack}">${item.rack}</option>`; }
        if (item.level && !levels.has(item.level)) { levels.add(item.level); levelSelect.innerHTML += `<option value="${item.level}">${item.level}</option>`; }
    });
}

function filterInventory() {
    const producto = document.getElementById('filterProducto').value;
    const bodega   = document.getElementById('filterBodega').value;
    const rack     = document.getElementById('filterRack').value;
    const nivel    = document.getElementById('filterNivel').value;
    const items    = document.querySelectorAll('.inventory-item');
    const hasFilter = producto || bodega || rack || nivel;
    let visible = 0;

    const oldMsg = document.querySelector('.no-results-filter');
    if (oldMsg) oldMsg.remove();

    items.forEach(item => {
        const show = (!producto || item.dataset.product   === producto) &&
                     (!bodega   || item.dataset.warehouse === bodega)   &&
                     (!rack     || item.dataset.rack      === rack)     &&
                     (!nivel    || item.dataset.level     === nivel);
        item.classList.toggle('hidden-filter', !show);
        if (show) visible++;
    });

    const statusBar = document.getElementById('filterStatus');
    if (hasFilter) {
        statusBar.style.cssText = 'display:flex!important;';
        document.getElementById('filterCount').textContent = visible;
    } else {
        statusBar.style.cssText = 'display:none!important;';
    }

    if (visible === 0 && hasFilter) {
        const msg = document.createElement('div');
        msg.className = 'no-results-filter';
        msg.innerHTML = '<i class="bi bi-search"></i><p>No se encontraron productos con los filtros seleccionados</p>';
        document.querySelector('.inventory-grid').appendChild(msg);
    }
}

function clearFilters() {
    document.getElementById('filterProducto').value = '';
    document.getElementById('filterBodega').value   = '';
    document.getElementById('filterRack').value     = '';
    document.getElementById('filterNivel').value    = '';
    filterInventory();
}

function goToInventoryMovements(inventoryId) {
    const id = parseInt(inventoryId);
    filteredMovements = allMovements.filter(m => m.warehouseInventoryId === id);
    pagination.current_page = 1;
    pagination.total        = filteredMovements.length;
    pagination.last_page    = Math.ceil(filteredMovements.length / pagination.per_page) || 1;
    updatePagination();
    renderMovements();
    const tabBtn = document.querySelector('.tacsa-tab');
    switchTab('historial', tabBtn);
    document.getElementById('tab-historial').scrollIntoView({ behavior: 'smooth' });
}

// ══════════════════════════════════
//  TOAST
// ══════════════════════════════════
function showToast(msg, type) {
    const toast = document.getElementById('toast');
    const icon  = document.getElementById('toastIcon');
    document.getElementById('toastMsg').textContent = msg;
    icon.className  = 'toast-icon ' + type;
    icon.innerHTML  = type === 'success' ? '<i class="bi bi-check-lg"></i>' : '<i class="bi bi-trash3"></i>';
    toast.classList.add('show');
    setTimeout(() => toast.classList.remove('show'), 3000);
}

// ══════════════════════════════════
//  DELETE
// ══════════════════════════════════
function openDelete(id) {
    deleteTarget = id;
    document.getElementById('deleteItemName').textContent = id;
    new bootstrap.Modal(document.getElementById('deleteModal')).show();
}

function confirmDelete() {
    if (!deleteTarget) return;
    bootstrap.Modal.getInstance(document.getElementById('deleteModal')).hide();
    showToast('Movimiento eliminado correctamente', 'error');
    deleteTarget = null;
}

// ══════════════════════════════════
//  REPORTES
// ══════════════════════════════════
const reportTitles = { bodega:'Reporte por Bodega', articulo:'Reporte por Artículo', caducidad:'Reporte por Caducidad', periodo:'Movimientos por Periodo' };

function selectReportType(type) {
    currentReportType = type;
    document.querySelectorAll('.report-type-card').forEach(c => c.classList.remove('selected'));
    document.getElementById('rptType-' + type).classList.add('selected');
    document.getElementById('reportFiltersPanel').style.display = 'block';
    document.getElementById('reportFiltersPanelTitle').textContent = reportTitles[type];
    document.querySelectorAll('.report-filter-group').forEach(g => g.style.display = 'none');
    const groupMap = { bodega:'filtersForBodega', caducidad:'filtersForCaducidad', periodo:'filtersForPeriodo' };
    if (groupMap[type]) document.getElementById(groupMap[type]).style.display = 'block';
    document.getElementById('reportResults').style.display = 'none';
    checkReportFilters();
}

function clearReportSelection() {
    currentReportType = null;
    document.querySelectorAll('.report-type-card').forEach(c => c.classList.remove('selected'));
    document.getElementById('reportFiltersPanel').style.display = 'none';
    document.querySelectorAll('.report-filter-group').forEach(g => g.style.display = 'none');
    document.getElementById('reportResults').style.display = 'none';
}

function checkReportFilters() {
    if (currentReportType === 'periodo') {
        const valid = document.getElementById('rptPeriodoInicio').value !== '' && document.getElementById('rptPeriodoFin').value !== '';
        document.getElementById('btnGenPeriodo').disabled = !valid;
    }
    if (currentReportType === 'bodega') {
        document.getElementById('btnGenBodega').disabled = document.getElementById('rptBodegaSelect').value === '';
    }
}

function generateReportInline(type) {
    if (type === 'caducidad') {
        fetch("{{ route('warehouse-movements.expiration-report') }}")
            .then(r => r.json())
            .then(result => renderReportTable('caducidad', ['Código','Producto','Bodega','Cantidad','Lote','Caducidad','Días Caducado'], result.data, 'Productos Caducados'))
            .catch(console.error);
        return;
    }
    if (type === 'periodo') {
        const formData = new FormData();
        formData.append('fecha_inicio',    document.getElementById('rptPeriodoInicio').value);
        formData.append('fecha_fin',       document.getElementById('rptPeriodoFin').value);
        formData.append('tipo_movimiento', document.getElementById('rptPeriodoTipoInline').value);
        formData.append('warehouse_id',    document.getElementById('rptPeriodoBodega').value);
        fetch("{{ route('warehouse-movements.report') }}", {
            method: 'POST',
            headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content },
            body: formData
        }).then(r => r.json()).then(result => {
            const ini = document.getElementById('rptPeriodoInicio').value;
            const fin = document.getElementById('rptPeriodoFin').value;
            renderReportTable('periodo', ['Folio','Fecha','Tipo','Producto','Bodega','Cantidad','Lote'], result.data, `Movimientos del ${ini} al ${fin}`);
        }).catch(console.error);
    }
}

function renderReportTable(type, headers, data, title) {
    document.getElementById('reportResultsTitle').textContent = title;
    document.getElementById('resultsCount').textContent       = (data?.length || 0) + ' registros encontrados';
    document.getElementById('reportTableHead').innerHTML      = '<tr>' + headers.map(h => `<th>${h}</th>`).join('') + '</tr>';

    if (!data || data.length === 0) {
        document.getElementById('reportTableBody').innerHTML = '';
        document.getElementById('reportEmpty').style.display = 'block';
    } else {
        document.getElementById('reportEmpty').style.display = 'none';
        let rows = '';
        data.forEach(d => {
            if (type === 'caducidad') {
                rows += `<tr><td>${d.productCode}</td><td>${d.productName}</td><td>${d.warehouseName}</td><td>${d.quantity}</td><td>${d.lotNumber}</td><td>${d.expirationDate}</td><td><span class="badge bg-danger">${d.expiredDays||0} días</span></td></tr>`;
            } else if (type === 'periodo') {
                rows += `<tr><td>${d.folio}</td><td>${d.createdAt}</td><td>${d.movementType}</td><td>${d.productName}</td><td>${d.warehousesName}</td><td>${d.quantity}</td><td>${d.lotNumber}</td></tr>`;
            }
        });
        document.getElementById('reportTableBody').innerHTML = rows;
    }
    document.getElementById('reportResults').style.display = 'block';
    document.getElementById('reportResults').scrollIntoView({ behavior: 'smooth', block: 'start' });
}

function formatDate(dateStr) {
    return new Date(dateStr).toLocaleDateString('es-MX', { day:'2-digit', month:'short', year:'numeric' });
}
</script>
@endpush