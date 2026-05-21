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

    .header-actions {
        display: flex;
        gap: 0.75rem;
    }

    .btn-entrada {
        height: 42px;
        padding: 0 1.25rem;
        font-size: 0.8125rem;
        font-weight: 600;
        border-radius: 8px;
        border: none;
        background: var(--tacsa-green);
        color: #ffffff;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        transition: background-color 0.15s;
        cursor: pointer;
    }

    .btn-entrada:hover {
        background: var(--tacsa-green-dark);
        color: #ffffff;
    }

    .btn-salida {
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
    }

    .btn-salida:hover {
        background: var(--tacsa-red-dark);
        color: #ffffff;
    }

    /* ══════════════════════════════════
                                   INLINE REPORTS TAB
                                ══════════════════════════════════ */
    .report-type-selector {
        background: var(--bg-card);
        border: 1px solid var(--border-color);
        border-radius: 12px;
        padding: 1.5rem;
        margin-bottom: 1.5rem;
    }

    .report-intro {
        font-size: 0.875rem;
        color: var(--text-secondary);
        margin-bottom: 1rem;
    }

    .report-types-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 1rem;
    }

    .report-type-card {
        display: flex;
        align-items: flex-start;
        gap: 1rem;
        padding: 1.25rem;
        background: #fafafa;
        border: 1.5px solid var(--border-color);
        border-radius: 10px;
        cursor: pointer;
        transition: all 0.2s ease;
    }

    .report-type-card:hover {
        border-color: var(--tacsa-red);
        background: #fff;
        box-shadow: 0 4px 12px rgba(220, 38, 38, 0.08);
    }

    .report-type-card.selected {
        border-color: var(--tacsa-red);
        background: var(--tacsa-red-light);
        box-shadow: 0 4px 12px rgba(220, 38, 38, 0.12);
    }

    .report-type-icon {
        width: 48px;
        height: 48px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.25rem;
        flex-shrink: 0;
    }

    .report-type-icon.blue {
        background: var(--tacsa-blue-light);
        color: var(--tacsa-blue);
    }

    .report-type-icon.green {
        background: var(--tacsa-green-light);
        color: var(--tacsa-green);
    }

    .report-type-icon.amber {
        background: var(--tacsa-amber-light);
        color: var(--tacsa-amber);
    }

    .report-type-icon.red {
        background: var(--tacsa-red-light);
        color: var(--tacsa-red);
    }

    .report-type-info h6 {
        font-size: 0.9375rem;
        font-weight: 700;
        margin: 0 0 0.25rem;
        color: var(--text-primary);
    }

    .report-type-info p {
        font-size: 0.8125rem;
        color: var(--text-secondary);
        margin: 0;
        line-height: 1.4;
    }

    .report-filters-panel {
        background: var(--bg-card);
        border: 1px solid var(--border-color);
        border-radius: 12px;
        padding: 1.25rem 1.5rem;
        margin-bottom: 1.5rem;
    }

    .report-filters-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1rem;
        padding-bottom: 0.75rem;
        border-bottom: 1px solid var(--border-color);
    }

    .btn-clear-report {
        padding: 0.4rem 0.75rem;
        font-size: 0.8125rem;
        font-weight: 500;
        color: var(--text-secondary);
        background: transparent;
        border: 1px solid var(--border-color);
        border-radius: 6px;
        cursor: pointer;
        transition: all 0.15s;
        display: flex;
        align-items: center;
        gap: 0.35rem;
    }

    .btn-clear-report:hover {
        border-color: var(--tacsa-red);
        color: var(--tacsa-red);
    }

    .btn-generate-report {
        width: 100%;
        padding: 0.625rem 1rem;
        font-size: 0.8125rem;
        font-weight: 600;
        color: #fff;
        background: var(--tacsa-red);
        border: none;
        border-radius: 8px;
        cursor: pointer;
        transition: all 0.15s;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.4rem;
    }

    .btn-generate-report:hover:not(:disabled) {
        background: var(--tacsa-red-dark);
    }

    .btn-generate-report:disabled {
        opacity: 0.5;
        cursor: not-allowed;
    }

    .report-results {
        background: var(--bg-card);
        border: 1px solid var(--border-color);
        border-radius: 12px;
        overflow: hidden;
    }

    .report-results-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 1rem 1.5rem;
        border-bottom: 1px solid var(--border-color);
        background: #fafafa;
    }

    .results-count {
        font-size: 0.8125rem;
        color: var(--text-secondary);
        font-weight: 500;
    }

    .report-empty {
        text-align: center;
        padding: 3rem 1rem;
        color: var(--text-secondary);
    }

    .report-empty i {
        font-size: 2.5rem;
        color: #d1d5db;
        display: block;
        margin-bottom: 0.75rem;
    }

    .report-empty p {
        margin: 0;
        font-size: 0.875rem;
    }

    @media (max-width: 768px) {
        .report-types-grid {
            grid-template-columns: 1fr;
        }

        .report-filters-header {
            flex-direction: column;
            gap: 0.75rem;
            align-items: flex-start;
        }
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

    .stat-icon.purple {
        background: rgba(139, 92, 246, 0.15);
        color: #8B5CF6;
    }

    .stat-icon.orange {
        background: rgba(249, 115, 22, 0.15);
        color: #F97316;
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
                                   TABS
                                ══════════════════════════════════ */
    .tacsa-tabs {
        display: flex;
        gap: 0;
        margin-bottom: 1.5rem;
        border-bottom: 2px solid var(--border-color);
    }

    .tacsa-tab {
        padding: 0.75rem 1.5rem;
        font-size: 0.875rem;
        font-weight: 500;
        color: var(--text-secondary);
        background: none;
        border: none;
        cursor: pointer;
        position: relative;
        transition: color 0.15s;
    }

    .tacsa-tab:hover {
        color: var(--text-primary);
    }

    .tacsa-tab.active {
        color: var(--tacsa-red);
        font-weight: 600;
    }

    .tacsa-tab.active::after {
        content: '';
        position: absolute;
        bottom: -2px;
        left: 0;
        right: 0;
        height: 2px;
        background: var(--tacsa-red);
        border-radius: 1px;
    }

    /* ══════════════════════════════════
                                   TAB CONTENT
                                ══════════════════════════════════ */
    .tab-content-panel {
        display: none;
    }

    .tab-content-panel.active {
        display: block;
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

    .filter-label {
        display: block;
        font-size: 0.75rem;
        font-weight: 600;
        color: var(--text-secondary);
        text-transform: uppercase;
        letter-spacing: 0.03em;
        margin-bottom: 0.375rem;
    }

    .filter-result-text {
        font-size: 0.8125rem;
        color: var(--text-secondary);
        font-weight: 500;
    }

    .inventory-item.hidden-filter {
        display: none !important;
    }

    .no-results-filter {
        grid-column: 1 / -1;
        text-align: center;
        padding: 3rem 1rem;
        color: var(--text-secondary);
    }

    .no-results-filter i {
        font-size: 2.5rem;
        color: #d1d5db;
        display: block;
        margin-bottom: 0.75rem;
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

    /* ── Badges ── */
    .badge-entrada {
        display: inline-flex;
        align-items: center;
        gap: 0.375rem;
        padding: 0.25rem 0.75rem;
        font-size: 0.6875rem;
        font-weight: 600;
        border-radius: 9999px;
        background: var(--tacsa-green-light);
        color: var(--tacsa-green);
    }

    .badge-entrada i {
        font-size: 0.75rem;
    }

    .badge-salida {
        display: inline-flex;
        align-items: center;
        gap: 0.375rem;
        padding: 0.25rem 0.75rem;
        font-size: 0.6875rem;
        font-weight: 600;
        border-radius: 9999px;
        background: var(--tacsa-red-light);
        color: var(--tacsa-red);
    }

    .badge-salida i {
        font-size: 0.75rem;
    }

    .badge-ajuste {
        display: inline-flex;
        align-items: center;
        gap: 0.375rem;
        padding: 0.25rem 0.75rem;
        font-size: 0.6875rem;
        font-weight: 600;
        border-radius: 9999px;
        background: var(--tacsa-amber-light);
        color: var(--tacsa-amber);
    }

    .badge-ajuste i {
        font-size: 0.75rem;
    }

    .badge-transferencia-in {
        display: inline-flex;
        align-items: center;
        gap: 0.375rem;
        padding: 0.25rem 0.75rem;
        font-size: 0.6875rem;
        font-weight: 600;
        border-radius: 9999px;
        background: var(--tacsa-blue-light);
        color: var(--tacsa-blue);
    }

    .badge-transferencia-out {
        display: inline-flex;
        align-items: center;
        gap: 0.375rem;
        padding: 0.25rem 0.75rem;
        font-size: 0.6875rem;
        font-weight: 600;
        border-radius: 9999px;
        background: rgba(124, 58, 237, 0.1);
        color: #7c3aed;
    }

    .badge-traslado {
        display: inline-flex;
        align-items: center;
        gap: 0.375rem;
        padding: 0.25rem 0.75rem;
        font-size: 0.6875rem;
        font-weight: 600;
        border-radius: 9999px;
        background: rgba(124, 58, 237, 0.15);
        color: #6d28d9;
    }

    .transfer-warehouse {
        color: var(--tacsa-red);
        font-weight: 500;
    }

    .transfer-warehouse-dest {
        color: #6d28d9;
        font-weight: 600;
    }

    .badge-product {
        display: inline-block;
        padding: 0.25rem 0.625rem;
        font-size: 0.6875rem;
        font-weight: 600;
        border-radius: 9999px;
        background: var(--tacsa-blue-light);
        color: var(--tacsa-blue);
    }

    .cell-qty {
        font-weight: 700;
        font-size: 0.875rem;
    }

    .cell-qty.positive {
        color: var(--tacsa-green);
    }

    .cell-qty.negative {
        color: var(--tacsa-red);
    }

    /* ── Action buttons ── */
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

    .page-btn:disabled {
        opacity: 0.5;
        cursor: not-allowed;
    }

    /* ══════════════════════════════════
                                   EMPTY STATE
                                ══════════════════════════════════ */
    .empty-state {
        text-align: center;
        padding: 4rem 2rem;
    }

    .empty-icon {
        width: 72px;
        height: 72px;
        border-radius: 50%;
        background: #f3f4f6;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 1.25rem;
    }

    .empty-icon i {
        font-size: 2rem;
        color: #9ca3af;
    }

    .empty-state h4 {
        font-size: 1rem;
        font-weight: 600;
        margin-bottom: 0.375rem;
        color: var(--text-primary);
    }

    .empty-state p {
        font-size: 0.875rem;
        color: var(--text-secondary);
        margin: 0;
    }

    /* ══════════════════════════════════
                                   INVENTORY TAB - CARDS
                                ══════════════════════════════════ */
    .inventory-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 1rem;
    }

    .inventory-item {
        background: var(--bg-card);
        border: 1px solid var(--border-color);
        border-radius: 10px;
        padding: 1.25rem;
        transition: box-shadow 0.15s, border-color 0.15s;
    }

    .inventory-item:hover {
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.06);
        border-color: #d1d5db;
    }

    .inventory-item-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 1rem;
    }

    .inventory-item-header h5 {
        font-size: 0.875rem;
        font-weight: 600;
        margin: 0;
        color: var(--text-primary);
    }

    .inventory-item-row {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 0.5rem 0;
        border-top: 1px solid #f3f4f6;
    }

    .inventory-item-row .label {
        font-size: 0.75rem;
        color: var(--text-secondary);
    }

    .inventory-item-row .value {
        font-size: 0.8125rem;
        font-weight: 600;
        color: var(--text-primary);
    }

    .stock-bar-wrapper {
        margin-top: 0.75rem;
    }

    .stock-bar-label {
        display: flex;
        justify-content: space-between;
        font-size: 0.6875rem;
        color: var(--text-secondary);
        margin-bottom: 0.375rem;
    }

    .stock-bar {
        height: 6px;
        background: #f3f4f6;
        border-radius: 9999px;
        overflow: hidden;
    }

    .stock-bar-fill {
        height: 100%;
        border-radius: 9999px;
        transition: width 0.3s;
    }

    .stock-bar-fill.high {
        background: var(--tacsa-green);
    }

    .stock-bar-fill.medium {
        background: var(--tacsa-amber);
    }

    .stock-bar-fill.low {
        background: var(--tacsa-red);
    }

    /* ══════════════════════════════════
                                   MODALS
                                ══════════════════════════════════ */
    .modal {
        display: none;
        background-color: rgba(0, 0, 0, 0.5);
    }

    .modal.show {
        display: block !important;
    }

    .modal.show .modal-dialog {
        display: flex !important;
        align-items: center;
        justify-content: center;
        min-height: 100vh;
    }

    .modal-backdrop {
        background-color: rgba(0, 0, 0, 0.5);
    }

    .modal-content {
        border: none;
        border-radius: 12px;
        position: relative;
        z-index: 1060;
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

    /* ── Form fields ── */
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
    .tacsa-select,
    .tacsa-textarea {
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

    .tacsa-textarea {
        height: auto;
        padding: 0.75rem 1rem;
        resize: vertical;
        min-height: 80px;
    }

    .tacsa-input::placeholder,
    .tacsa-textarea::placeholder {
        color: #9ca3af;
    }

    .tacsa-input:focus,
    .tacsa-select:focus,
    .tacsa-textarea:focus {
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
        background: var(--tacsa-green);
        color: #ffffff;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        cursor: pointer;
        transition: background 0.15s;
    }

    .btn-tacsa-save:hover {
        background: var(--tacsa-green-dark);
    }

    .btn-tacsa-save.red {
        background: var(--tacsa-red);
    }

    .btn-tacsa-save.red:hover {
        background: var(--tacsa-red-dark);
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

    /* ══════════════════════════════════
                                   TOAST
                                ══════════════════════════════════ */
    .tacsa-toast {
        position: fixed;
        bottom: 2rem;
        right: 2rem;
        background: var(--bg-card);
        border: 1px solid var(--border-color);
        border-radius: 10px;
        padding: 1rem 1.25rem;
        box-shadow: 0 8px 24px rgba(0, 0, 0, 0.12);
        display: flex;
        align-items: center;
        gap: 0.75rem;
        font-size: 0.8125rem;
        font-weight: 500;
        z-index: 9999;
        transform: translateY(120%);
        opacity: 0;
        transition: all 0.3s ease;
    }

    .tacsa-toast.show {
        transform: translateY(0);
        opacity: 1;
    }

    .tacsa-toast .toast-icon {
        width: 32px;
        height: 32px;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1rem;
        flex-shrink: 0;
    }

    .tacsa-toast .toast-icon.success {
        background: var(--tacsa-green-light);
        color: var(--tacsa-green);
    }

    .tacsa-toast .toast-icon.error {
        background: var(--tacsa-red-light);
        color: var(--tacsa-red);
    }

    /* ══════════════════════════════════
                                   FOOTER
                                ══════════════════════════════════ */
    .site-footer {
        border-top: 2px solid var(--tacsa-red);
        text-align: center;
        padding: 1.25rem;
        font-size: 0.75rem;
        color: var(--text-secondary);
        margin-top: 2rem;
    }



    /* 3. El Sidebar */

    /* ══════════════════════════════════
                                   RESPONSIVE
                                ══════════════════════════════════ */
    @media (max-width: 992px) {
        .stat-cards {
            grid-template-columns: repeat(2, 1fr);
        }

        .inventory-grid {
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

        .inventory-grid {
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

        .header-actions {
            width: 100%;
        }

        .header-actions .btn-entrada,
        .header-actions .btn-salida {
            flex: 1;
            justify-content: center;
        }

        .toolbar-actions {
            width: 100%;
            flex-wrap: wrap;
        }

        .search-box input {
            width: 100%;
        }

        .table-footer {
            flex-direction: column;
            gap: 0.75rem;
        }
    }
</style>

@section('title', 'TACSA - Movimientos de Inventario')
@section('contenido')
    <main id="main" class="main bg-light py-4">
        <div class="container-fluid">

            <!-- ══════════ PAGE CONTENT ══════════ -->
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
                        <div class="stat-icon blue">
                            <i class="bi bi-arrow-left-right"></i>
                        </div>
                        <div class="stat-info">
                            <h3>{{ $movementsTotal }}</h3>
                            <span>Total Movimientos</span>
                        </div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-icon green">
                            <i class="bi bi-box-arrow-in-down"></i>
                        </div>
                        <div class="stat-info">
                            <h3>{{ $movementsTotalIN }}</h3>
                            <span>Entradas</span>
                        </div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-icon red">
                            <i class="bi bi-box-arrow-up"></i>
                        </div>
                        <div class="stat-info">
                            <h3>{{ $movementsTotalOUT }}</h3>
                            <span>Salidas</span>
                        </div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-icon purple">
                            <i class="bi bi-arrows-move"></i>
                        </div>
                        <div class="stat-info">
                            <h3>{{ $movementsTotalTRANSFER }}</h3>
                            <span>Traslados</span>
                        </div>
                    </div>

                    <div class="stat-card">
                        <div class="stat-icon purple">
                            <i class="bi-box-arrow-in-right"></i>
                        </div>
                        <div class="stat-info">
                            <h3>{{ $movementsTotalRELOCATION }}</h3>
                            <span>Re ubicaciones</span>
                        </div>
                    </div>

                    <div class="stat-card">
                        <div class="stat-icon orange">
                            <i class="bi bi-pencil-square"></i>
                        </div>
                        <div class="stat-info">
                            <h3>{{ $movementsTotalADJUSTMENT }}</h3>
                            <span>Ajustes</span>
                        </div>
                    </div>

                    <div class="stat-card">
                        <div class="stat-icon orange">
                            <i class="bi bi-cash-stack"></i>
                        </div>
                        <div class="stat-info">
                            <h3>{{ $movementsTotalSALE }}</h3>
                            <span>Ventas</span>
                        </div>
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

                <!-- ═══════ TAB 1: HISTORIAL ═══════ -->
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
                                    <input type="text" id="searchMovements" placeholder="Buscar por producto, lote..."
                                        oninput="filterMovements()">
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
                                        <th>Cantidad</th>
                                        <th>Lote</th>
                                    </tr>
                                </thead>
                                <tbody id="movementsBody">
                                    <!-- Rows injected by JS -->
                                </tbody>
                            </table>
                        </div>
                        <!-- Empty state (hidden when there are rows) -->
                        <div class="empty-state" id="emptyHistorial" style="display:none;">
                            <div class="empty-icon">
                                <i class="bi bi-arrow-left-right"></i>
                            </div>
                            <h4>No hay movimientos registrados</h4>
                            <p>Los movimientos de entrada y salida se mostraran aqui</p>
                        </div>
                        <div class="table-footer" id="historialFooter">
                            <span>Mostrando <strong id="showingFrom">1</strong>-<strong id="showingTo">15</strong> de
                                <strong id="showingTotal">0</strong> movimientos</span>
                            <div class="pagination-btns" id="paginationBtns">
                                <button class="page-btn" id="prevPageBtn" onclick="changePage(-1)" disabled><i
                                        class="bi bi-chevron-left"></i></button>
                                <span id="pageNumbers"></span>
                                <button class="page-btn" id="nextPageBtn" onclick="changePage(1)"><i
                                        class="bi bi-chevron-right"></i></button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- ═══════ TAB 2: INVENTARIO ACTUAL ═══════ -->
                <div class="tab-content-panel" id="tab-inventario">
                    <div class="table-card" style="border:none; background:transparent; overflow:visible;">
                        <div class="table-toolbar"
                            style="border-radius:12px 12px 0 0; background: var(--bg-card); border: 1px solid var(--border-color); border-bottom: 1px solid var(--border-color);">
                            <div class="section-title">
                                <span class="bar"></span>
                                <h2>Stock por Producto</h2>
                            </div>
                        </div>

                        <div class="inventory-filters"
                            style="padding:1rem 1.5rem; border:1px solid var(--border-color); border-top:none; background:var(--bg-card);">
                            <div class="row g-3">
                                <div class="col-md-3 col-sm-6">
                                    <label class="filter-label">Producto</label>
                                    <select class="form-select filter-select" id="filterProducto"
                                        onchange="filterInventory()">
                                        <option value="">Todos los productos</option>
                                    </select>
                                </div>
                                <div class="col-md-3 col-sm-6">
                                    <label class="filter-label">Bodega</label>
                                    <select class="form-select filter-select" id="filterBodega"
                                        onchange="filterInventory()">
                                        <option value="">Todas las bodegas</option>
                                    </select>
                                </div>
                                <div class="col-md-3 col-sm-6">
                                    <label class="filter-label">Rack</label>
                                    <select class="form-select filter-select" id="filterRack"
                                        onchange="filterInventory()">
                                        <option value="">Todos los racks</option>

                                    </select>
                                </div>
                                <div class="col-md-3 col-sm-6">
                                    <label class="filter-label">Nivel</label>
                                    <select class="form-select filter-select" id="filterNivel"
                                        onchange="filterInventory()">
                                        <option value="">Todos los niveles</option>
                                    </select>
                                </div>
                            </div>
                            <div class="d-flex align-items-center justify-content-between mt-3" id="filterStatus"
                                style="display:none!important;">
                                <span class="filter-result-text"><i class="bi bi-funnel-fill"
                                        style="color:var(--tacsa-red);"></i> <span id="filterCount">6</span> productos
                                    encontrados</span>
                                <button class="btn btn-sm btn-outline-secondary" onclick="clearFilters()"
                                    style="font-size:0.8rem; border-radius:8px;">
                                    <i class="bi bi-x-lg"></i> Limpiar filtros
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="inventory-grid" style="margin-top:1rem;">
                        <!-- Product 1 -->
                        @foreach ($inventories as $item)
                            <div class="inventory-item" data-product="{{ $item->getProductName() }}"
                                data-warehouse="{{ $item->getWarehouseName() }}" data-rack="{{ $item->getRack() }}"
                                data-level="{{ $item->getLevel() }}">
                                <div class="inventory-item-header">
                                    <h5>{{ $item->getProductName() }}</h5>
                                    <span class="badge-product">{{ $item->getProductCode() }}</span>
                                </div>
                                <div class="inventory-item-row">
                                    <span class="label">Bodega</span>
                                    <span class="value">{{ $item->getWarehouseName() }}</span>
                                </div>
                                <div class="inventory-item-row">
                                    <span class="label">Rack / Nivel</span>
                                    <span class="value">{{ $item->getRack() }} / Nivel
                                        {{ $item->getLevel() }}</span>
                                </div>

                                <div class="inventory-item-row">
                                    <span class="label">Bahía / Modulo</span>
                                    <span class="value">{{ $item->getBay() }} / Modulo
                                        {{ $item->getModule() }}</span>
                                </div>
                                <div class="inventory-item-row">
                                    <span class="label">Tarima</span>
                                    <span class="value" style="color:var(--tacsa-green);">{{ $item->getPlatform() }}</span>
                                </div>
                                <div class="inventory-item-row">
                                    <span class="label">Stock Actual</span>
                                    <span class="value" style="color:var(--tacsa-green);">{{ $item->getStock() }}
                                        pzs</span>
                                </div>
                                <div class="inventory-item-row">
                                    <span class="label">Fecha de caducidad</span>
                                    <span class="value"
                                        style="color:red;">{{ substr($item->getExpirationDate(), 0, 10) }}</span>
                                </div>

                                <div class="inventory-item-row">
                                    <span class="label">No. de lote</span>
                                    <span class="value"
                                        style="color:rgb(7, 7, 7);">{{ $item->getLotNumber() }}</span>
                                </div>

                                <div class="inventory-item-row">
                                    <span class="label">Fecha de fabricación</span>
                                    <span class="value"
                                        style="color:rgb(7, 7, 7);">{{ $item->getManufacturingDate() }}</span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

            </div>

            <div class="tab-content-panel" id="tab-reportes">

                <!-- Report Type Selection -->
                <div class="report-type-selector">
                    <p class="report-intro">Seleccione el tipo de reporte que desea generar:</p>
                    <div class="report-types-grid">
                        <div class="report-type-card" onclick="selectReportType('bodega')" id="rptType-bodega">
                            <div class="report-type-icon blue">
                                <i class="bi bi-building"></i>
                            </div>
                            <div class="report-type-info">
                                <h6>Por Bodega</h6>
                                <p>Inventario completo de una bodega con detalles de productos y ubicaciones</p>
                            </div>
                        </div>
                        <div class="report-type-card" onclick="selectReportType('articulo')" id="rptType-articulo">
                            <div class="report-type-icon green">
                                <i class="bi bi-box-seam"></i>
                            </div>
                            <div class="report-type-info">
                                <h6>Por Articulo</h6>
                                <p>Movimientos y existencias de un producto en todos los almacenes</p>
                            </div>
                        </div>
                        <div class="report-type-card" onclick="selectReportType('caducidad')" id="rptType-caducidad">
                            <div class="report-type-icon amber">
                                <i class="bi bi-exclamation-triangle"></i>
                            </div>
                            <div class="report-type-info">
                                <h6>Por Caducidad</h6>
                                <p>Productos proximos a caducar o ya caducados para tomar accion</p>
                            </div>
                        </div>
                        <div class="report-type-card" onclick="selectReportType('periodo')" id="rptType-periodo">
                            <div class="report-type-icon red">
                                <i class="bi bi-calendar-range"></i>
                            </div>
                            <div class="report-type-info">
                                <h6>Movimientos por Periodo</h6>
                                <p>Entradas, salidas y ajustes dentro de un rango de fechas</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Report Filters Panel (dynamic) -->
                <div class="report-filters-panel" id="reportFiltersPanel" style="display:none;">
                    <div class="report-filters-header">
                        <div class="section-title">
                            <span class="bar"></span>
                            <h5 id="reportFiltersPanelTitle">Filtros del Reporte</h5>
                        </div>
                        <button type="button" class="btn-clear-report" onclick="clearReportSelection()">
                            <i class="bi bi-arrow-left"></i> Cambiar tipo
                        </button>
                    </div>

                    <!-- Filters for Bodega -->
                    <div class="report-filter-group" id="filtersForBodega" style="display:none;">
                        <div class="row g-3 align-items-end">
                            <div class="col-md-4">
                                <label class="field-label">Bodega <span class="required">*</span></label>
                                <select class="tacsa-select" id="rptBodegaSelect" onchange="checkReportFilters()">
                                    <option value="">Seleccione una bodega</option>
                                    <option value="13">Almacen Central</option>
                                    <option value="14">Almacen Norte</option>
                                    <option value="15">Almacen Sur</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label class="field-label">Rack (opcional)</label>
                                <select class="tacsa-select" id="rptBodegaRack">
                                    <option value="">Todos</option>
                                    <option value="R-01">R-01</option>
                                    <option value="R-02">R-02</option>
                                    <option value="R-05">R-05</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label class="field-label">Nivel (opcional)</label>
                                <select class="tacsa-select" id="rptBodegaNivel">
                                    <option value="">Todos</option>
                                    <option value="1">Nivel 1</option>
                                    <option value="2">Nivel 2</option>
                                    <option value="3">Nivel 3</option>
                                    <option value="4">Nivel 4</option>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <button type="button" class="btn-generate-report" id="btnGenBodega"
                                    onclick="generateReportInline('bodega')" disabled>
                                    <i class="bi bi-search"></i> Consultar
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Filters for Articulo -->
                    <div class="report-filter-group" id="filtersForArticulo" style="display:none;">
                        <div class="row g-3 align-items-end">
                            <div class="col-md-5">
                                <label class="field-label">Producto <span class="required">*</span></label>
                                <select class="tacsa-select" id="rptArticuloSelect" onchange="checkReportFilters()">
                                    <option value="">Seleccione un producto</option>
                                    <option value="mf019">Tacsa Power; 50 kgs.</option>
                                    <option value="pi004">Anibac Plus; 20 lts.</option>
                                    <option value="fo048">Agrovida; 1 lt.</option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="field-label">Bodega (opcional)</label>
                                <select class="tacsa-select" id="rptArticuloBodega">
                                    <option value="">Todas</option>
                                    <option value="13">Almacen Central</option>
                                    <option value="14">Almacen Norte</option>
                                    <option value="15">Almacen Sur</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <button type="button" class="btn-generate-report" id="btnGenArticulo"
                                    onclick="generateReportInline('articulo')" disabled>
                                    <i class="bi bi-search"></i> Consultar
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Filters for Caducidad -->
                    <div class="report-filter-group" id="filtersForCaducidad" style="display:none;">
                        <div class="row g-3 align-items-end">
                            <div class="col-md-4">
                                <label class="field-label">Productos ya caducados</label>
                                <p class="text-muted small mb-0">Se mostrarán todos los productos cuya fecha de
                                    caducidad
                                    haya
                                    pasado</p>
                            </div>
                            <div class="col-md-4">
                                <button type="button" class="btn-generate-report" id="btnGenCaducidad"
                                    onclick="generateReportInline('caducidad')">
                                    <i class="bi bi-search"></i> Consultar
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Filters for Periodo -->
                    <div class="report-filter-group" id="filtersForPeriodo" style="display:none;">
                        <div class="row g-3 align-items-end">
                            <div class="col-md-2">
                                <label class="field-label">Fecha inicio <span class="required">*</span></label>
                                <input type="date" class="tacsa-input" id="rptPeriodoInicio"
                                    onchange="checkReportFilters()">
                            </div>
                            <div class="col-md-2">
                                <label class="field-label">Fecha fin <span class="required">*</span></label>
                                <input type="date" class="tacsa-input" id="rptPeriodoFin"
                                    onchange="checkReportFilters()">
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
                                        <option value="{{ $warehouse->getId() }}">
                                            {{ $warehouse->getWarehouseName() }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-2">
                                <button type="button" class="btn-generate-report" id="btnGenPeriodo"
                                    onclick="generateReportInline('periodo')" disabled>
                                    <i class="bi bi-search"></i> Consultar
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Report Results Area -->
                <div class="report-results" id="reportResults" style="display:none;">
                    <div class="report-results-header">
                        <div class="section-title">
                            <span class="bar"></span>
                            <h5 id="reportResultsTitle">Resultados</h5>
                        </div>
                        <div class="report-results-actions">
                            <span class="results-count" id="resultsCount">0 registros encontrados</span>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="tacsa-table" id="reportTable">
                            <thead id="reportTableHead">
                                <!-- Dynamic headers -->
                            </thead>
                            <tbody id="reportTableBody">
                                <!-- Dynamic rows -->
                            </tbody>
                        </table>
                    </div>
                    <div class="report-empty" id="reportEmpty" style="display:none;">
                        <i class="bi bi-inbox"></i>
                        <p>No se encontraron registros con los filtros seleccionados</p>
                    </div>
                </div>

            </div>

            <!-- Footer -->
            <div class="site-footer">
                &copy; 2026 Copyright &copy; Tacsa . Sistemas TACSA Todos los derechos reservados.
            </div>

            <!-- ══════════════════════════════════════════════
                                     MODAL: NUEVA ENTRADA
                                ══════════════════════════════════════════════ -->
            <div class="modal fade" id="entradaModal" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-lg modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header">
                            <div class="section-title">
                                <span class="bar"></span>
                                <h5>Registrar Entrada</h5>
                            </div>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                aria-label="Cerrar"></button>
                        </div>
                        <form id="entradaForm" onsubmit="return handleEntrada(event)">
                            <div class="modal-body" id="modalContent">
                                <div class="text-center p-4">
                                    <div class="spinner-border"></div>
                                    <p class="mt-2">Cargando información...</p>
                                </div>
                                <div class="modal-footer justify-content-end gap-2">
                                    <button type="button" class="btn-tacsa-cancel" data-bs-dismiss="modal">
                                        <i class="bi bi-x-lg"></i> Cancelar
                                    </button>
                                    <button type="submit" class="btn-tacsa-save">
                                        <i class="bi bi-box-arrow-in-down"></i> Registrar Entrada
                                    </button>
                                </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- ══════════════════════════════════════════════
                                     MODAL: NUEVA SALIDA
                                ══════════════════════════════════════════════ -->
            <div class="modal fade" id="salidaModal" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-lg modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header">
                            <div class="section-title">
                                <span class="bar"></span>
                                <h5>Registrar Salida</h5>
                            </div>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                aria-label="Cerrar"></button>
                        </div>
                        <form id="salidaForm" onsubmit="return handleSalida(event)">
                            <div class="modal-body">
                                <div class="modal-section-title">
                                    <span class="bar"></span>
                                    <span>Producto y Almacen</span>
                                </div>
                                <div class="row g-3 mb-3">
                                    <div class="col-md-6">
                                        <label class="field-label">Producto <span class="required">*</span></label>
                                        <select class="tacsa-select" required>
                                            <option value="">Seleccione un producto</option>
                                            <option value="1">SKU-001 - Tornillo Hex 1/4"</option>
                                            <option value="2">SKU-002 - Tuerca M8</option>
                                            <option value="3">SKU-003 - Arandela Plana 3/8"</option>
                                            <option value="4">SKU-004 - Varilla Roscada 5/16"</option>
                                            <option value="5">SKU-005 - Clavo 2.5"</option>
                                        </select>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="field-label">Almacen <span class="required">*</span></label>
                                        <select class="tacsa-select" required>
                                            <option value="">Seleccione un almacen</option>
                                            <option value="1">ALM-001 - Almacen Central</option>
                                            <option value="2">ALM-002 - Almacen Norte</option>
                                            <option value="3">ALM-003 - Almacen Sur</option>
                                        </select>
                                    </div>
                                </div>

                                <hr class="section-separator">

                                <div class="modal-section-title">
                                    <span class="bar"></span>
                                    <span>Detalle de Salida</span>
                                </div>
                                <div class="row g-3 mb-3">
                                    <div class="col-md-6">
                                        <label class="field-label">Cantidad <span class="required">*</span></label>
                                        <input type="number" class="tacsa-input" placeholder="0" min="1"
                                            required>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="field-label">No. de Lote</label>
                                        <input type="text" class="tacsa-input" placeholder="Ej: LOT-2026-001">
                                    </div>
                                </div>

                                <hr class="section-separator">

                                <div class="modal-section-title">
                                    <span class="bar"></span>
                                    <span>Motivo de Salida</span>
                                </div>
                                <div class="mb-0">
                                    <label class="field-label">Motivo <span class="required">*</span></label>
                                    <textarea class="tacsa-textarea" placeholder="Describa el motivo de la salida..." rows="3" required></textarea>
                                </div>
                            </div>
                            <div class="modal-footer justify-content-end gap-2">
                                <button type="button" class="btn-tacsa-cancel" data-bs-dismiss="modal">
                                    <i class="bi bi-x-lg"></i> Cancelar
                                </button>
                                <button type="submit" class="btn-tacsa-save red">
                                    <i class="bi bi-box-arrow-up"></i> Registrar Salida
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- ══════════════════════════════════════════════
                                     MODAL: VER DETALLE
                                 ══════════════════════════════════════════════ -->
            <div class="modal fade" id="detailModal" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-lg modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header">
                            <div class="section-title">
                                <span class="bar"></span>
                                <h5>Detalle del Movimiento</h5>
                            </div>
                            <button type="button" class="btn-close" onclick="closeDetailModal()"
                                aria-label="Cerrar"></button>
                        </div>
                        <div class="modal-body" id="detailBody">
                            <!-- Filled by JS -->
                        </div>
                        <div class="modal-footer justify-content-end">
                            <button type="button" class="btn-tacsa-cancel" onclick="closeDetailModal()">
                                <i class="bi bi-x-lg"></i> Cerrar
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- ══════════════════════════════════════════════
                                     MODAL: ELIMINAR
                                ══════════════════════════════════════════════ -->
            <div class="modal fade" id="deleteModal" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered modal-sm">
                    <div class="modal-content">
                        <div class="modal-body" style="padding:2rem 1.5rem;">
                            <div class="delete-icon-wrapper">
                                <i class="bi bi-trash3"></i>
                            </div>
                            <div class="delete-text">
                                <h5>Eliminar Movimiento</h5>
                                <p>Se eliminara el movimiento <span class="item-name" id="deleteItemName">MOV-001</span>.
                                    Esta
                                    accion no se puede deshacer.</p>
                            </div>
                        </div>
                        <div class="modal-footer justify-content-center gap-2" style="border:none; padding-top:0;">
                            <button type="button" class="btn-tacsa-cancel" data-bs-dismiss="modal">
                                <i class="bi bi-x-lg"></i> Cancelar
                            </button>
                            <button type="button" class="btn-tacsa-save red" onclick="confirmDelete()">
                                <i class="bi bi-trash3"></i> Eliminar
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- TOAST -->
            <div class="tacsa-toast" id="toast">
                <div class="toast-icon success" id="toastIcon">
                    <i class="bi bi-check-lg"></i>
                </div>
                <span id="toastMsg">Movimiento registrado exitosamente</span>
            </div>

        </div>
    </main>

@endsection
@push('scripts')
    
    <script>
        // ══════════════════════════════════
        //  DATA
        // ══════════════════════════════════
        const allMovements = @json($movements);
        const inventoryData = @json($inventories);
        const paginationInfo = @json($paginationInfo);

        let filteredMovements = [...allMovements];
        let pagination = {
            total: paginationInfo ? paginationInfo.total : allMovements.length,
            per_page: paginationInfo ? paginationInfo.per_page : 15,
            current_page: 1,
            last_page: Math.ceil(allMovements.length / (paginationInfo ? paginationInfo.per_page : 15))
        };

        let deleteTarget = null;
        renderInventorySelects();
        updatePagination();
        renderMovements();



        // ══════════════════════════════════
        //  RENDER TABLE OF INVENTORY
        // ══════════════════════════════════
        function renderInventorySelects() {
            const productSelect = document.getElementById("filterProducto");
            const warehouseSelect = document.getElementById("filterBodega");
            const rackSelect = document.getElementById("filterRack");
            const levelSelect = document.getElementById("filterNivel");

            const products = new Set();
            const warehouses = new Set();
            const racks = new Set();
            const levels = new Set();

            inventoryData.forEach(item => {

                if (!products.has(item.productName)) {
                    products.add(item.productName);
                    productSelect.innerHTML += `<option value="${item.productName}">${item.productName}</option>`;
                }

                if (!warehouses.has(item.warehouseName)) {
                    warehouses.add(item.warehouseName);
                    warehouseSelect.innerHTML +=
                        `<option value="${item.warehouseName}">${item.warehouseName}</option>`;
                }

                if (item.rack && !racks.has(item.rack)) {
                    racks.add(item.rack);
                    rackSelect.innerHTML += `<option value="${item.rack}">${item.rack}</option>`;
                }

                if (item.level && !levels.has(item.level)) {
                    levels.add(item.level);
                    levelSelect.innerHTML += `<option value="${item.level}">${item.level}</option>`;
                }

            });
        }

        // ══════════════════════════════════
        //  RENDER TABLE
        // ══════════════════════════════════
        function renderMovements() {
            const tbody = document.getElementById('movementsBody');
            const fragment = document.createDocumentFragment();

            tbody.innerHTML = '';

            const start = (pagination.current_page - 1) * pagination.per_page;
            const end = start + pagination.per_page;
            const pageData = filteredMovements.slice(start, end);

            pageData.forEach(m => {

                let badgeClass, badgeIcon, badgeLabel;

                //alert(m.movementType);
                //SALE
                if (m.movementType === 'IN') {
                    badgeClass = 'badge-entrada';
                    badgeIcon = 'bi-box-arrow-in-down';
                    badgeLabel = 'Entrada';
                } else if (m.movementType === 'OUT') {
                    badgeClass = 'badge-salida';
                    badgeIcon = 'bi-box-arrow-up';
                    badgeLabel = 'Salida';
                } else if (m.movementType === 'TRANSFER') {
                    badgeClass = 'badge-transferencia';
                    badgeIcon = 'bi-arrow-left-right';
                    badgeLabel = 'Traslado';
                } else if (m.movementType === 'RELOCATION') {
                    badgeClass = 'badge-transferencia';
                    badgeIcon = 'bi-arrow-left-right';
                    badgeLabel = 'Reubicación';
                } else if (m.movementType === 'SALE') {
                    badgeClass = 'bi-cart-check';
                    badgeIcon = 'bi-cash-stack';
                    badgeLabel = 'Venta';
                } else {
                    badgeClass = 'badge-ajuste';
                    badgeIcon = 'bi-arrow-repeat';
                    badgeLabel = 'Ajuste';
                }

                const qtyClass = m.quantity > 0 ? 'positive' : 'negative';
                const qtyPrefix = m.movementType === 'IN' ? '+' : '-';

                let warehouseDisplay = m.warehousesName;
                if (m.movementType === 'TRANSFER' && m.reason) {
                    const match = m.reason.match(/Traslado de\s+(.+?)\s+a\s+(.+?):/);
                    if (match) {
                        warehouseDisplay =
                            `<span class="transfer-warehouse">${match[1]}</span> <i class="bi bi-arrow-right" style="font-size:0.65rem;margin:0 3px;color:#6d28d9;"></i> <span class="transfer-warehouse-dest">${match[2]}</span>`;
                    }
                }

                if (m.movementType === 'RELOCATION' && m.reason) {
                    warehouseDisplay = m.reason;
                }

                const tr = document.createElement('tr');
                tr.innerHTML = `
            <td style="font-weight:600; color:var(--tacsa-red);">${m.folio}</td>
            <td>${m.createdAt}</td>
            <td><span class="${badgeClass}"><i class="bi ${badgeIcon}"></i> ${badgeLabel}</span></td>
            <td>
                <div style="font-weight:500;">${m.productName}</div>
                <div style="font-size:0.6875rem; color:var(--text-secondary);">${m.productId}</div>
            </td>
            <td>${warehouseDisplay}</td>
            <td><span class="cell-qty ${qtyClass}">${qtyPrefix}${m.quantity}</span></td>
            <td><span class="badge-product">${m.lotNumber}</span></td>
        `;

                fragment.appendChild(tr);
            });

            tbody.appendChild(fragment);
        }

        // ══════════════════════════════════
        //  PAGINATION
        // ══════════════════════════════════
        function updatePagination() {
            const from = (pagination.current_page - 1) * pagination.per_page + 1;
            const to = Math.min(pagination.current_page * pagination.per_page, pagination.total);

            document.getElementById('showingFrom').textContent = pagination.total > 0 ? from : 0;
            document.getElementById('showingTo').textContent = to;
            document.getElementById('showingTotal').textContent = pagination.total;

            document.getElementById('prevPageBtn').disabled = pagination.current_page <= 1;
            document.getElementById('nextPageBtn').disabled = pagination.current_page >= pagination.last_page;

            const pageNumbers = document.getElementById('pageNumbers');
            pageNumbers.innerHTML = '';

            let startPage = Math.max(1, pagination.current_page - 2);
            let endPage = Math.min(pagination.last_page, pagination.current_page + 2);

            if (endPage - startPage < 4) {
                if (startPage === 1) {
                    endPage = Math.min(5, pagination.last_page);
                } else {
                    startPage = Math.max(1, pagination.last_page - 4);
                }
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
            if (newPage >= 1 && newPage <= pagination.last_page) {
                goToPage(newPage);
            }
        }

        function goToPage(page) {
            pagination.current_page = page;
            updatePagination();
            renderMovements();
            window.scrollTo({
                top: 0,
                behavior: 'smooth'
            });
        }

        function formatDate(dateStr) {
            const d = new Date(dateStr);
            return d.toLocaleDateString('es-MX', {
                day: '2-digit',
                month: 'short',
                year: 'numeric'
            });
        }

        // ══════════════════════════════════
        //  FILTER
        // ══════════════════════════════════
        function filterMovements() {
            const search = document.getElementById('searchMovements').value.toLowerCase();
            const typeFilter = document.getElementById('filterType').value;

            filteredMovements = allMovements.filter(m => {
                const matchesSearch = !search ||
                    (m.productName && m.productName.toLowerCase().includes(search)) ||
                    (m.productId && m.productId.toLowerCase().includes(search)) ||
                    (m.lotNumber && m.lotNumber.toLowerCase().includes(search)) ||
                    (m.warehousesName && m.warehousesName.toLowerCase().includes(search)) ||
                    (m.folio && m.folio.toLowerCase().includes(search));

                let typeMatch = false;
                if (!typeFilter) {
                    typeMatch = true;
                } else if (typeFilter === 'entrada' && m.movementType === 'IN') {
                    typeMatch = true;
                } else if (typeFilter === 'salida' && m.movementType === 'OUT') {
                    typeMatch = true;
                } else if (typeFilter === 'traslado' && m.movementType === 'TRANSFER') {
                    typeMatch = true;
                } else if (typeFilter === 'ajuste' && m.movementType === 'ADJUSTMENT') {
                    typeMatch = true;
                } else if (typeFilter === 'relocation' && m.movementType === 'RELOCATION') {
                    typeMatch = true;
                } else if (typeFilter === 'sales' && m.movementType === 'SALE') {
                    typeMatch = true;
                }

                //sales
                return matchesSearch && typeMatch;
            });

            pagination.current_page = 1;
            pagination.total = filteredMovements.length;
            pagination.last_page = Math.ceil(filteredMovements.length / pagination.per_page) || 1;

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
        //  VIEW DETAIL
        // ══════════════════════════════════
        function viewDetail(folio) {
            const m = movements.find(x => x.folio === folio);
            if (!m) {
                console.log('Movimiento no encontrado para folio:', folio);
                return;
            }
            console.log(m.movementType);

            let badgeHtml;
            if (m.movementType === 'IN') badgeHtml =
                '<span class="badge-entrada"><i class="bi bi-box-arrow-in-down"></i> Entrada</span>';
            else if (m.movementType === 'OUT') badgeHtml =
                '<span class="badge-salida"><i class="bi bi-box-arrow-up"></i> Salida</span>';
            else if (m.movementType === 'RELOCATION') badgeHtml =
                '<span class="badge-reubicacion"><i class="bi bi-arrows-move"></i> Reubicación</span>';
            else if (m.movementType === 'TRANSFER') badgeHtml =
                '<span class="badge-traslado"><i class="bi bi-arrow-left-right"></i> Traslado</span>';
            else badgeHtml = '<span class="badge-ajuste"><i class="bi bi-arrow-repeat"></i> Ajuste</span>';

            let warehouseDisplay = m.warehousesName;
            let warehouseLabel = 'Almacén';
            if (m.movementType === 'TRANSFER' && m.reason) {
                const match = m.reason.match(/Traslado de\s+(.+?)\s+a\s+(.+?):/);
                if (match) {
                    warehouseDisplay =
                        `<span class="transfer-warehouse">${match[1]}</span> <i class="bi bi-arrow-right" style="font-size:0.8rem;margin:0 5px;color:#6d28d9;"></i> <span class="transfer-warehouse-dest">${match[2]}</span>`;
                    warehouseLabel = 'Origen → Destino';
                }
            }

            document.getElementById('detailBody').innerHTML = `
                <div class="detail-row"><span class="detail-label">Folio</span><span class="detail-value" style="font-weight:600; color:var(--tacsa-red);">${m.folio}</span></div>
                <div class="detail-row"><span class="detail-label">Fecha</span><span class="detail-value">${formatDate(m.createdAt)}</span></div>
                <div class="detail-row"><span class="detail-label">Tipo</span><span class="detail-value">${badgeHtml}</span></div>
                <div class="detail-row"><span class="detail-label">Producto</span><span class="detail-value">${m.productName} (${m.productId})</span></div>
                <div class="detail-row"><span class="detail-label">${warehouseLabel}</span><span class="detail-value">${warehouseDisplay}</span></div>
                <div class="detail-row"><span class="detail-label">Cantidad</span><span class="detail-value cell-qty ${m.quantity > 0 ? 'positive' : 'negative'}">${m.quantity > 0 ? '+' : ''}${m.quantity}</span></div>
                <div class="detail-row"><span class="detail-label">Lote</span><span class="detail-value"><span class="badge-product">${m.lotNumber}</span></span></div>
                <div class="detail-row"><span class="detail-label">Rack / nivel</span><span class="detail-value">${m.rack} / ${m.level}</span></div>
                <div class="detail-row"><span class="detail-label">Modulo  / Bahía</span><span class="detail-value">${m.rack} / ${m.level}</span></div>
                <div class="detail-row"><span class="detail-label">Motivo</span><span class="detail-value">${m.reason || '-'}</span></div>
                <div class="detail-row"><span class="detail-label">Usuario</span><span class="detail-value">${m.userName || '-'}</span></div>
            `;

            const modalEl = document.getElementById('detailModal');
            if (modalEl) {
                modalEl.style.display = 'block';
                modalEl.classList.add('show');
                modalEl.removeAttribute('aria-hidden');
                modalEl.setAttribute('aria-modal', 'true');
                modalEl.setAttribute('role', 'dialog');

                // Create backdrop if not exists
                let backdrop = document.querySelector('.modal-backdrop.show');
                if (!backdrop) {
                    backdrop = document.createElement('div');
                    backdrop.className = 'modal-backdrop show';
                    backdrop.style.position = 'fixed';
                    backdrop.style.top = '0';
                    backdrop.style.left = '0';
                    backdrop.style.width = '100vw';
                    backdrop.style.height = '100vh';
                    backdrop.style.backgroundColor = 'rgba(0,0,0,0.5)';
                    backdrop.style.zIndex = '1050';
                    document.body.appendChild(backdrop);
                }

                // Close on backdrop click
                backdrop.onclick = function() {
                    closeDetailModal();
                };
            } else {
                console.error('Modal no encontrado');
            }
        }

        function closeDetailModal() {
            const modalEl = document.getElementById('detailModal');
            const backdrop = document.querySelector('.modal-backdrop.show');
            if (modalEl) {
                modalEl.style.display = 'none';
                modalEl.classList.remove('show');
            }
            if (backdrop) {
                backdrop.remove();
            }
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
            if (deleteTarget) {
                const idx = movements.findIndex(m => m.id === deleteTarget);
                if (idx > -1) movements.splice(idx, 1);
                bootstrap.Modal.getInstance(document.getElementById('deleteModal')).hide();
                filterMovements();
                showToast('Movimiento eliminado correctamente', 'error');
                deleteTarget = null;
            }
        }

        // ══════════════════════════════════
        //  FORM HANDLERS
        // ══════════════════════════════════
        function handleEntrada(e) {
            e.preventDefault();
            bootstrap.Modal.getInstance(document.getElementById('entradaModal')).hide();
            e.target.reset();
            showToast('Entrada registrada exitosamente', 'success');
            return false;
        }

        function handleSalida(e) {
            e.preventDefault();
            bootstrap.Modal.getInstance(document.getElementById('salidaModal')).hide();
            e.target.reset();
            showToast('Salida registrada exitosamente', 'success');
            return false;
        }

        // ══════════════════════════════════
        //  TOAST
        // ══════════════════════════════════
        function showToast(msg, type) {
            const toast = document.getElementById('toast');
            const icon = document.getElementById('toastIcon');
            const msgEl = document.getElementById('toastMsg');

            msgEl.textContent = msg;
            icon.className = 'toast-icon ' + type;
            icon.innerHTML = type === 'success' ?
                '<i class="bi bi-check-lg"></i>' :
                '<i class="bi bi-trash3"></i>';

            toast.classList.add('show');
            setTimeout(() => toast.classList.remove('show'), 3000);
        }

        // ══════════════════════════════════
        //  INLINE REPORTS (Tab based)
        // ══════════════════════════════════
        let currentReportType = null;

        const reportTitles = {
            bodega: 'Reporte por Bodega',
            articulo: 'Reporte por Articulo',
            caducidad: 'Reporte por Caducidad',
            periodo: 'Movimientos por Periodo'
        };

        // Sample data for reports
        const sampleInventoryData = [{
                id: 1,
                warehouseId: 13,
                productCode: 'mf019',
                rack: 'R-01',
                level: 2,
                product: 'Tacsa Power; 50 kgs.',
                quantity: 5,
                lote: '2029-001',
                reason: 'Ajuste',
                expiration: '2036-08-25',
                warehouse: 'Almacen Central'
            },
            {
                id: 2,
                warehouseId: 13,
                productCode: 'pi004',
                rack: 'R-02',
                level: 2,
                product: 'Anibac Plus; 20 lts.',
                quantity: 25,
                lote: '2029-001',
                reason: 'Ajuste',
                expiration: '2029-03-07',
                warehouse: 'Almacen Central'
            },
            {
                id: 6,
                warehouseId: 13,
                productCode: 'fo048',
                rack: 'R-05',
                level: 3,
                product: 'Agrovida; 1 lt.',
                quantity: 25,
                lote: '2026-001',
                reason: 'Ajuste',
                expiration: '2030-02-26',
                warehouse: 'Almacen Central'
            },
            {
                id: 7,
                warehouseId: 14,
                productCode: 'mf019',
                rack: 'R-01',
                level: 1,
                product: 'Tacsa Power; 50 kgs.',
                quantity: 12,
                lote: '2029-002',
                reason: 'Entrada',
                expiration: '2035-12-15',
                warehouse: 'Almacen Norte'
            },
            {
                id: 8,
                warehouseId: 15,
                productCode: 'pi004',
                rack: 'R-03',
                level: 2,
                product: 'Anibac Plus; 20 lts.',
                quantity: 8,
                lote: '2028-003',
                reason: 'Entrada',
                expiration: '2028-06-10',
                warehouse: 'Almacen Sur'
            }
        ];

        const sampleMovements = [{
                id: 1,
                date: '2026-02-20',
                type: 'Entrada',
                product: 'Tacsa Power; 50 kgs.',
                quantity: 10,
                warehouse: 'Almacen Central',
                user: 'Juan Perez'
            },
            {
                id: 2,
                date: '2026-02-21',
                type: 'Salida',
                product: 'Anibac Plus; 20 lts.',
                quantity: 5,
                warehouse: 'Almacen Central',
                user: 'Maria Garcia'
            },
            {
                id: 3,
                date: '2026-02-22',
                type: 'Ajuste',
                product: 'Agrovida; 1 lt.',
                quantity: -2,
                warehouse: 'Almacen Central',
                user: 'Carlos Lopez'
            },
            {
                id: 4,
                date: '2026-02-25',
                type: 'Entrada',
                product: 'Tacsa Power; 50 kgs.',
                quantity: 15,
                warehouse: 'Almacen Norte',
                user: 'Ana Martinez'
            },
            {
                id: 5,
                date: '2026-03-01',
                type: 'Salida',
                product: 'Anibac Plus; 20 lts.',
                quantity: 3,
                warehouse: 'Almacen Sur',
                user: 'Pedro Sanchez'
            }
        ];

        function goToReportesTab() {
            const tabBtn = document.getElementById('tabReportes');
            switchTab('reportes', tabBtn);
        }

        function selectReportType(type) {
            currentReportType = type;

            // Highlight selected card
            document.querySelectorAll('.report-type-card').forEach(c => c.classList.remove('selected'));
            document.getElementById('rptType-' + type).classList.add('selected');

            // Show filters panel
            document.getElementById('reportFiltersPanel').style.display = 'block';
            document.getElementById('reportFiltersPanelTitle').textContent = reportTitles[type];

            // Toggle filter groups
            document.querySelectorAll('.report-filter-group').forEach(g => g.style.display = 'none');
            const groupMap = {
                bodega: 'filtersForBodega',
                articulo: 'filtersForArticulo',
                caducidad: 'filtersForCaducidad',
                periodo: 'filtersForPeriodo'
            };
            document.getElementById(groupMap[type]).style.display = 'block';

            // Reset results
            document.getElementById('reportResults').style.display = 'none';

            // Check filters
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
            let valid = false;
            switch (currentReportType) {
                case 'bodega':
                    valid = document.getElementById('rptBodegaSelect').value !== '';
                    document.getElementById('btnGenBodega').disabled = !valid;
                    break;
                case 'articulo':
                    valid = document.getElementById('rptArticuloSelect').value !== '';
                    document.getElementById('btnGenArticulo').disabled = !valid;
                    break;
                case 'caducidad':
                    valid = document.getElementById('rptCaducidadRango').value !== '';
                    document.getElementById('btnGenCaducidad').disabled = !valid;
                    break;
                case 'periodo':
                    const inicio = document.getElementById('rptPeriodoInicio').value;
                    const fin = document.getElementById('rptPeriodoFin').value;
                    valid = inicio !== '' && fin !== '';
                    document.getElementById('btnGenPeriodo').disabled = !valid;
                    break;
            }
        }

        function generateReportInline(type) {
            let data = [];
            let headers = [];
            let title = '';

            switch (type) {
                case 'bodega':
                    const bodegaId = parseInt(document.getElementById('rptBodegaSelect').value);
                    const rackFilter = document.getElementById('rptBodegaRack').value;
                    const nivelFilter = document.getElementById('rptBodegaNivel').value;

                    data = sampleInventoryData.filter(d => {
                        let match = d.warehouseId === bodegaId;
                        if (rackFilter) match = match && d.rack === rackFilter;
                        if (nivelFilter) match = match && d.level === parseInt(nivelFilter);
                        return match;
                    });
                    headers = ['Producto', 'Codigo', 'Rack', 'Nivel', 'Cantidad', 'Lote', 'Caducidad'];
                    title = 'Inventario de ' + document.getElementById('rptBodegaSelect').selectedOptions[0].text;
                    break;

                case 'articulo':
                    const articuloCode = document.getElementById('rptArticuloSelect').value;
                    const artBodega = document.getElementById('rptArticuloBodega').value;

                    data = sampleInventoryData.filter(d => {
                        let match = d.productCode === articuloCode;
                        if (artBodega) match = match && d.warehouseId === parseInt(artBodega);
                        return match;
                    });
                    headers = ['Bodega', 'Rack', 'Nivel', 'Cantidad', 'Lote', 'Caducidad'];
                    title = 'Existencias de ' + document.getElementById('rptArticuloSelect').selectedOptions[0].text;
                    break;

                case 'caducidad':
                    const reportCaducidadUrl = "{{ route('warehouse-movements.expiration-report') }}";

                    fetch(reportCaducidadUrl)
                        .then(response => response.json())
                        .then(result => {
                            const headers = ['Codigo', 'Producto', 'Bodega', 'Cantidad', 'Lote', 'Caducidad',
                                'Dias Caducado'
                            ];
                            const title = 'Productos Caducados';
                            renderReportTable('caducidad', headers, result.data, title);
                            renderRankingCaducidad(result.ranking);
                        })
                        .catch(error => console.error('Error:', error));
                    break;

                case 'periodo':
                    const fechaInicio = document.getElementById('rptPeriodoInicio').value;
                    const fechaFin = document.getElementById('rptPeriodoFin').value;
                    const tipoMov = document.getElementById('rptPeriodoTipoInline').value;
                    const perBodega = document.getElementById('rptPeriodoBodega').value;

                    const reportMovementsUrl = "{{ route('warehouse-movements.report') }}";

                    const formData = new FormData();
                    formData.append('fecha_inicio', fechaInicio);
                    formData.append('fecha_fin', fechaFin);
                    formData.append('tipo_movimiento', tipoMov);
                    formData.append('warehouse_id', perBodega);

                    fetch(reportMovementsUrl, {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                            },
                            body: formData
                        })
                        .then(response => {
                            return response.json();
                        })
                        .then(result => {

                            data = result.data;
                            headers = [
                                'Folio',
                                'Fecha',
                                'Tipo',
                                'Producto',
                                'Bodega',
                                'Cantidad',
                                'Lote'
                            ];

                            title = 'Movimientos del ' + fechaInicio + ' al ' + fechaFin;

                            renderReportTable(type, headers, data, title);
                        });
            }

            renderReportTable(type, headers, data, title);
        }

        function renderReportTable(type, headers, data, title) {
            const resultsDiv = document.getElementById('reportResults');
            const tableHead = document.getElementById('reportTableHead');
            const tableBody = document.getElementById('reportTableBody');
            const emptyDiv = document.getElementById('reportEmpty');

            document.getElementById('reportResultsTitle').textContent = title;
            document.getElementById('resultsCount').textContent = data.length + ' registros encontrados';

            // Build headers
            tableHead.innerHTML = '<tr>' + headers.map(h => '<th>' + h + '</th>').join('') + '</tr>';

            // Build rows
            if (data.length === 0) {
                tableBody.innerHTML = '';
                emptyDiv.style.display = 'block';
                document.querySelector('.table-responsive').style.display = 'none';
            } else {
                emptyDiv.style.display = 'none';
                document.querySelector('#reportResults .table-responsive').style.display = 'block';

                let rows = '';

                data.forEach(d => {
                    switch (type) {
                        case 'bodega':
                            rows += '<tr><td>' + d.product + '</td><td>' + d.productCode + '</td><td>' + d.rack +
                                '</td><td>Nivel ' + d.level + '</td><td>' + d.quantity + '</td><td>' + d.lote +
                                '</td><td>' + d.expiration + '</td></tr>';
                            break;
                        case 'articulo':
                            rows += '<tr><td>' + d.warehouse + '</td><td>' + d.rack + '</td><td>Nivel ' + d.level +
                                '</td><td>' + d.quantity + '</td><td>' + d.lote + '</td><td>' + d.expiration +
                                '</td></tr>';
                            break;
                        case 'caducidad':
                            const today = new Date();
                            const expDate = new Date(d.expiration);
                            const diffDays = Math.ceil((expDate - today) / (1000 * 60 * 60 * 24));
                            let status = '<span class="movement-badge badge-entry">Vigente</span>';
                            if (diffDays < 0) status = '<span class="movement-badge badge-exit">Caducado</span>';
                            else if (diffDays <= 30) status =
                                '<span class="movement-badge badge-adjust">Por caducar</span>';
                            rows += '<tr><td>' + d.productCode + '</td><td>' + d.productName + '</td><td>' + d
                                .warehouseName +
                                '</td><td>' + d.quantity + '</td><td>' + d.lotNumber + '</td><td>' + d
                                .expirationDate +
                                '</td><td><span class="badge bg-danger">' + (d.expiredDays || 0) +
                                ' dias</span>    </td></tr>';
                            //
                            break;
                        case 'periodo':
                            rows += '<tr><td>' + d.folio + '</td><td>' + d.createdAt + '</td><td>' + d
                                .movementType +
                                '</td><td>' + d.productName + '</td><td>' + d.warehousesName + '</td><td>' + d
                                .quantity + '</td><td>' + d.lotNumber + '</td><td>' + '</td></tr>';
                            break;
                    }
                });
                tableBody.innerHTML = rows;
            }

            resultsDiv.style.display = 'block';
            resultsDiv.scrollIntoView({
                behavior: 'smooth',
                block: 'start'
            });
        }


        // ══════════════════════════════════
        //  INVENTORY FILTERS
        // ══════════════════════════════════
        function filterInventory() {
            const producto = document.getElementById('filterProducto').value;
            const bodega = document.getElementById('filterBodega').value;
            const rack = document.getElementById('filterRack').value;
            const nivel = document.getElementById('filterNivel').value;
            const items = document.querySelectorAll('.inventory-item');
            const statusBar = document.getElementById('filterStatus');
            const hasFilter = producto || bodega || rack || nivel;
            let visible = 0;

            // Remove old no-results msg
            const oldMsg = document.querySelector('.no-results-filter');
            if (oldMsg) oldMsg.remove();

            items.forEach(item => {
                let show = true;
                if (producto && item.dataset.product !== producto) {
                    show = false;
                }

                if (bodega && item.dataset.warehouse !== bodega) {
                    show = false;
                }
                if (rack && item.dataset.rack !== rack) {
                    show = false;
                }

                if (nivel && item.dataset.level !== nivel) {
                    show = false;
                }

                if (show) {
                    item.classList.remove('hidden-filter');
                    visible++;
                } else {
                    item.classList.add('hidden-filter');
                }
            });

            // Show / hide status bar
            if (hasFilter) {
                statusBar.style.cssText = 'display:flex!important;';
                document.getElementById('filterCount').textContent = visible;
            } else {
                statusBar.style.cssText = 'display:none!important;';
            }

            // No results message
            if (visible === 0 && hasFilter) {
                const grid = document.querySelector('.inventory-grid');
                const msg = document.createElement('div');
                msg.className = 'no-results-filter';
                msg.innerHTML = '<i class="bi bi-search"></i>No se encontraron productos con los filtros seleccionados';
                grid.appendChild(msg);
            }
        }

        function clearFilters() {
            document.getElementById('filterProducto').value = '';
            document.getElementById('filterBodega').value = '';
            document.getElementById('filterRack').value = '';
            document.getElementById('filterNivel').value = '';
            filterInventory();
        }
        // ══════════════════════════════════
        //  INIT
        // ══════════════════════════════════
        renderMovements();
    </script>
@endpush
