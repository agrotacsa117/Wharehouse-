@section('contenido')

    <!DOCTYPE html>
    <html lang="es">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>TACSA - Registro de Inventario de las Bodegas</title>
        <!-- Bootstrap 5 CSS -->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
        <!-- Bootstrap Icons -->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
        <!-- Google Fonts -->
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
        <style>
            :root {
                --tacsa-red: #DC2626;
                --tacsa-red-dark: #B91C1C;
                --tacsa-red-light: rgba(220, 38, 38, 0.15);
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
                padding: 3rem 1rem;
            }

            /* -- Card container -- */
            .form-card {
                max-width: 720px;
                margin: 0 auto;
                background: var(--bg-card);
                border-radius: 12px;
                border: 1px solid var(--border-color);
                box-shadow: 0 1px 3px rgba(0, 0, 0, 0.06), 0 1px 2px rgba(0, 0, 0, 0.04);
                overflow: hidden;
            }

            /* -- Header -- */
            .form-header {
                padding: 2.5rem 2rem 1.5rem;
            }

            .logo-wrapper {
                position: relative;
                display: flex;
                align-items: center;
                justify-content: center;
            }

            .logo-line {
                position: absolute;
                top: 50%;
                left: 0;
                right: 0;
                height: 6px;
                background-color: var(--tacsa-red);
                transform: translateY(-50%);
            }

            .logo-circle {
                position: relative;
                z-index: 1;
                width: 112px;
                height: 112px;
                border-radius: 50%;
                border: 4px solid var(--tacsa-red);
                background: var(--bg-card);
                display: flex;
                align-items: center;
                justify-content: center;
            }

            .logo-circle span {
                font-size: 1.25rem;
                font-weight: 700;
                color: var(--tacsa-red);
                text-align: center;
                line-height: 1.2;
            }

            .form-title {
                font-size: 1.5rem;
                font-weight: 700;
                color: var(--tacsa-red);
                text-align: center;
                margin-top: 2rem;
                margin-bottom: 0.5rem;
            }

            .form-subtitle {
                font-size: 0.875rem;
                color: var(--text-secondary);
                text-align: center;
                margin-bottom: 0;
            }

            /* -- Form body -- */
            .form-body {
                padding: 0 2rem 2rem;
            }

            /* -- Section title -- */
            .section-title {
                display: flex;
                align-items: center;
                gap: 0.75rem;
                margin-bottom: 1.25rem;
            }

            .section-title .bar {
                width: 4px;
                height: 24px;
                background-color: var(--tacsa-red);
                border-radius: 9999px;
                flex-shrink: 0;
            }

            .section-title h2 {
                font-size: 1.125rem;
                font-weight: 600;
                color: var(--tacsa-red);
                margin: 0;
            }

            /* -- Separator -- */
            .section-separator {
                border: none;
                height: 1px;
                background-color: var(--tacsa-red-light);
                margin: 2rem 0;
            }

            /* -- Form fields -- */
            .field-label {
                font-size: 0.875rem;
                font-weight: 500;
                color: var(--text-primary);
                margin-bottom: 0.5rem;
            }

            .field-label .required {
                color: var(--tacsa-red);
                margin-left: 2px;
            }

            /* -- Shared input / select styles -- */
            .form-control.tacsa-input,
            .form-select.tacsa-select {
                height: 48px;
                border-radius: 8px;
                border: 1px solid var(--border-color);
                padding: 0 1rem;
                font-size: 0.875rem;
                color: var(--text-primary);
                background-color: var(--bg-card);
                transition: border-color 0.2s ease, box-shadow 0.2s ease;
            }

            .form-control.tacsa-input::placeholder {
                color: #9ca3af;
            }

            .form-control.tacsa-input:focus,
            .form-select.tacsa-select:focus {
                border-color: var(--tacsa-red);
                box-shadow: 0 0 0 3px rgba(220, 38, 38, 0.12);
                outline: none;
            }

            .form-select.tacsa-select {
                padding-right: 2.5rem;
                appearance: none;
                background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='16' height='16' viewBox='0 0 16 16'%3E%3Cpath fill='none' stroke='%236b7280' stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='m4 6 4 4 4-4'/%3E%3C/svg%3E");
                background-repeat: no-repeat;
                background-position: right 0.75rem center;
                background-size: 16px 16px;
            }

            /* placeholder color for unselected select */
            .form-select.tacsa-select:invalid,
            .form-select.tacsa-select option[value=""] {
                color: #9ca3af;
            }

            .form-select.tacsa-select option {
                color: var(--text-primary);
            }

            /* -- Helper text -- */
            .field-helper {
                font-size: 0.75rem;
                color: var(--text-secondary);
                margin-top: 0.375rem;
            }

            /* -- Action buttons -- */
            .form-actions {
                display: flex;
                justify-content: flex-end;
                align-items: center;
                gap: 1rem;
                padding-top: 0.5rem;
            }

            .btn-tacsa-cancel {
                height: 44px;
                padding: 0 1.5rem;
                font-size: 0.875rem;
                font-weight: 500;
                border-radius: 8px;
                border: 1px solid var(--border-color);
                background: var(--bg-card);
                color: var(--text-primary);
                display: inline-flex;
                align-items: center;
                gap: 0.5rem;
                transition: background-color 0.15s ease, border-color 0.15s ease;
                cursor: pointer;
            }

            .btn-tacsa-cancel:hover {
                background-color: #f9fafb;
                border-color: #d1d5db;
            }

            .btn-tacsa-save {
                height: 44px;
                padding: 0 1.5rem;
                font-size: 0.875rem;
                font-weight: 500;
                border-radius: 8px;
                border: none;
                background: var(--tacsa-red);
                color: #ffffff;
                display: inline-flex;
                align-items: center;
                gap: 0.5rem;
                transition: background-color 0.15s ease;
                cursor: pointer;
            }

            .btn-tacsa-save:hover {
                background-color: var(--tacsa-red-dark);
            }

            /* -- Responsive -- */
            @media (max-width: 576px) {
                body {
                    padding: 1rem 0.5rem;
                }

                .form-header {
                    padding: 2rem 1.25rem 1.25rem;
                }

                .form-body {
                    padding: 0 1.25rem 1.5rem;
                }

                .form-actions {
                    flex-direction: column;
                }

                .form-actions button {
                    width: 100%;
                    justify-content: center;
                }
            }
        </style>
    </head>

    <body>

        <div class="form-card">

            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @if (session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif

            <!-- ===== Header ===== -->
            <div class="form-header">
                <div class="logo-wrapper">
                    <div class="logo-line"></div>
                    <div class="logo-circle">
                        <span>TACSA</span>
                    </div>
                </div>
                <h1 class="form-title">Registro de Inventario</h1>
                <p class="form-subtitle">Complete el formulario con la informacion del inventario de las bodegas</p>
            </div>

            <!-- ===== Form ===== -->
            <form id="warehouseInventoryForm" method="POST" action="{{ route('operation.get.store') }}" class="form-body"
                novalidate>
                @csrf
                <!-- ── Seccion 1: Producto y Almacen ── -->
                <div class="section-title">
                    <div class="bar"></div>
                    <h2>Producto y Bodega</h2>
                </div>

                <div class="row g-3">
                    <!-- productId (select) -->
                    <div class="col-md-6">
                        <label for="productId" class="field-label">
                            Producto <span class="required">*</span>
                        </label>
                        <select class="form-select tacsa-select" id="productId" name="productId" required>
                            <option value="" disabled selected>Seleccione un producto</option>

                            @foreach ($products as $product)
                                <option value="{{ $product->getId() }}">
                                    {{ $product->getProductName() }}
                                </option>
                            @endforeach
                        </select>
                        <p class="field-helper">Seleccione el producto que ingresara al inventario.</p>
                    </div>

                    <!-- warehouseId (select) -->
                    <div class="col-md-6">
                        <label for="warehouseId" class="field-label">
                            Bodega <span class="required">*</span>
                        </label>
                        <select class="form-select tacsa-select" id="warehouseId" name="warehouseId" required>
                            <option value="" disabled selected>Seleccione un almacen</option>
                            @foreach ($warehouses as $warehouse)
                                <option value="{{ $warehouse->getId() }}"
                                    {{ old('warehouseId') == $warehouse->getId() ? 'selected' : '' }}>
                                    {{ $warehouse->getWarehouseName() }}
                                </option>
                            @endforeach

                        </select>
                        <p class="field-helper">Seleccione el almacen donde se registrara el inventario.</p>
                    </div>
                </div>

                <hr class="section-separator">

                <!-- ── Seccion 2: Ubicacion en Rack ── -->
                <div class="section-title">
                    <div class="bar"></div>
                    <h2>Ubicacion</h2>
                </div>

                <div class="row g-3">
                    <!-- rack -->
                    <div class="col-md-6">
                        <label for="rack" class="field-label">
                            Rack <span class="required">*</span>
                        </label>
                        <input type="number" class="form-control tacsa-input" id="rack" name="rack"
                            placeholder="Ej: 01, 02" required>
                    </div>

                    <!-- level -->
                    <div class="col-md-6">
                        <label for="level" class="field-label">
                            Nivel <span class="required">*</span>
                        </label>
                        <input type="number" class="form-control tacsa-input" id="level" name="level"
                            placeholder="Ej: 1, 2, 3" min="1" required>
                    </div>

                    <div class="col-md-6">
                        <label for="module" class="field-label">
                            Modulo <span class="required">*</span>
                        </label>
                        <input type="number" class="form-control tacsa-input" id="module" name="module"
                            placeholder="Ej: 1, 2, 3" min="1" required>
                    </div>

                    <div class="col-md-6">
                        <label for="bay" class="field-label">
                            Bahía<span class="required">*</span>
                        </label>
                        <input type="number" class="form-control tacsa-input" id="bay" name="bay"
                            placeholder="Ej: 1, 2, 3" min="1" required>
                    </div>

                    <div class="col-md-6">
                        <label for="platform" class="field-label">
                            Tarima<span class="required">*</span>
                        </label>
                        <input type="number" class="form-control tacsa-input" id="platform" name="platform"
                            placeholder="1" min="1" required>
                    </div>
                </div>

                <hr class="section-separator">

                <!-- ── Seccion 3: Detalle del Inventario ── -->
                <div class="section-title">
                    <div class="bar"></div>
                    <h2>Detalle del Inventario</h2>
                </div>

                <div class="row g-3">
                    <!-- quantity -->
                    <div class="col-md-6">
                        <label for="quantity" class="field-label">
                            Cantidad <span class="required">*</span>
                        </label>
                        <input type="number" class="form-control tacsa-input" id="quantity" name="quantity"
                            placeholder="Ej: 100" min="1" required>
                    </div>

                    <!-- loteNumber -->
                    <div class="col-md-6">
                        <label for="loteNumber" class="field-label">
                            Numero de Lote <span class="required">*</span>
                        </label>
                        <input type="text" class="form-control tacsa-input" id="loteNumber" name="loteNumber"
                            placeholder="Ej: LOTE-2025-001" required>
                    </div>

                    <!-- expirationDate -->
                    <div class="col-md-6">
                        <label for="expirationDate" class="field-label">
                            Fecha de Caducidad <span class="required">*</span>
                        </label>
                        <input type="date" class="form-control tacsa-input" id="expirationDate" name="expirationDate"
                            required>
                    </div>

                    <!-- reason -->
                    <div class="col-md-6">
                        <label for="reason" class="field-label">
                            Motivo <span class="required">*</span>
                        </label>
                        <select class="form-control tacsa-input" id="reason" name="reason" required>
                            <option value="" disabled selected>Seleccione un motivo...</option>
                            <option value="Inventario Inicial">Inventario Inicial</option>
                            <option value="Ajuste de inventario">Recepción de traslado</option>
                            <option value="Reubicación">Reubicación</option>
                            <option value="Ajuste">Ajuste</option>
                        </select>
                    </div>

                    <div class="col-md-6" id="container-folio" style="display: none;">
                        <label for="transfer_folio" class="field-label">
                            Folio de Traslado <span class="required">*</span>
                        </label>
                        <input type="text" class="form-control tacsa-input" id="transfer_folio" name="transfer_folio"
                            placeholder="Ej: TR-10023">
                    </div>
                </div>

                <hr class="section-separator">

                <!-- ===== Action buttons ===== -->
                <div class="form-actions">
                    <button type="button" class="btn-tacsa-cancel" id="btnCancel"
                        onclick="window.location='{{ route('warehouse-movements.get') }}'">
                        <i class="bi bi-x-lg"></i>
                        Cancelar
                    </button>
                    <button type="submit" class="btn-tacsa-save">
                        <i class="bi bi-check-lg"></i>
                        Guardar Inventario
                    </button>
                </div>

            </form>
        </div>

        <!-- Bootstrap 5 JS -->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const reasonSelect = document.getElementById('reason');
                const folioContainer = document.getElementById('container-folio');
                const folioInput = document.getElementById('transfer_folio');

                reasonSelect.addEventListener('change', function() {
                    // Validamos contra el VALUE del option seleccionado
                    if (this.value === 'Ajuste de inventario') {
                        folioContainer.style.display = 'block';
                        folioInput.setAttribute('required', 'required');
                    } else {
                        folioContainer.style.display = 'none';
                        folioInput.removeAttribute('required');
                        folioInput.value = ''; // Limpiar el campo si se oculta
                    }
                });
            });
        </script>
    </body>

    </html>
