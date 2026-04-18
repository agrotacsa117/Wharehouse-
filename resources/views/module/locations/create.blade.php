@extends('layouts.main')

@section('contenido')

    <!DOCTYPE html>
    <html lang="es">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>TACSA - Registro de Ubicaciones</title>
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

            /* ── Card container ── */
            .form-card {
                max-width: 720px;
                margin: 0 auto;
                background: var(--bg-card);
                border-radius: 12px;
                border: 1px solid var(--border-color);
                box-shadow: 0 1px 3px rgba(0, 0, 0, 0.06), 0 1px 2px rgba(0, 0, 0, 0.04);
                overflow: hidden;
            }

            /* ── Header ── */
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

            /* ── Form body ── */
            .form-body {
                padding: 0 2rem 2rem;
            }

            /* ── Section title ── */
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

            /* ── Separator ── */
            .section-separator {
                border: none;
                height: 1px;
                background-color: var(--tacsa-red-light);
                margin: 2rem 0;
            }

            /* ── Form fields ── */
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

            .form-control.tacsa-input {
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

            .form-control.tacsa-input:focus {
                border-color: var(--tacsa-red);
                box-shadow: 0 0 0 3px rgba(220, 38, 38, 0.12);
                outline: none;
            }

            /* ── Action buttons ── */
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

            /* ── Responsive ── */
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

            <!-- ── Header ── -->
            <div class="form-header">
                <div class="logo-wrapper">
                    <img src="https://hebbkx1anhila5yf.public.blob.vercel-storage.com/image-3pBfz8jhasVBCCRAl1NOQFuUdXmn8i.png"
                        alt="">
                </div>
                <h1 class="form-title">Registro de Ubicaciones</h1>
                <p class="form-subtitle">Complete el formulario con la informacion de la ubicacion</p>
            </div>


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
            <!-- ── Form ── -->
            <form id="locationForm" class="form-body" novalidate method="POST" action="{{ route('locations.store') }}">
                @csrf
                <!-- Seccion: Informacion de la Sede -->
                <div class="section-title">
                    <div class="bar"></div>
                    <h2>Informacion de la Sede</h2>
                </div>
                <div class="row g-3">
                    <div class="col-md-6">
                        <label for="headquartersName" class="field-label">
                            Nombre de la Sede <span class="required">*</span>
                        </label>
                        <input type="text" class="form-control tacsa-input" id="headquartersName" name="headquartersName"
                            placeholder="Ej: Sede Central" required>
                    </div>
                    <div class="col-md-6">
                        <label for="postalCode" class="field-label">
                            Codigo Postal <span class="required">*</span>
                        </label>
                        <input type="number" class="form-control tacsa-input" id="postalCode" name="postalCode"
                            placeholder="Ej: 06600" required>
                    </div>
                </div>

                <hr class="section-separator">

                <!-- Seccion: Ubicacion Geografica -->
                <div class="section-title">
                    <div class="bar"></div>
                    <h2>Ubicacion Geografica</h2>
                </div>
                <div class="row g-3">
                    <div class="col-md-6">
                        <label for="state" class="field-label">
                            Estado <span class="required">*</span>
                        </label>
                        <input type="text" class="form-control tacsa-input" id="state" name="state"
                            placeholder="Ej: Ciudad de Mexico" required>
                    </div>
                    <div class="col-md-6">
                        <label for="city" class="field-label">
                            Ciudad <span class="required">*</span>
                        </label>
                        <input type="text" class="form-control tacsa-input" id="city" name="city"
                            placeholder="Ej: Cuauhtemoc" required>
                    </div>
                </div>

                <hr class="section-separator">

                <!-- Seccion: Direccion -->
                <div class="section-title">
                    <div class="bar"></div>
                    <h2>Direccion</h2>
                </div>
                <div class="row g-3">
                    <div class="col-12">
                        <label for="address" class="field-label">
                            Direccion Completa <span class="required">*</span>
                        </label>
                        <input type="text" class="form-control tacsa-input" id="address" name="address"
                            placeholder="Calle, Numero, Colonia" required>
                    </div>
                </div>

                <hr class="section-separator">

                <!-- Botones de accion -->
                <div class="form-actions">
                    <button type="button" class="btn-tacsa-cancel" id="btnCancel"
                    onclick="window.location.href='{{ route('warehouse-managment.get') }}'">
                        <i class="bi bi-x-lg"></i>
                        Cancelar
                    </button>
                    <button type="submit" class="btn-tacsa-save">
                        <i class="bi bi-check-lg"></i>
                        Guardar Ubicacion
                    </button>
                </div>

            </form>
        </div>

        <!-- Bootstrap 5 JS -->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    </body>

    </html>
